<?php
/**
 * Sistem Penghitung & Penampil Pembaca Artikel (Post Views Counter)
 * MTsN 1 Blitar Modern Theme
 *
 * Fitur:
 * - Kompatibel dengan LiteSpeed Cache & Full Page Caching (menggunakan AJAX non-blocking)
 * - Mencegah spam refresh / F5 menggunakan sessionStorage per artikel
 * - Bot & crawler filter (Googlebot, Bingbot, preview scraper tidak menaikkan view)
 * - Kolom "Dibaca" di tabel admin WordPress dengan fitur pengurutan (sortable)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Ambil jumlah view artikel
 *
 * @param int|null $post_id
 * @return int
 */
function mtsn1_get_post_views( $post_id = null ) {
    $post_id = $post_id ? absint( $post_id ) : get_the_ID();
    if ( ! $post_id ) {
        return 0;
    }

    $views = get_post_meta( $post_id, 'mtsn1_post_views_count', true );
    if ( $views === '' || $views === false ) {
        // Cek fallback meta dari plugin umum seperti WP-PostViews jika ada
        $views = get_post_meta( $post_id, 'post_views_count', true );
    }

    return (int) $views;
}

/**
 * 2. Tambah (increment) view artikel
 *
 * @param int $post_id
 * @return int Jumlah view baru
 */
function mtsn1_increment_post_views( $post_id ) {
    $post_id = absint( $post_id );
    if ( ! $post_id ) {
        return 0;
    }

    $current_views = mtsn1_get_post_views( $post_id );
    $new_views     = $current_views + 1;

    update_post_meta( $post_id, 'mtsn1_post_views_count', $new_views );
    // Sinkronkan juga ke meta key standar agar kompatibel jika ada plugin pihak ketiga
    update_post_meta( $post_id, 'post_views_count', $new_views );

    return $new_views;
}

/**
 * 3. Deteksi User Agent Bot / Crawler
 *
 * @return bool
 */
function mtsn1_is_bot() {
    if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
        return true;
    }

    $ua   = strtolower( $_SERVER['HTTP_USER_AGENT'] );
    $bots = array(
        'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider',
        'yandexbot', 'sogou', 'exabot', 'facebot', 'facebookexternalhit',
        'ia_archiver', 'whatsapp', 'telegrambot', 'twitterbot', 'slackbot',
        'applebot', 'semrushbot', 'ahrefsbot', 'dotbot', 'rogerbot',
        'curl', 'wget', 'python', 'headlesschrome', 'lighthouse'
    );

    foreach ( $bots as $bot ) {
        if ( strpos( $ua, $bot ) !== false ) {
            return true;
        }
    }

    return false;
}

/**
 * 4. Endpoint AJAX untuk Menghitung & Mengambil Jumlah View
 * Kompatibel penuh dengan caching server (LiteSpeed Cache / Cloudflare)
 */
add_action( 'wp_ajax_mtsn1_track_view', 'mtsn1_ajax_track_view_callback' );
add_action( 'wp_ajax_nopriv_mtsn1_track_view', 'mtsn1_ajax_track_view_callback' );
function mtsn1_ajax_track_view_callback() {
    $post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
    if ( ! $post_id || get_post_status( $post_id ) !== 'publish' ) {
        wp_send_json_error( array( 'message' => 'Invalid post' ) );
    }

    $only_get = ! empty( $_GET['only_get'] );

    if ( ! $only_get && ! mtsn1_is_bot() ) {
        $views = mtsn1_increment_post_views( $post_id );
    } else {
        $views = mtsn1_get_post_views( $post_id );
    }

    wp_send_json_success( array(
        'post_id'   => $post_id,
        'views'     => $views,
        'formatted' => number_format_i18n( $views ),
    ) );
}

/**
 * 5. Skrip JavaScript Pelacak View di Frontend (non-blocking)
 * Dijalankan di footer pada halaman single artikel/postingan
 */
add_action( 'wp_footer', 'mtsn1_render_post_views_tracker_script', 30 );
function mtsn1_render_post_views_tracker_script() {
    if ( ! is_singular() ) {
        return;
    }
    ?>
    <script>
    (function() {
      document.addEventListener('DOMContentLoaded', function() {
        var viewEls = document.querySelectorAll('.mtsn1-view-count[data-post-id]');
        if (!viewEls || !viewEls.length) return;

        viewEls.forEach(function(el) {
          var postId = el.getAttribute('data-post-id');
          if (!postId) return;

          var sessionKey = 'mtsn1_viewed_' + postId;
          var alreadyCounted = false;
          try {
            alreadyCounted = sessionStorage.getItem(sessionKey) === '1';
          } catch(e) {}

          var ajaxUrl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
          var url = ajaxUrl + '?action=mtsn1_track_view&post_id=' + encodeURIComponent(postId) + (alreadyCounted ? '&only_get=1' : '');

          fetch(url)
            .then(function(res) { return res.json(); })
            .then(function(data) {
              if (data && data.success && data.data && data.data.formatted) {
                el.textContent = data.data.formatted;
                if (!alreadyCounted) {
                  try { sessionStorage.setItem(sessionKey, '1'); } catch(e) {}
                }
              }
            })
            .catch(function(err) {});
        });
      });
    })();
    </script>
    <?php
}

/**
 * 6. Tambah Kolom "Dibaca" di Tabel Admin Berita (WP Admin)
 */
add_filter( 'manage_post_posts_columns', 'mtsn1_register_views_column' );
add_filter( 'manage_editorial_posts_columns', 'mtsn1_register_views_column' );
add_filter( 'manage_prestasi_posts_columns', 'mtsn1_register_views_column' );
function mtsn1_register_views_column( $columns ) {
    $new_cols = array();
    foreach ( $columns as $key => $title ) {
        $new_cols[ $key ] = $title;
        // Sisipkan setelah kolom judul atau tanggal
        if ( 'title' === $key ) {
            $new_cols['post_views'] = '<i class="dashicons dashicons-visibility" style="margin-right:3px;"></i> Dibaca';
        }
    }
    return $new_cols;
}

/**
 * Tampilkan Nilai Kolom "Dibaca" di Tabel Admin
 */
add_action( 'manage_posts_custom_column', 'mtsn1_render_views_column_content', 10, 2 );
function mtsn1_render_views_column_content( $column_name, $post_id ) {
    if ( 'post_views' === $column_name ) {
        $views = mtsn1_get_post_views( $post_id );
        echo '<span style="display:inline-flex;align-items:center;gap:4px;font-weight:600;color:#047857;background:#ecfdf5;padding:2px 8px;border-radius:12px;font-size:12px;border:1px solid #a7f3d0;">';
        echo '<span class="dashicons dashicons-visibility" style="font-size:14px;width:14px;height:14px;"></span> ' . esc_html( number_format_i18n( $views ) ) . ' kali';
        echo '</span>';
    }
}

/**
 * Jadikan Kolom "Dibaca" Dapat Diurutkan (Sortable)
 */
add_filter( 'manage_edit-post_sortable_columns', 'mtsn1_register_views_sortable_column' );
add_filter( 'manage_edit-editorial_sortable_columns', 'mtsn1_register_views_sortable_column' );
add_filter( 'manage_edit-prestasi_sortable_columns', 'mtsn1_register_views_sortable_column' );
function mtsn1_register_views_sortable_column( $columns ) {
    $columns['post_views'] = 'post_views';
    return $columns;
}

/**
 * Logika Pengurutan Kolom "Dibaca" di Query Admin
 */
add_action( 'pre_get_posts', 'mtsn1_sort_views_column_query' );
function mtsn1_sort_views_column_query( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( 'post_views' === $query->get( 'orderby' ) ) {
        $query->set( 'meta_key', 'mtsn1_post_views_count' );
        $query->set( 'orderby', 'meta_value_num' );
    }
}
