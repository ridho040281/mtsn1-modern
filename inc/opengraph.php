<?php
/**
 * Open Graph & Social Media Preview Meta Generator
 * MTsN 1 Blitar Modern Theme
 *
 * Menghasilkan metadata Open Graph, Twitter Cards, dan Schema
 * agar saat link artikel/berita dibagikan di WhatsApp, Facebook,
 * Twitter/X, Telegram, dll, thumbnail foto artikel langsung muncul.
 *
 * @package MTSN1_Modern
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Buat Kanvas Open Graph Universal (1200 x 760 px) dengan Safe-Zone Ganda
 * agar gambar/banner (baik 21:9, 16:9, maupun flyer 4:5) tampil 100% UTUH
 * tanpa terpotong di WhatsApp Chat (1.91:1) MAUPUN WhatsApp Story/Status (~1.58:1).
 *
 * @param string $source_url URL gambar asli
 * @param int    $thumb_id   Attachment ID (opsional)
 * @return array|false       Array ('url', 'width', 'height', 'type') atau false jika gagal
 */
function mtsn1_generate_social_og_canvas( $source_url, $thumb_id = 0 ) {
    if ( ! function_exists( 'imagecreatetruecolor' ) || ! function_exists( 'imagejpeg' ) ) {
        return false;
    }

    $local_path = '';
    if ( $thumb_id > 0 ) {
        $attached = get_attached_file( $thumb_id );
        if ( $attached && file_exists( $attached ) ) {
            $local_path = $attached;
        }
    }

    $upload_dir = wp_upload_dir();
    if ( empty( $local_path ) && ! empty( $source_url ) ) {
        if ( ! empty( $upload_dir['baseurl'] ) && strpos( $source_url, $upload_dir['baseurl'] ) === 0 ) {
            $rel = substr( $source_url, strlen( $upload_dir['baseurl'] ) );
            $candidate = $upload_dir['basedir'] . str_replace( '/', DIRECTORY_SEPARATOR, $rel );
            if ( file_exists( $candidate ) ) {
                $local_path = $candidate;
            }
        }
        if ( empty( $local_path ) ) {
            $home = home_url( '/' );
            if ( strpos( $source_url, $home ) === 0 ) {
                $rel = substr( $source_url, strlen( $home ) );
                $candidate = ABSPATH . str_replace( '/', DIRECTORY_SEPARATOR, $rel );
                if ( file_exists( $candidate ) ) {
                    $local_path = $candidate;
                }
            }
        }
    }

    if ( empty( $local_path ) || ! is_readable( $local_path ) ) {
        return false;
    }

    $info = @getimagesize( $local_path );
    if ( ! $info || empty( $info[0] ) || empty( $info[1] ) ) {
        return false;
    }

    $src_w = (int) $info[0];
    $src_h = (int) $info[1];
    $mime  = $info['mime'] ?? '';

    $canvas_w = 1200;
    $canvas_h = 760;

    // Direktori cache di wp-content/uploads/mtsn1-og/
    $og_dir_path = trailingslashit( $upload_dir['basedir'] ) . 'mtsn1-og';
    $og_dir_url  = trailingslashit( $upload_dir['baseurl'] ) . 'mtsn1-og';

    if ( ! file_exists( $og_dir_path ) ) {
        wp_mkdir_p( $og_dir_path );
    }

    $mtime      = @filemtime( $local_path );
    $hash       = substr( md5( 'og_v2_story_chat_' . $local_path . '_' . $mtime ), 0, 14 );
    $cache_file = 'og-' . $hash . '.jpg';
    $cache_path = trailingslashit( $og_dir_path ) . $cache_file;
    $cache_url  = trailingslashit( $og_dir_url ) . $cache_file;

    if ( file_exists( $cache_path ) && filesize( $cache_path ) > 1024 ) {
        return array(
            'url'    => $cache_url,
            'width'  => $canvas_w,
            'height' => $canvas_h,
            'type'   => 'image/jpeg',
        );
    }

    // Muat gambar sumber menggunakan GD
    $src_img = false;
    if ( 'image/webp' === $mime && function_exists( 'imagecreatefromwebp' ) ) {
        $src_img = @imagecreatefromwebp( $local_path );
    } elseif ( 'image/jpeg' === $mime && function_exists( 'imagecreatefromjpeg' ) ) {
        $src_img = @imagecreatefromjpeg( $local_path );
    } elseif ( 'image/png' === $mime && function_exists( 'imagecreatefrompng' ) ) {
        $src_img = @imagecreatefrompng( $local_path );
    }
    if ( ! $src_img && function_exists( 'imagecreatefromstring' ) ) {
        $raw = @file_get_contents( $local_path );
        if ( $raw ) {
            $src_img = @imagecreatefromstring( $raw );
        }
    }

    if ( ! $src_img ) {
        return false;
    }

    $canvas = imagecreatetruecolor( $canvas_w, $canvas_h );
    if ( ! $canvas ) {
        imagedestroy( $src_img );
        return false;
    }

    // 1. Buat Ambient Blurred Backdrop dari warna asli gambar agar menyatu elegan
    $blur_w = 120;
    $blur_h = 76;
    $blur_buf = imagecreatetruecolor( $blur_w, $blur_h );
    if ( $blur_buf ) {
        imagecopyresampled( $blur_buf, $src_img, 0, 0, 0, 0, $blur_w, $blur_h, $src_w, $src_h );
        if ( function_exists( 'imagefilter' ) ) {
            for ( $i = 0; $i < 10; $i++ ) {
                @imagefilter( $blur_buf, IMG_FILTER_GAUSSIAN_BLUR );
            }
            @imagefilter( $blur_buf, IMG_FILTER_SMOOTH, 6 );
            @imagefilter( $blur_buf, IMG_FILTER_BRIGHTNESS, -18 );
        }
        imagecopyresampled( $canvas, $blur_buf, 0, 0, 0, 0, $canvas_w, $canvas_h, $blur_w, $blur_h );
        imagedestroy( $blur_buf );
    } else {
        $bg_col = imagecolorallocate( $canvas, 6, 78, 59 );
        imagefilledrectangle( $canvas, 0, 0, $canvas_w, $canvas_h, $bg_col );
    }

    // 2. Hitung Safe-Zone Universal (1152 x 588) di tengah kanvas 1200 x 760
    // - Di WhatsApp Story (~1.58:1 = 1200x760): seluruh 1200x760 tampil utuh
    // - Di WhatsApp Chat (1.91:1 = 1200x628): area tengah y=66..694 tampil utuh
    // Karena safe-zone berada di x=24..1176 dan y=86..674, gambar 100% aman di Chat maupun Story!
    $safe_w = 1152;
    $safe_h = 588;

    $scale = min( $safe_w / $src_w, $safe_h / $src_h );
    $dst_w = max( 1, (int) round( $src_w * $scale ) );
    $dst_h = max( 1, (int) round( $src_h * $scale ) );
    $dst_x = (int) round( ( $canvas_w - $dst_w ) / 2 );
    $dst_y = (int) round( ( $canvas_h - $dst_h ) / 2 );

    imagealphablending( $canvas, true );
    imagecopyresampled( $canvas, $src_img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h );

    $saved = @imagejpeg( $canvas, $cache_path, 90 );
    imagedestroy( $canvas );
    imagedestroy( $src_img );

    if ( $saved && file_exists( $cache_path ) ) {
        return array(
            'url'    => $cache_url,
            'width'  => $canvas_w,
            'height' => $canvas_h,
            'type'   => 'image/jpeg',
        );
    }

    return false;
}

/**
 * Output Open Graph and Twitter Card meta tags in wp_head
 */
function mtsn1_opengraph_meta_tags() {
    $og_title       = '';
    $og_desc        = '';
    $og_url         = '';
    $og_type        = 'website';
    $og_image       = '';
    $og_image_w     = '';
    $og_image_h     = '';
    $og_image_type  = '';
    $og_author      = '';
    $og_section     = '';
    $published_time = '';
    $modified_time  = '';

    $site_name    = get_bloginfo( 'name' );
    $site_tagline = get_bloginfo( 'description' );
    if ( empty( $site_tagline ) ) {
        $site_tagline = 'Madrasah Mandiri Berprestasi, Berakhlak Mulia & Digital';
    }

    $default_banner = home_url( '/wp-content/uploads/2026/07/Slide-Website.webp' );
    $fallback_logo   = get_template_directory_uri() . '/assets/img/pwa/icon-512.png';

    if ( is_singular() ) {
        $post_id = get_queried_object_id();
        $post    = get_post( $post_id );

        if ( $post ) {
            $og_type  = is_single() ? 'article' : 'website';
            $og_title = wp_strip_all_tags( get_the_title( $post_id ) );
            $og_url   = get_permalink( $post_id );

            // Deskripsi artikel
            if ( post_password_required( $post_id ) ) {
                $og_desc = 'Konten ini dilindungi dengan kata sandi.';
            } elseif ( has_excerpt( $post_id ) ) {
                $og_desc = get_the_excerpt( $post_id );
            } elseif ( ! empty( $post->post_content ) ) {
                $raw_content = strip_shortcodes( $post->post_content );
                $raw_content = wp_strip_all_tags( $raw_content );
                $og_desc     = wp_trim_words( $raw_content, 28, '...' );
            }
            if ( empty( $og_desc ) ) {
                $og_desc = $og_title . ' - ' . $site_name;
            }

            // 1. Gambar Featured Image (Thumbnail Utama)
            $thumb_id = 0;
            if ( has_post_thumbnail( $post_id ) ) {
                $thumb_id = (int) get_post_thumbnail_id( $post_id );
                $img_full = wp_get_attachment_image_src( $thumb_id, 'full' );
                $img_large = wp_get_attachment_image_src( $thumb_id, 'large' );
                $img_data = ! empty( $img_full ) ? $img_full : $img_large;

                if ( $img_data ) {
                    $og_image   = $img_data[0];
                    $og_image_w = $img_data[1];
                    $og_image_h = $img_data[2];
                }
            }

            // 2. Fallback CPT Blog (Al-Qalam / Opini Guru)
            if ( empty( $og_image ) && get_post_type( $post_id ) === 'blog' ) {
                $author_photo = get_post_meta( $post_id, '_author_photo', true );
                if ( ! empty( $author_photo ) ) {
                    $og_image = $author_photo;
                }
            }

            // 3. Fallback CPT Editorial (Sekapur Sirih Kepala Madrasah)
            if ( empty( $og_image ) && get_post_type( $post_id ) === 'editorial' ) {
                $edit_banner = get_post_meta( $post_id, '_editorial_inside_banner', true );
                $edit_foto   = get_post_meta( $post_id, '_foto_kepala_depan', true );
                if ( ! empty( $edit_banner ) ) {
                    $og_image = $edit_banner;
                } elseif ( ! empty( $edit_foto ) ) {
                    $og_image = $edit_foto;
                }
            }

            // 4. Fallback jika ada gambar di dalam isi konten
            if ( empty( $og_image ) && ! empty( $post->post_content ) ) {
                if ( preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $post->post_content, $matches ) ) {
                    $og_image = $matches[1];
                }
            }

            // 5. Konversi otomatis ke Kanvas Universal WhatsApp Chat & Story (1200x760 Safe-Zone)
            // agar banner 21:9, 16:9, maupun flyer 4:5 tidak terpotong di WhatsApp Story maupun Chat
            if ( ! empty( $og_image ) ) {
                $og_canvas = mtsn1_generate_social_og_canvas( $og_image, $thumb_id ?? 0 );
                if ( $og_canvas && ! empty( $og_canvas['url'] ) ) {
                    $og_image      = $og_canvas['url'];
                    $og_image_w    = $og_canvas['width'];
                    $og_image_h    = $og_canvas['height'];
                    $og_image_type = $og_canvas['type'];
                }
            }

            // Meta tambahan artikel
            $published_time = get_the_date( 'c', $post_id );
            $modified_time  = get_the_modified_date( 'c', $post_id );

            // Penulis
            if ( get_post_type( $post_id ) === 'blog' && function_exists( 'mtsn1_get_blog_author_data' ) ) {
                $b_auth    = mtsn1_get_blog_author_data( $post_id );
                $og_author = $b_auth['name'];
            } else {
                $author_obj = get_userdata( $post->post_author );
                $og_author  = $author_obj ? $author_obj->display_name : '';
            }

            // Kategori / Rubrik
            $categories = get_the_category( $post_id );
            if ( ! empty( $categories ) ) {
                $og_section = $categories[0]->name;
            } elseif ( get_post_type( $post_id ) === 'blog' ) {
                $terms = get_the_terms( $post_id, 'blog-kat' );
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    $og_section = $terms[0]->name;
                }
            }
        }
    } elseif ( is_front_page() || is_home() ) {
        $og_type  = 'website';
        $og_title = $site_name . ' - ' . $site_tagline;
        $og_desc  = 'Website Resmi MTs Negeri 1 Blitar - Madrasah Mandiri Berprestasi, Berakhlak Mulia & Digital. Informasi Pendaftaran SPMB, Prestasi Siswa, GTK, dan Kabar Madrasah.';
        $og_url   = home_url( '/' );
        $og_image = $default_banner;
    } elseif ( is_archive() ) {
        $og_type  = 'website';
        $og_title = wp_strip_all_tags( get_the_archive_title() ) . ' - ' . $site_name;
        $arch_desc = wp_strip_all_tags( get_the_archive_description() );
        $og_desc  = ! empty( $arch_desc ) ? $arch_desc : 'Kumpulan publikasi, berita, dan kegiatan terkini ' . $site_name . '.';
        $og_url   = home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );
        $og_image = $default_banner;
    } elseif ( is_search() ) {
        $og_type  = 'website';
        $og_title = 'Hasil Pencarian: ' . esc_html( get_search_query() ) . ' - ' . $site_name;
        $og_desc  = 'Menampilkan hasil pencarian untuk kata kunci "' . esc_html( get_search_query() ) . '" di ' . $site_name . '.';
        $og_url   = home_url( '/?s=' . urlencode( get_search_query() ) );
        $og_image = $fallback_logo;
    } else {
        $og_type  = 'website';
        $og_title = $site_name . ' - ' . $site_tagline;
        $og_desc  = 'Website Resmi MTs Negeri 1 Blitar - Madrasah Mandiri Berprestasi, Berakhlak Mulia & Digital.';
        $og_url   = home_url( '/' );
        $og_image = $default_banner;
    }

    // Fallback terakhir gambar jika masih kosong
    if ( empty( $og_image ) ) {
        $og_image = $fallback_logo;
    }

    // Pastikan URL gambar absolut
    if ( ! empty( $og_image ) && strpos( $og_image, 'http' ) !== 0 ) {
        $og_image = home_url( $og_image );
    }

    // Tentukan mime type gambar otomatis jika belum ada
    if ( ! empty( $og_image ) && empty( $og_image_type ) ) {
        $clean_img_path = parse_url( $og_image, PHP_URL_PATH );
        $ext = strtolower( pathinfo( $clean_img_path, PATHINFO_EXTENSION ) );
        if ( in_array( $ext, array( 'jpg', 'jpeg' ), true ) ) {
            $og_image_type = 'image/jpeg';
        } elseif ( $ext === 'png' ) {
            $og_image_type = 'image/png';
        } elseif ( $ext === 'webp' ) {
            $og_image_type = 'image/webp';
        }
    }

    // Pembersihan teks dari whitespace berlebih
    $og_title = trim( preg_replace( '/\s+/', ' ', $og_title ) );
    $og_desc  = trim( preg_replace( '/\s+/', ' ', $og_desc ) );

    ?>
    <!-- Open Graph & Social Media Preview Meta (WhatsApp, Telegram, Facebook, Twitter/X) -->
    <meta name="description" content="<?php echo esc_attr( $og_desc ); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>">
    <meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>">
    <meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $og_desc ); ?>">
    <meta property="og:url" content="<?php echo esc_url( $og_url ); ?>">
    <meta property="og:locale" content="id_ID">
    <?php if ( ! empty( $og_image ) ) : ?>
    <meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
    <meta property="og:image:secure_url" content="<?php echo esc_url( $og_image ); ?>">
    <?php if ( ! empty( $og_image_w ) && ! empty( $og_image_h ) ) : ?>
    <meta property="og:image:width" content="<?php echo esc_attr( $og_image_w ); ?>">
    <meta property="og:image:height" content="<?php echo esc_attr( $og_image_h ); ?>">
    <?php endif; ?>
    <?php if ( ! empty( $og_image_type ) ) : ?>
    <meta property="og:image:type" content="<?php echo esc_attr( $og_image_type ); ?>">
    <?php endif; ?>
    <meta property="og:image:alt" content="<?php echo esc_attr( $og_title ); ?>">
    <link rel="image_src" href="<?php echo esc_url( $og_image ); ?>">
    <?php endif; ?>
    <?php if ( $og_type === 'article' ) : ?>
    <?php if ( ! empty( $published_time ) ) : ?>
    <meta property="article:published_time" content="<?php echo esc_attr( $published_time ); ?>">
    <?php endif; ?>
    <?php if ( ! empty( $modified_time ) ) : ?>
    <meta property="article:modified_time" content="<?php echo esc_attr( $modified_time ); ?>">
    <?php endif; ?>
    <?php if ( ! empty( $og_author ) ) : ?>
    <meta property="article:author" content="<?php echo esc_attr( $og_author ); ?>">
    <?php endif; ?>
    <?php if ( ! empty( $og_section ) ) : ?>
    <meta property="article:section" content="<?php echo esc_attr( $og_section ); ?>">
    <?php endif; ?>
    <?php endif; ?>

    <!-- Twitter Card Meta -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr( $og_title ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $og_desc ); ?>">
    <?php if ( ! empty( $og_image ) ) : ?>
    <meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">
    <meta name="twitter:image:alt" content="<?php echo esc_attr( $og_title ); ?>">
    <?php endif; ?>
    <!-- End Open Graph & Social Media Preview Meta -->
    <?php
}
// Catatan: mtsn1_opengraph_meta_tags() dipanggil langsung di header.php
// paling atas di dalam <head> agar dieksekusi sebelum tag link icon dan script lain.
