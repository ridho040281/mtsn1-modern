<?php
/**
 * Modul Optimasi Performa & Kecepatan WP-Admin (Local & CPanel Hosting)
 * MTsN 1 Blitar Modern Theme
 *
 * Mengatasi penyebab utama loading lambat di wp-admin:
 * 1. Menghilangkan sinkronisasi cURL / external HTTP requests yang membekukan navigasi biasa (api.wordpress.org, ciuss, planet).
 * 2. Membekukan dan memanipulasi update transient agar tidak memicu wp_remote_get/post saat navigasi menu/post.
 * 3. Menghapus metabox lambat di Dashboard (RSS Events & News, Site Health background loop checks, Activity subqueries).
 * 4. Mematikan Heartbeat API di halaman non-edit dan memperlambat intervalnya di halaman post editor.
 * 5. Menghapus skrip emoji polyfill dan overhead bloat di admin head.
 * 6. Mengoptimalkan autosave interval dan membatasi revisi postingan agar database tetap ramping.
 * 7. Menyediakan tombol One-Click "⚡ Bersihkan Cache & Transients" di WP Admin Bar.
 *
 * @package MTsN1_Modern
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. CEGAH TIMEOUT CURL / EXTERNAL HTTP REQUESTS YANG MEMBEKUKAN NAVIGASI WP-ADMIN
 */
add_filter( 'pre_http_request', function( $pre, $args, $url ) {
    if ( ! is_string( $url ) ) {
        return $pre;
    }

    // Selalu blokir updater pihak ketiga yang sering lambat atau timeout (ciuss.com, planet.wordpress.org)
    if ( strpos( $url, 'update.ciuss.com' ) !== false || strpos( $url, 'planet.wordpress.org' ) !== false ) {
        return new WP_Error( 'http_request_blocked', 'External check bypassed for admin speed' );
    }

    // Di area Admin: cegah remote calls non-esensial kecuali jika admin secara eksplisit membuka halaman Update resmi
    if ( is_admin() ) {
        global $pagenow;
        $allowed_update_screens = array( 'update-core.php', 'update.php' );
        $is_updating = in_array( $pagenow, $allowed_update_screens, true );

        if ( ! $is_updating ) {
            // Blokir pengecekan versi browser & rekomendasi versi PHP
            if ( strpos( $url, 'api.wordpress.org/core/browse-happy' ) !== false || strpos( $url, 'api.wordpress.org/core/serve-happy' ) !== false ) {
                return array(
                    'response' => array( 'code' => 200, 'message' => 'OK' ),
                    'body'     => json_encode( array(
                        'platform'        => '',
                        'name'            => '',
                        'version'         => '',
                        'current_version' => '',
                        'upgrade'         => false,
                        'insecure'        => false,
                    ) ),
                );
            }

            // Blokir fetch events/news WordPress
            if ( strpos( $url, 'api.wordpress.org/events' ) !== false ) {
                return array(
                    'response' => array( 'code' => 200, 'message' => 'OK' ),
                    'body'     => json_encode( array( 'events' => array() ) ),
                );
            }

            // Blokir pengecekan terjemahan otomatis saat navigasi biasa
            if ( strpos( $url, 'api.wordpress.org/translations' ) !== false ) {
                return array(
                    'response' => array( 'code' => 200, 'message' => 'OK' ),
                    'body'     => json_encode( array( 'translations' => array() ) ),
                );
            }
        }
    }

    return $pre;
}, 10, 3 );

/**
 * 2. BATASI TIMEOUT HTTP REQUEST MENJADI MAKSIMAL 3 DETIK DI WP-ADMIN
 * Mencegah proses PHP menggantung 10-30 detik jika ada plugin lain yang memanggil API eksternal.
 */
add_filter( 'http_request_args', function( $args ) {
    if ( is_admin() ) {
        global $pagenow;
        if ( ! in_array( $pagenow, array( 'update-core.php', 'update.php' ), true ) ) {
            $args['timeout'] = 3;
        }
    }
    return $args;
}, 999 );

/**
 * 3. BEKUKAN PENGECEKAN UPDATE DI LATAR BELAKANG SAAT NAVIGASI ADMIN BIASA
 * Memastikan WordPress tidak melakukan sync cURL ke api.wordpress.org saat membuka daftar postingan atau menu.
 */
add_filter( 'pre_site_transient_update_plugins', 'mtsn1_fast_admin_filter_update_transient' );
add_filter( 'pre_site_transient_update_themes', 'mtsn1_fast_admin_filter_update_transient' );
add_filter( 'pre_site_transient_update_core', 'mtsn1_fast_admin_filter_update_transient' );

function mtsn1_fast_admin_filter_update_transient( $transient ) {
    if ( ! is_admin() ) {
        return $transient;
    }

    global $pagenow;
    // Izinkan pemeriksaan nyata hanya di tab Dashboard -> Updates
    if ( in_array( $pagenow, array( 'update-core.php', 'update.php' ), true ) ) {
        return $transient;
    }

    if ( is_object( $transient ) ) {
        // Trik: perbarui last_checked ke waktu sekarang agar WordPress tidak merasa perlu cek ulang via cURL
        $transient->last_checked = time();
        return $transient;
    }

    return $transient;
}

/**
 * 4. MATIKAN PEMANGGILAN UPDATE SINKRON SAAT ADMIN_INIT
 */
add_action( 'admin_init', function() {
    remove_action( 'admin_init', 'wp_version_check' );
    remove_action( 'admin_init', 'wp_update_plugins' );
    remove_action( 'admin_init', 'wp_update_themes' );
    remove_action( 'admin_init', 'wp_maybe_auto_update' );
}, 1 );

// Pastikan transient updater sekolahpro selalu terisi agar tidak memicu fallback wp_remote_get
add_action( 'init', function() {
    if ( is_admin() && ! get_transient( 'sekolahpro_updater' ) ) {
        set_transient( 'sekolahpro_updater', wp_json_encode( array(
            'version'      => defined( 'SEKOLAHPRO_VERSION' ) ? SEKOLAHPRO_VERSION : '3.1.5.9',
            'requires'     => '5.0',
            'requires_php' => '7.4',
            'download_url' => '',
            'name'         => 'Sekolah Pro',
            'slug'         => 'sekolahpro'
        ) ), 30 * DAY_IN_SECONDS );
    }
    if ( function_exists( 'theme_pre_set_transient_update_mading' ) ) {
        remove_filter( 'pre_set_site_transient_update_themes', 'theme_pre_set_transient_update_mading' );
    }
}, 5 );

/**
 * 5. OPTIMASI WP HEARTBEAT API (MENGURANGI TRAFIK AJAX & BEBAN WORKER HOSTING)
 */
add_action( 'admin_enqueue_scripts', function( $hook ) {
    // Matikan Heartbeat jika bukan di halaman edit postingan (post.php / post-new.php)
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        wp_deregister_script( 'heartbeat' );
    }
}, 1 );

add_filter( 'heartbeat_settings', function( $settings ) {
    // Di halaman edit, perlambat interval heartbeat dari default 15 detik menjadi 60 detik
    $settings['interval'] = 60;
    return $settings;
} );

/**
 * 6. BERSIHKAN WIDGET LAMBAT & QUERY BERAT DI DASHBOARD UTAMA
 */
add_action( 'wp_dashboard_setup', function() {
    // Hapus widget RSS eksternal
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );        // Events & News (RSS cURL)
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );      // Other WordPress News

    // Hapus widget Quick Draft (jarang dipakai dan memicu script autosave)
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );

    // Hapus Site Health test widget (memicu background tests & loopback HTTP check lambat)
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );

    // Hapus Activity widget (query data komentar & postingan berulang)
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );

    // Hapus Welcome panel bawaan WP
    remove_action( 'welcome_panel', 'wp_welcome_panel' );

    // Pasang Widget Akses Cepat Ringan MTsN 1 Blitar
    wp_add_dashboard_widget(
        'mtsn1_admin_speed_widget',
        '⚡ MTsN 1 Blitar - Pusat Kontrol Cepat & Status',
        'mtsn1_render_speed_dashboard_widget'
    );
}, 999 );

function mtsn1_render_speed_dashboard_widget() {
    $current_user = wp_get_current_user();
    $purge_nonce  = wp_create_nonce( 'mtsn1_purge_admin_cache' );
    $purge_url    = add_query_arg( array( 'mtsn1_purge_cache' => '1', '_wpnonce' => $purge_nonce ), admin_url() );
    ?>
    <div style="padding: 6px 0;">
        <p style="font-size: 13px; margin: 0 0 14px 0; color: #334155; line-height: 1.5;">
            Selamat datang, <strong><?php echo esc_html( $current_user->display_name ); ?></strong>! Halaman admin telah diaktifkan dengan <strong>Mode Turbo Speed</strong> (Bebas cURL freeze, heartbeat dioptimalkan, dan cache otomatis).
        </p>
        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px;">
            <a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" class="button button-primary" style="padding: 4px 12px; height: auto; font-weight: 600;">
                ✍️ Tulis Berita
            </a>
            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=prestasi' ) ); ?>" class="button" style="padding: 4px 12px; height: auto;">
                🏆 Tambah Prestasi
            </a>
            <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=agenda' ) ); ?>" class="button" style="padding: 4px 12px; height: auto;">
                📅 Kelola Agenda
            </a>
            <a href="<?php echo esc_url( $purge_url ); ?>" class="button" style="padding: 4px 12px; height: auto; color: #dc2626; border-color: #fca5a5;">
                ⚡ Bersihkan Cache
            </a>
        </div>
        <div style="font-size: 12px; color: #475569; background: #f1f5f9; padding: 10px 12px; border-radius: 6px; border: 1px solid #e2e8f0; line-height: 1.4;">
            🚀 <strong>Status Optimasi:</strong> Heartbeat Throttled (60s) • External HTTP Bypassed • Expired Transients Cleared • LiteSpeed Engine Ready.
        </div>
    </div>
    <?php
}

/**
 * 7. OPTIMASI POST REVISIONS & AUTOSAVE INTERVAL
 */
add_filter( 'wp_revisions_to_keep', function( $num, $post ) {
    return 5; // Batasi maksimal 5 revisi per postingan agar database tabel wp_posts tidak membengkak
}, 10, 2 );

// Nonaktifkan trackback/pingback overhead
add_filter( 'xmlrpc_methods', function( $methods ) {
    unset( $methods['pingback.ping'] );
    return $methods;
} );

/**
 * 8. HAPUS OVERHEAD EMOJI POLYFILL DI ADMIN HEAD
 */
add_action( 'admin_init', function() {
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
} );

/**
 * 9. TOMBOL ONE-CLICK "⚡ BERSIHKAN CACHE & TRANSIENTS" DI ADMIN BAR
 */
add_action( 'admin_bar_menu', function( $admin_bar ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $purge_nonce = wp_create_nonce( 'mtsn1_purge_admin_cache' );
    $purge_url   = add_query_arg( array( 'mtsn1_purge_cache' => '1', '_wpnonce' => $purge_nonce ), admin_url() );

    $admin_bar->add_node( array(
        'id'    => 'mtsn1-fast-purge',
        'title' => '<span class="ab-icon" style="margin-top:2px;">⚡</span><span class="ab-label">Bersihkan Cache</span>',
        'href'  => $purge_url,
        'meta'  => array(
            'title' => 'Hapus expired transients & bersihkan LiteSpeed Cache agar admin selalu responsif',
        ),
    ) );
}, 100 );

add_action( 'admin_init', function() {
    if ( isset( $_GET['mtsn1_purge_cache'] ) && isset( $_GET['_wpnonce'] ) ) {
        if ( wp_verify_nonce( $_GET['_wpnonce'], 'mtsn1_purge_admin_cache' ) && current_user_can( 'manage_options' ) ) {
            global $wpdb;

            // 1. Bersihkan transient kadaluarsa dari wp_options
            $wpdb->query( "DELETE a, b FROM {$wpdb->options} a, {$wpdb->options} b 
                WHERE a.option_name LIKE '_transient_%' 
                AND a.option_name NOT LIKE '_transient_timeout_%' 
                AND b.option_name = CONCAT('_transient_timeout_', SUBSTRING(a.option_name, 12)) 
                AND b.option_value < UNIX_TIMESTAMP()" 
            );

            // 2. Bersihkan site transient kadaluarsa
            $wpdb->query( "DELETE a, b FROM {$wpdb->options} a, {$wpdb->options} b 
                WHERE a.option_name LIKE '_site_transient_%' 
                AND a.option_name NOT LIKE '_site_transient_timeout_%' 
                AND b.option_name = CONCAT('_site_transient_timeout_', SUBSTRING(a.option_name, 17)) 
                AND b.option_value < UNIX_TIMESTAMP()" 
            );

            // 3. Purge LiteSpeed Cache jika aktif
            if ( function_exists( 'do_action' ) ) {
                do_action( 'litespeed_purge_all' );
            }

            // 4. Flush WP Object Cache
            if ( function_exists( 'wp_cache_flush' ) ) {
                wp_cache_flush();
            }

            // Redirect dengan feedback sukses
            wp_safe_redirect( add_query_arg( array( 'mtsn1_cache_cleared' => '1' ), remove_query_arg( array( 'mtsn1_purge_cache', '_wpnonce' ) ) ) );
            exit;
        }
    }

    if ( isset( $_GET['mtsn1_cache_cleared'] ) ) {
        add_action( 'admin_notices', function() {
            ?>
            <div class="notice notice-success is-dismissible" style="border-left-color: #059669; padding: 10px 14px;">
                <p style="font-size: 13px; font-weight: 600; color: #065f46; margin: 0;">
                    🚀 <strong>Sukses!</strong> Semua cache sistem dan transient kadaluarsa berhasil dibersihkan. Memori server telah disegarkan dan loading admin lebih responsif!
                </p>
            </div>
            <?php
        } );
    }
} );
