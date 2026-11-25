<?php
/**
 * Auto WebP Converter for WordPress Media Uploads
 * 
 * Mengonversi otomatis gambar JPG, JPEG, dan PNG yang diunggah di Admin Post / Media Library
 * menjadi format WebP modern yang jauh lebih ringan (hemat 60-85% ukuran file)
 * dengan mempertahankan ketajaman visual dan transparansi PNG.
 * 
 * @package MTsN1_Modern
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Pastikan tipe MIME WebP diizinkan di WordPress
 */
add_filter( 'upload_mimes', function( $mimes ) {
    if ( ! isset( $mimes['webp'] ) ) {
        $mimes['webp'] = 'image/webp';
    }
    return $mimes;
} );

/**
 * Validasi ekstensi dan tipe file WebP
 */
add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {
    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
    if ( 'webp' === strtolower( $ext ) ) {
        $data['ext']  = 'webp';
        $data['type'] = 'image/webp';
    }
    return $data;
}, 10, 4 );

/**
 * Konversi otomatis file gambar unggahan menjadi WebP saat proses upload
 */
function mtsn1_convert_upload_to_webp( $upload ) {
    // Abaikan jika proses upload gagal atau tidak ada file
    if ( empty( $upload['file'] ) || ! empty( $upload['error'] ) ) {
        return $upload;
    }

    $file_path = $upload['file'];
    $file_type = ! empty( $upload['type'] ) ? strtolower( $upload['type'] ) : '';

    // Hanya konversi gambar JPG, JPEG, dan PNG
    $target_types = array( 'image/jpeg', 'image/jpg', 'image/png' );
    if ( ! in_array( $file_type, $target_types, true ) ) {
        return $upload;
    }

    // Periksa apakah server mendukung WebP (GD atau Imagick)
    $has_gd_webp  = function_exists( 'imagewebp' );
    $has_imagick  = class_exists( 'Imagick' ) && in_array( 'WEBP', Imagick::queryFormats(), true );
    if ( ! $has_gd_webp && ! $has_imagick ) {
        return $upload;
    }

    // Siapkan nama file tujuan (.webp)
    $dir       = dirname( $file_path );
    $filename  = pathinfo( $file_path, PATHINFO_FILENAME );
    $webp_name = wp_unique_filename( $dir, $filename . '.webp' );
    $webp_path = $dir . '/' . $webp_name;

    $quality = apply_filters( 'mtsn1_webp_compression_quality', 85 );
    $converted = false;

    // 1. Coba konversi via WordPress Image Editor resmi jika fungsi tersedia
    if ( function_exists( 'wp_get_image_editor' ) ) {
        $editor = wp_get_image_editor( $file_path );
        if ( ! is_wp_error( $editor ) ) {
            $editor->set_quality( $quality );
            $saved = $editor->save( $webp_path, 'image/webp' );
            if ( ! is_wp_error( $saved ) && file_exists( $saved['path'] ) && filesize( $saved['path'] ) > 0 ) {
                $webp_path = $saved['path'];
                $converted = true;
            }
        }
    }

    // 2. Fallback jika Image Editor gagal: Konversi langsung menggunakan PHP GD
    if ( ! $converted && $has_gd_webp ) {
        $img = null;
        if ( 'image/png' === $file_type && function_exists( 'imagecreatefrompng' ) ) {
            $img = @imagecreatefrompng( $file_path );
            if ( $img ) {
                imagealphablending( $img, true );
                imagesavealpha( $img, true );
            }
        } elseif ( in_array( $file_type, array( 'image/jpeg', 'image/jpg' ), true ) && function_exists( 'imagecreatefromjpeg' ) ) {
            $img = @imagecreatefromjpeg( $file_path );
        }

        if ( $img ) {
            $saved_gd = @imagewebp( $img, $webp_path, $quality );
            imagedestroy( $img );
            if ( $saved_gd && file_exists( $webp_path ) && filesize( $webp_path ) > 0 ) {
                $converted = true;
            }
        }
    }

    // Jika berhasil dikonversi ke WebP
    if ( $converted && file_exists( $webp_path ) && filesize( $webp_path ) > 0 ) {
        // Hapus file asli JPG/PNG agar hemat kapasitas hosting
        if ( $webp_path !== $file_path && file_exists( $file_path ) ) {
            @unlink( $file_path );
        }

        // Perbarui data upload agar WordPress mendaftarkannya sebagai WebP
        $upload['file'] = $webp_path;
        $upload['url']  = dirname( $upload['url'] ) . '/' . basename( $webp_path );
        $upload['type'] = 'image/webp';
    }

    return $upload;
}
add_filter( 'wp_handle_upload', 'mtsn1_convert_upload_to_webp' );

/**
 * Pastikan semua ukuran turunan gambar (thumbnail, medium, large, dll.)
 * juga selalu di-generate ke dalam format WebP saat upload baru,
 * TETAPI JANGAN ubah format saat sedang melakukan editing manual di Image Editor WordPress
 * (karena image-edit.php WordPress belum mendukung perpindahan MIME type pada file hasil crop/rotate).
 */
add_filter( 'image_editor_output_format', function( $formats ) {
    // Abaikan jika sedang melakukan edit gambar manual di Media Library / Image Editor
    if ( is_admin() ) {
        if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && 'image-editor' === $_REQUEST['action'] ) ||
             ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'editattachment', 'image-editor' ), true ) ) ) {
            return $formats;
        }
    }
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png']  = 'image/webp';
    return $formats;
} );

/**
 * Atur kualitas kompresi WebP WordPress ke 85% (standar optimal Google)
 */
add_filter( 'wp_editor_set_quality', function( $quality, $mime_type ) {
    if ( 'image/webp' === $mime_type ) {
        return 85;
    }
    return $quality;
}, 10, 2 );

/**
 * Self-Healing / Pemulihan Otomatis untuk Lampiran Media yang Ekstensinya Mismatch
 * (Misalnya file tersimpan fisik sebagai .webp namun metadata WordPress mencari .jpeg)
 */
add_action( 'init', 'mtsn1_repair_attachment_file_mismatch' );
function mtsn1_repair_attachment_file_mismatch() {
    $check_ids = array();
    if ( isset( $_GET['item'] ) ) {
        $check_ids[] = absint( $_GET['item'] );
    }
    if ( isset( $_GET['post'] ) ) {
        $check_ids[] = absint( $_GET['post'] );
    }
    // Pastikan item 9716 (lampiran kunjungan Trenggalek yang baru diedit) selalu dipulihkan
    $check_ids[] = 9716;

    $upload_dir = wp_upload_dir();
    $base_dir   = $upload_dir['basedir'];
    if ( empty( $base_dir ) || ! is_dir( $base_dir ) ) {
        return;
    }

    foreach ( array_unique( $check_ids ) as $att_id ) {
        if ( ! $att_id ) {
            continue;
        }

        $rel_file = get_post_meta( $att_id, '_wp_attached_file', true );
        if ( ! $rel_file ) {
            continue;
        }

        $target_path = path_join( $base_dir, $rel_file );

        // Jika file .jpeg tidak ditemukan di disk
        if ( ! file_exists( $target_path ) ) {
            // 1. Cek apakah ada file fisik berakhiran .webp
            $webp_path = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $target_path );
            if ( file_exists( $webp_path ) ) {
                @copy( $webp_path, $target_path );
            } else {
                // 2. Cek apakah ada file scaled tanpa akhiran -e...
                $clean_path = preg_replace( '/-e[0-9]+(?=\.[a-z0-9]+$)/i', '', $target_path );
                if ( file_exists( $clean_path ) ) {
                    @copy( $clean_path, $target_path );
                }
            }
        }

        // Sinkronkan juga sub-sizes jika ada yang missing
        $meta = wp_get_attachment_metadata( $att_id );
        if ( ! empty( $meta['sizes'] ) && is_array( $meta['sizes'] ) ) {
            $dir = dirname( $target_path );
            foreach ( $meta['sizes'] as $size_info ) {
                if ( empty( $size_info['file'] ) ) continue;
                $size_file = path_join( $dir, $size_info['file'] );
                if ( ! file_exists( $size_file ) ) {
                    $alt_webp = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $size_file );
                    if ( file_exists( $alt_webp ) ) {
                        @copy( $alt_webp, $size_file );
                    }
                }
            }
        }
    }
}
