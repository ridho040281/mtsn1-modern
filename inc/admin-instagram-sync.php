<?php
/**
 * Modul Otomatisasi Sinkronisasi Prestasi Instagram ke Website MTsN 1 Blitar
 * 
 * Mendukung:
 * 1. REST API Webhook (/wp-json/mtsn1/v1/sync-prestasi) untuk Make.com, Zapier, n8n, atau bot otomatis.
 * 2. Meta Instagram Graph API langsung dengan Token & Account ID.
 * 3. Fitur "Tarik Postingan Sekarang" (One-Click Manual Sync) di WP-Admin.
 * 4. Otomatis konversi gambar flyer IG ke format WebP ringan (< 100 KB).
 * 5. Smart Caption Parser untuk mendeteksi Peraih Prestasi & Tingkat Kejuaraan.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Pengaturan Default Sinkronisasi Instagram
 */
function mtsn1_get_default_ig_sync_settings() {
    return [
        'access_token'     => '',
        'account_id'       => '',
        'secret_key'       => 'mtsn1_' . substr( md5( wp_salt() . 'ig_sync' ), 0, 16 ),
        'keyword_filter'   => '#prestasi, #juara, #lomba, selamat dan sukses, peraih medali, kejuaraan',
        'default_status'   => 'publish', // 'publish' atau 'draft'
        'auto_cron'        => 'hourly',  // 'hourly', 'twicedaily', 'daily', 'disabled'
        'auto_webp'        => '1',
        'last_sync_time'   => '',
        'sync_log'         => [],
    ];
}

/**
 * Ambil Pengaturan Tersimpan
 */
function mtsn1_get_ig_sync_settings() {
    $saved = get_option( 'mtsn1_ig_sync_settings', [] );
    $defaults = mtsn1_get_default_ig_sync_settings();
    if ( empty( $saved ) || ! is_array( $saved ) ) {
        return $defaults;
    }
    return wp_parse_args( $saved, $defaults );
}

/**
 * Daftarkan Submenu di Sidebar WP-Admin (Di bawah menu Prestasi)
 */
add_action( 'admin_menu', 'mtsn1_register_ig_sync_menu' );
function mtsn1_register_ig_sync_menu() {
    add_submenu_page(
        'edit.php?post_type=prestasi',
        'Otomatisasi Instagram',
        '⚡ Sinkronisasi IG',
        'manage_options',
        'mtsn1-ig-sync',
        'mtsn1_render_ig_sync_page'
    );
}

/**
 * Smart Parser Caption Instagram
 * Mengekstrak Judul, Peraih Prestasi, dan Tingkat Kejuaraan dari teks postingan IG
 */
function mtsn1_parse_ig_caption( $caption ) {
    $clean_caption = trim( $caption );
    $lines = array_values( array_filter( array_map( 'trim', explode( "\n", $clean_caption ) ) ) );

    $title = '';
    $peraih = '';
    $tingkat = 'Kabupaten/Kota'; // Default

    // 1. Ekstrak Tingkat
    $cap_upper = strtoupper( $clean_caption );
    if ( strpos( $cap_upper, 'INTERNASIONAL' ) !== false || strpos( $cap_upper, 'INTERNATIONAL' ) !== false || strpos( $cap_upper, 'WORLD' ) !== false ) {
        $tingkat = 'Internasional';
    } elseif ( strpos( $cap_upper, 'NASIONAL' ) !== false || strpos( $cap_upper, 'INDONESIA' ) !== false ) {
        $tingkat = 'Nasional';
    } elseif ( strpos( $cap_upper, 'PROVINSI' ) !== false || strpos( $cap_upper, 'JAWA TIMUR' ) !== false || strpos( $cap_upper, 'SE-JATIM' ) !== false || strpos( $cap_upper, 'KARESIDENAN' ) !== false ) {
        $tingkat = 'Provinsi';
    } elseif ( strpos( $cap_upper, 'KECAMATAN' ) !== false || strpos( $cap_upper, 'WONODADI' ) !== false ) {
        $tingkat = 'Kecamatan';
    } else {
        $tingkat = 'Kabupaten/Kota';
    }

    // 2. Ekstrak Nama Peraih
    foreach ( $lines as $line ) {
        if ( preg_match( '/(?:nama|peraih|atlit|peserta|ananda|oleh|siswa\/i)\s*[:\-]\s*(.+)/i', $line, $matches ) ) {
            $peraih = trim( $matches[1] );
            break;
        }
        if ( preg_match( '/(?:diraih oleh|atas nama|ananda)\s+([A-Za-z\s\.\'\`]+)/i', $line, $matches ) ) {
            $peraih = trim( $matches[1] );
            break;
        }
    }

    // 3. Tentukan Judul Postingan
    // Cari baris yang mengandung 'Juara' atau 'Prestasi' atau gunakan baris pertama
    foreach ( $lines as $line ) {
        // Hilangkan hashtag dari judul
        $cleaned_line = preg_replace( '/#\w+/u', '', $line );
        $cleaned_line = trim( $cleaned_line );
        if ( stripos( $cleaned_line, 'Juara' ) !== false || stripos( $cleaned_line, 'Medal' ) !== false || stripos( $cleaned_line, 'Selamat' ) !== false ) {
            $title = $cleaned_line;
            break;
        }
    }

    if ( empty( $title ) && ! empty( $lines[0] ) ) {
        $title = preg_replace( '/#\w+/u', '', $lines[0] );
    }

    $title = trim( strip_tags( $title ) );
    if ( mb_strlen( $title ) > 140 ) {
        $title = mb_substr( $title, 0, 137 ) . '...';
    }
    if ( empty( $title ) ) {
        $title = 'Prestasi Siswa MTsN 1 Blitar';
    }

    return [
        'title'   => $title,
        'peraih'  => $peraih,
        'tingkat' => $tingkat,
        'content' => nl2br( esc_html( $clean_caption ) ),
    ];
}

/**
 * Unduh Gambar dan Otomatis Konversi ke WebP Ringan (< 100 KB)
 */
function mtsn1_download_and_convert_to_webp( $image_url, $title_slug ) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Download gambar remote ke file temporary
    $tmp_file = download_url( $image_url, 30 );
    if ( is_wp_error( $tmp_file ) ) {
        return $tmp_file;
    }

    $upload_dir = wp_upload_dir();
    $filename_base = 'prestasi-ig-' . sanitize_title( $title_slug ) . '-' . time();
    $final_path = $upload_dir['path'] . '/' . $filename_base . '.webp';
    $final_url  = $upload_dir['url'] . '/' . $filename_base . '.webp';

    $img_info = @getimagesize( $tmp_file );
    $converted = false;

    if ( $img_info && function_exists( 'imagewebp' ) ) {
        $mime = $img_info['mime'];
        $src_img = null;

        if ( $mime === 'image/jpeg' ) {
            $src_img = @imagecreatefromjpeg( $tmp_file );
        } elseif ( $mime === 'image/png' ) {
            $src_img = @imagecreatefrompng( $tmp_file );
        } elseif ( $mime === 'image/webp' ) {
            $src_img = @imagecreatefromwebp( $tmp_file );
        }

        if ( $src_img ) {
            // Resize jika dimensi sangat besar (> 1600px) agar tetap proporsional & hemat penyimpanan
            $width = imagesx( $src_img );
            $height = imagesy( $src_img );
            $max_w = 1200;

            if ( $width > $max_w ) {
                $new_w = $max_w;
                $new_h = intval( $height * ( $max_w / $width ) );
                $resized_img = imagecreatetruecolor( $new_w, $new_h );
                imagecopyresampled( $resized_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height );
                imagedestroy( $src_img );
                $src_img = $resized_img;
            }

            // Simpan sebagai WebP berkualitas 85 (ukuran file ~50KB - 90KB)
            $converted = imagewebp( $src_img, $final_path, 85 );
            imagedestroy( $src_img );
        }
    }

    // Jika konversi WebP gagal, simpan file original sebagai fallback
    if ( ! $converted ) {
        $ext = pathinfo( $tmp_file, PATHINFO_EXTENSION );
        if ( ! $ext ) $ext = 'jpg';
        $final_path = $upload_dir['path'] . '/' . $filename_base . '.' . $ext;
        $final_url  = $upload_dir['url'] . '/' . $filename_base . '.' . $ext;
        @copy( $tmp_file, $final_path );
    }

    @unlink( $tmp_file );

    // Masukkan ke WordPress Media Library
    $attachment = [
        'guid'           => $final_url,
        'post_mime_type' => $converted ? 'image/webp' : ( $img_info['mime'] ?? 'image/jpeg' ),
        'post_title'     => sanitize_text_field( $title_slug ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $attach_id = wp_insert_attachment( $attachment, $final_path );
    if ( ! is_wp_error( $attach_id ) ) {
        $attach_data = wp_generate_attachment_metadata( $attach_id, $final_path );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        return $attach_id;
    }

    return new WP_Error( 'attach_failed', 'Gagal mendaftarkan lampiran gambar ke media library' );
}

/**
 * Eksekusi Pembuatan Post Prestasi dari Data Instagram
 */
function mtsn1_create_prestasi_from_ig_data( $item ) {
    global $wpdb;

    $media_id   = sanitize_text_field( $item['media_id'] ?? '' );
    $permalink  = esc_url_raw( $item['permalink'] ?? '' );
    $caption    = sanitize_textarea_field( $item['caption'] ?? '' );
    $image_url  = esc_url_raw( $item['image_url'] ?? '' );
    $post_date  = ! empty( $item['timestamp'] ) ? gmdate( 'Y-m-d H:i:s', strtotime( $item['timestamp'] ) ) : current_time( 'mysql' );

    if ( empty( $image_url ) ) {
        return new WP_Error( 'no_image', 'Image URL tidak boleh kosong.' );
    }

    // Cek duplikasi: apakah media_id atau permalink sudah pernah diimport
    if ( ! empty( $media_id ) ) {
        $existing = $wpdb->get_var( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_instagram_media_id' AND meta_value = %s LIMIT 1",
            $media_id
        ) );
        if ( $existing ) {
            return [ 'status' => 'skipped', 'message' => 'Postingan ini sudah pernah diimpor sebelumnya.', 'post_id' => $existing ];
        }
    }

    if ( ! empty( $permalink ) ) {
        $existing_url = $wpdb->get_var( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_instagram_url' AND meta_value = %s LIMIT 1",
            $permalink
        ) );
        if ( $existing_url ) {
            return [ 'status' => 'skipped', 'message' => 'URL Instagram ini sudah ada di database.', 'post_id' => $existing_url ];
        }
    }

    // Parsing teks caption
    $parsed = mtsn1_parse_ig_caption( $caption );
    $settings = mtsn1_get_ig_sync_settings();
    $status = ! empty( $settings['default_status'] ) ? $settings['default_status'] : 'publish';

    // Buat Postingan Baru di Post Type 'prestasi'
    $post_data = [
        'post_title'    => $parsed['title'],
        'post_content'  => $parsed['content'],
        'post_status'   => $status,
        'post_type'     => 'prestasi',
        'post_date'     => $post_date,
        'post_date_gmt' => get_gmt_from_date( $post_date ),
    ];

    $post_id = wp_insert_post( $post_data, true );
    if ( is_wp_error( $post_id ) ) {
        return $post_id;
    }

    // Simpan metadata
    if ( ! empty( $parsed['peraih'] ) ) {
        update_post_meta( $post_id, '_peraih', $parsed['peraih'] );
        update_post_meta( $post_id, '_nama_siswa', $parsed['peraih'] );
    }
    if ( ! empty( $parsed['tingkat'] ) ) {
        update_post_meta( $post_id, '_tingkat', $parsed['tingkat'] );
    }
    if ( ! empty( $media_id ) ) {
        update_post_meta( $post_id, '_instagram_media_id', $media_id );
    }
    if ( ! empty( $permalink ) ) {
        update_post_meta( $post_id, '_instagram_url', $permalink );
    }

    // Unduh gambar flyer & pasang sebagai Featured Image
    $attach_id = mtsn1_download_and_convert_to_webp( $image_url, $parsed['title'] );
    if ( ! is_wp_error( $attach_id ) ) {
        set_post_thumbnail( $post_id, $attach_id );
    }

    // Simpan ke log
    mtsn1_add_sync_log( [
        'post_id'   => $post_id,
        'title'     => $parsed['title'],
        'peraih'    => $parsed['peraih'],
        'tingkat'   => $parsed['tingkat'],
        'permalink' => $permalink,
        'date'      => current_time( 'mysql' ),
    ] );

    return [
        'status'  => 'success',
        'post_id' => $post_id,
        'title'   => $parsed['title'],
        'peraih'  => $parsed['peraih'],
        'tingkat' => $parsed['tingkat'],
    ];
}

/**
 * Catat Riwayat Sinkronisasi
 */
function mtsn1_add_sync_log( $entry ) {
    $settings = mtsn1_get_ig_sync_settings();
    $log = isset( $settings['sync_log'] ) && is_array( $settings['sync_log'] ) ? $settings['sync_log'] : [];
    array_unshift( $log, $entry );
    $log = array_slice( $log, 0, 20 ); // Simpan maksimal 20 log terakhir
    $settings['sync_log'] = $log;
    $settings['last_sync_time'] = current_time( 'mysql' );
    update_option( 'mtsn1_ig_sync_settings', $settings );
}

/**
 * Daftarkan REST API Webhook (/wp-json/mtsn1/v1/sync-prestasi)
 */
add_action( 'rest_api_init', 'mtsn1_register_ig_sync_api' );
function mtsn1_register_ig_sync_api() {
    register_rest_route( 'mtsn1/v1', '/sync-prestasi', [
        'methods'             => 'POST',
        'callback'            => 'mtsn1_handle_ig_sync_webhook',
        'permission_callback' => 'mtsn1_verify_ig_sync_permission',
    ] );
}

/**
 * Validasi Izin Akses Webhook (Menggunakan Secret API Key)
 */
function mtsn1_verify_ig_sync_permission( $request ) {
    $settings = mtsn1_get_ig_sync_settings();
    $expected_key = $settings['secret_key'] ?? '';

    $provided_key = $request->get_header( 'X-MTSN1-KEY' );
    if ( empty( $provided_key ) ) {
        $provided_key = $request->get_param( 'secret_key' );
    }

    if ( ! empty( $expected_key ) && hash_equals( $expected_key, (string) $provided_key ) ) {
        return true;
    }

    return new WP_Error( 'rest_forbidden', 'Akses ditolak: Secret API Key tidak valid.', [ 'status' => 403 ] );
}

/**
 * Handler Webhook POST
 */
function mtsn1_handle_ig_sync_webhook( $request ) {
    $params = $request->get_json_params();
    if ( empty( $params ) ) {
        $params = $request->get_params();
    }

    $image_url = $params['image_url'] ?? ( $params['media_url'] ?? '' );
    $caption   = $params['caption'] ?? '';
    $permalink = $params['permalink'] ?? '';
    $media_id  = $params['media_id'] ?? ( $params['id'] ?? '' );
    $timestamp = $params['timestamp'] ?? '';

    if ( empty( $image_url ) ) {
        return new WP_REST_Response( [ 'success' => false, 'message' => 'Parameter image_url/media_url wajib diisi.' ], 400 );
    }

    // Filter Kata Kunci / Hashtag
    $settings = mtsn1_get_ig_sync_settings();
    $filters = array_filter( array_map( 'trim', explode( ',', strtolower( $settings['keyword_filter'] ) ) ) );
    
    $is_prestasi = false;
    if ( empty( $filters ) ) {
        $is_prestasi = true;
    } else {
        $cap_lower = strtolower( $caption );
        foreach ( $filters as $f ) {
            if ( ! empty( $f ) && strpos( $cap_lower, $f ) !== false ) {
                $is_prestasi = true;
                break;
            }
        }
    }

    if ( ! $is_prestasi ) {
        return new WP_REST_Response( [
            'success' => true,
            'status'  => 'ignored',
            'message' => 'Postingan diabaikan karena caption tidak mengandung kata kunci/hashtag filter prestasi.',
        ], 200 );
    }

    $res = mtsn1_create_prestasi_from_ig_data( [
        'media_id'  => $media_id,
        'permalink' => $permalink,
        'caption'   => $caption,
        'image_url' => $image_url,
        'timestamp' => $timestamp,
    ] );

    if ( is_wp_error( $res ) ) {
        return new WP_REST_Response( [ 'success' => false, 'error' => $res->get_error_message() ], 500 );
    }

    return new WP_REST_Response( [ 'success' => true, 'result' => $res ], 200 );
}

/**
 * Tarik Postingan Langsung via Meta Graph API
 */
function mtsn1_fetch_from_meta_graph_api() {
    $settings = mtsn1_get_ig_sync_settings();
    $token = trim( $settings['access_token'] ?? '' );
    $account_id = trim( $settings['account_id'] ?? '' );

    if ( empty( $token ) || empty( $account_id ) ) {
        return new WP_Error( 'missing_credentials', 'Access Token Meta atau Akun ID Instagram belum diisi di Pengaturan.' );
    }

    $url = sprintf(
        'https://graph.facebook.com/v21.0/%s/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url,timestamp&access_token=%s&limit=15',
        rawurlencode( $account_id ),
        rawurlencode( $token )
    );

    $response = wp_remote_get( $url, [ 'timeout' => 25 ] );
    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( ! empty( $data['error'] ) ) {
        return new WP_Error( 'meta_api_error', $data['error']['message'] ?? 'Meta API mengembalikan pesan kesalahan.' );
    }

    $items = $data['data'] ?? [];
    if ( empty( $items ) ) {
        return [ 'synced_count' => 0, 'message' => 'Tidak ditemukan postingan media di akun Instagram ini.' ];
    }

    $filters = array_filter( array_map( 'trim', explode( ',', strtolower( $settings['keyword_filter'] ) ) ) );
    $synced = 0;

    foreach ( $items as $item ) {
        $type = $item['media_type'] ?? '';
        if ( $type !== 'IMAGE' && $type !== 'CAROUSEL_ALBUM' ) {
            continue;
        }

        $caption = $item['caption'] ?? '';
        $is_prestasi = false;
        if ( empty( $filters ) ) {
            $is_prestasi = true;
        } else {
            $cap_lower = strtolower( $caption );
            foreach ( $filters as $f ) {
                if ( ! empty( $f ) && strpos( $cap_lower, $f ) !== false ) {
                    $is_prestasi = true;
                    break;
                }
            }
        }

        if ( ! $is_prestasi ) {
            continue;
        }

        $img_url = $item['media_url'] ?? ( $item['thumbnail_url'] ?? '' );
        if ( empty( $img_url ) ) {
            continue;
        }

        $res = mtsn1_create_prestasi_from_ig_data( [
            'media_id'  => $item['id'] ?? '',
            'permalink' => $item['permalink'] ?? '',
            'caption'   => $caption,
            'image_url' => $img_url,
            'timestamp' => $item['timestamp'] ?? '',
        ] );

        if ( is_array( $res ) && ( $res['status'] ?? '' ) === 'success' ) {
            $synced++;
        }
    }

    return [
        'synced_count' => $synced,
        'message'      => "Berhasil memeriksa " . count( $items ) . " postingan IG dan mengimpor $synced prestasi baru.",
    ];
}

/**
 * Handle Form Submission di Admin
 */
add_action( 'admin_init', 'mtsn1_handle_ig_sync_admin_actions' );
function mtsn1_handle_ig_sync_admin_actions() {
    if ( ! isset( $_POST['mtsn1_ig_nonce'] ) || ! wp_verify_nonce( $_POST['mtsn1_ig_nonce'], 'mtsn1_save_ig_sync' ) ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $settings = mtsn1_get_ig_sync_settings();

    // 1. Aksi Simpan Pengaturan
    if ( isset( $_POST['save_settings'] ) ) {
        $settings['access_token']   = sanitize_text_field( trim( $_POST['access_token'] ?? '' ) );
        $settings['account_id']     = sanitize_text_field( trim( $_POST['account_id'] ?? '' ) );
        $settings['secret_key']     = sanitize_text_field( trim( $_POST['secret_key'] ?? '' ) );
        $settings['keyword_filter'] = sanitize_text_field( trim( $_POST['keyword_filter'] ?? '' ) );
        $settings['default_status'] = in_array( $_POST['default_status'] ?? '', [ 'publish', 'draft' ] ) ? $_POST['default_status'] : 'publish';
        $settings['auto_cron']      = sanitize_text_field( $_POST['auto_cron'] ?? 'hourly' );

        update_option( 'mtsn1_ig_sync_settings', $settings );
        wp_safe_redirect( add_query_arg( [ 'page' => 'mtsn1-ig-sync', 'saved' => '1' ], admin_url( 'edit.php?post_type=prestasi' ) ) );
        exit;
    }

    // 2. Aksi Tarik Postingan Sekarang (Manual Fetch)
    if ( isset( $_POST['fetch_now'] ) ) {
        $result = mtsn1_fetch_from_meta_graph_api();
        if ( is_wp_error( $result ) ) {
            wp_safe_redirect( add_query_arg( [ 'page' => 'mtsn1-ig-sync', 'error' => urlencode( $result->get_error_message() ) ], admin_url( 'edit.php?post_type=prestasi' ) ) );
            exit;
        } else {
            wp_safe_redirect( add_query_arg( [ 'page' => 'mtsn1-ig-sync', 'fetched' => $result['synced_count'], 'msg' => urlencode( $result['message'] ) ], admin_url( 'edit.php?post_type=prestasi' ) ) );
            exit;
        }
    }
}

/**
 * Tampilan Halaman Admin: Sinkronisasi Instagram di WP-Admin
 */
function mtsn1_render_ig_sync_page() {
    $settings = mtsn1_get_ig_sync_settings();
    $webhook_url = rest_url( 'mtsn1/v1/sync-prestasi' );
    ?>
    <div class="wrap" style="max-width: 1050px; margin-top: 20px;">
        <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; display: flex; items-center; gap: 10px;">
            <span class="dashicons dashicons-update" style="font-size: 28px; width: 28px; height: 28px; color: #059669;"></span>
            Otomatisasi Sinkronisasi Prestasi Instagram &rarr; Website
        </h1>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 24px;">
            Sistem otomatis untuk mendeteksi flyer prestasi baru di akun Instagram resmi <strong>@mtsn1blitar69</strong> dan menerbitkannya seketika ke Galeri Prestasi website.
        </p>

        <!-- NOTIFIKASI SUKSES / ERROR -->
        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible" style="padding: 12px 16px; font-size: 14px; font-weight: 600; border-left-color: #059669; background: #ecfdf5; color: #065f46; border-radius: 8px; margin-bottom: 24px;">
                <p>✓ Pengaturan Sinkronisasi Instagram berhasil disimpan!</p>
            </div>
        <?php endif; ?>

        <?php if ( isset( $_GET['fetched'] ) ) : ?>
            <div class="notice notice-success is-dismissible" style="padding: 12px 16px; font-size: 14px; font-weight: 600; border-left-color: #059669; background: #ecfdf5; color: #065f46; border-radius: 8px; margin-bottom: 24px;">
                <p>🎉 <?php echo esc_html( urldecode( $_GET['msg'] ?? 'Sinkronisasi berhasil!' ) ); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( isset( $_GET['error'] ) ) : ?>
            <div class="notice notice-error is-dismissible" style="padding: 12px 16px; font-size: 14px; font-weight: 600; border-radius: 8px; margin-bottom: 24px;">
                <p>⚠️ Gagal: <?php echo esc_html( urldecode( $_GET['error'] ) ); ?></p>
            </div>
        <?php endif; ?>

        <!-- GRID 2 KOLOM PENGATURAN -->
        <div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 24px; align-items: start;">

            <!-- KOLOM KIRI: FORM PENGATURAN & TARIK MANUAL -->
            <div>
                <!-- KARTU 1: TARIK POSTINGAN SEKARANG (ONE CLICK) -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 22px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px;">
                    <h2 style="font-size: 16px; font-weight: 800; color: #1e293b; margin: 0 0 10px 0; display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-controls-play" style="color: #059669;"></span> Tarik Postingan Instagram Sekarang
                    </h2>
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
                        Klik tombol di bawah untuk langsung memeriksa dan mengimpor flyer prestasi terbaru dari Instagram menggunakan Meta Graph API.
                    </p>

                    <form method="post" action="">
                        <?php wp_nonce_field( 'mtsn1_save_ig_sync', 'mtsn1_ig_nonce' ); ?>
                        <button type="submit" name="fetch_now" value="1" class="button button-primary" style="background: #059669; border-color: #047857; font-weight: 700; font-size: 13px; padding: 6px 18px; border-radius: 8px; height: auto; box-shadow: 0 2px 4px rgba(5,150,105,0.3); cursor: pointer;">
                            🚀 Tarik Flyer Terbaru dari IG Sekarang
                        </button>
                    </form>
                </div>

                <!-- KARTU 2: KREDENSIAL & FILTER -->
                <form method="post" action="" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <?php wp_nonce_field( 'mtsn1_save_ig_sync', 'mtsn1_ig_nonce' ); ?>
                    
                    <h2 style="font-size: 16px; font-weight: 800; color: #1e293b; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-admin-settings" style="color: #059669;"></span> Pengaturan Akun & Filter
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Instagram Business Account ID</label>
                            <input type="text" name="account_id" value="<?php echo esc_attr( $settings['account_id'] ); ?>" class="widefat" placeholder="Contoh: 17841400000000000" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px;">
                            <span style="font-size: 11px; color: #64748b;">ID Akun Instagram Bisnis dari Meta Developer App Anda.</span>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Meta Access Token (Long-Lived Token)</label>
                            <input type="password" name="access_token" value="<?php echo esc_attr( $settings['access_token'] ); ?>" class="widefat" placeholder="EAABw..." style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px;">
                            <span style="font-size: 11px; color: #64748b;">Token akses resmi yang memiliki izin <code>instagram_basic</code>.</span>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Filter Kata Kunci / Hashtag Prestasi</label>
                            <input type="text" name="keyword_filter" value="<?php echo esc_attr( $settings['keyword_filter'] ); ?>" class="widefat" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px;">
                            <span style="font-size: 11px; color: #64748b;">Hanya postingan IG yang mengandung salah satu kata kunci/hashtag ini yang akan diimpor (pisahkan dengan koma).</span>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Status Terbit Postingan</label>
                                <select name="default_status" class="widefat" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 6px 10px;">
                                    <option value="publish" <?php selected( $settings['default_status'], 'publish' ); ?>>Langsung Terbit (Publish)</option>
                                    <option value="draft" <?php selected( $settings['default_status'], 'draft' ); ?>>Simpan Sebagai Draft (Tinjau Dulu)</option>
                                </select>
                            </div>

                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Secret Webhook API Key</label>
                                <input type="text" name="secret_key" value="<?php echo esc_attr( $settings['secret_key'] ); ?>" class="widefat" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 6px 10px; font-family: monospace;">
                            </div>
                        </div>

                        <div style="padding-top: 10px;">
                            <button type="submit" name="save_settings" value="1" class="button button-primary" style="background: #0f172a; border-color: #0f172a; font-weight: 700; font-size: 13px; padding: 7px 20px; border-radius: 8px; cursor: pointer;">
                                💾 Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- KOLOM KANAN: DOKUMENTASI WEBHOOK REAL-TIME (MAKE.COM / ZAPIER) & RIWAYAT -->
            <div>
                <!-- KARTU WEBHOOK OTOMATIS REAL-TIME -->
                <div style="background: #f8fafc; border: 1.5px dashed #059669; border-radius: 16px; padding: 22px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <span class="dashicons dashicons-rest-api" style="font-size: 22px; color: #059669;"></span>
                        <h3 style="font-size: 15px; font-weight: 800; color: #065f46; margin: 0;">Webhook REST API (Real-Time)</h3>
                    </div>
                    <p style="font-size: 12px; color: #475569; line-height: 1.6; margin-bottom: 14px;">
                        Anda dapat menghubungkan akun Instagram melalui <strong>Make.com</strong>, <strong>Zapier</strong>, atau <strong>Meta Webhook</strong> agar setiap kali upload di IG, flyer langsung masuk ke web secara <em>real-time</em>!
                    </p>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 4px;">Endpoint URL:</label>
                        <code style="display: block; background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 10px; border-radius: 8px; font-size: 11px; color: #059669; word-break: break-all;">
                            <?php echo esc_url( $webhook_url ); ?>
                        </code>
                    </div>

                    <div style="margin-bottom: 12px;">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; margin-bottom: 4px;">Header Autentikasi:</label>
                        <code style="display: block; background: #ffffff; border: 1px solid #cbd5e1; padding: 6px 10px; border-radius: 8px; font-size: 11px; color: #334155;">
                            X-MTSN1-KEY: <?php echo esc_html( $settings['secret_key'] ); ?>
                        </code>
                    </div>

                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 10px 12px; font-size: 11px; color: #065f46;">
                        <strong>Format JSON Body (POST):</strong><br>
                        <code>{ "image_url": "URL_GAMBAR", "caption": "TEKS_CAPTION", "permalink": "URL_IG" }</code>
                    </div>
                </div>

                <!-- KARTU LOG RIWAYAT TERAKHIR -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 12px 0; display: flex; align-items: center; justify-between;">
                        <span>Riwayat Flyer Terakhir Diimpor</span>
                        <?php if ( ! empty( $settings['last_sync_time'] ) ) : ?>
                            <span style="font-size: 11px; font-weight: normal; color: #94a3b8;">Terakhir: <?php echo esc_html( $settings['last_sync_time'] ); ?></span>
                        <?php endif; ?>
                    </h3>

                    <?php
                    $logs = $settings['sync_log'] ?? [];
                    if ( empty( $logs ) ) :
                    ?>
                        <p style="font-size: 12px; color: #94a3b8; font-style: italic; margin: 0;">Belum ada riwayat impor otomatis.</p>
                    <?php else : ?>
                        <div style="display: flex; flex-direction: column; gap: 8px; max-height: 320px; overflow-y: auto;">
                            <?php foreach ( $logs as $l ) : ?>
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 8px 12px; font-size: 11px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                        <strong style="color: #0f172a;"><?php echo esc_html( $l['tingkat'] ?? 'Prestasi' ); ?></strong>
                                        <span style="color: #94a3b8; font-size: 10px;"><?php echo esc_html( substr( $l['date'] ?? '', 0, 16 ) ); ?></span>
                                    </div>
                                    <p style="margin: 0; color: #334155; line-height: 1.3;" class="line-clamp-1"><?php echo esc_html( $l['title'] ?? '' ); ?></p>
                                    <?php if ( ! empty( $l['post_id'] ) ) : ?>
                                        <div style="margin-top: 4px;">
                                            <a href="<?php echo esc_url( get_edit_post_link( $l['post_id'] ) ); ?>" style="color: #059669; font-weight: bold; text-decoration: none;">Edit di Web &rarr;</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <?php
}
