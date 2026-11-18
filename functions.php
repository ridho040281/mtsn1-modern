<?php
/**
 * Functions and definitions for MTsN 1 Blitar Modern Theme
 */

if ( ! function_exists( 'mtsn1_setup' ) ) :
    function mtsn1_setup() {
        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable featured images
        add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 800, 500, true );
        add_image_size( 'mtsn1-slider', 1400, 560, true );
        add_image_size( 'mtsn1-card', 600, 380, true );
        add_image_size( 'mtsn1-avatar', 200, 200, true );

        // Enable custom logo
        add_theme_support( 'custom-logo', array(
            'height'      => 120,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

        // Register navigation menus
        register_nav_menus( array(
            'primary' => __( 'Menu Utama Header', 'mtsn1-modern' ),
            'footer'  => __( 'Menu Footer', 'mtsn1-modern' ),
        ) );

        // HTML5 support
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );
    }
endif;
add_action( 'after_setup_theme', 'mtsn1_setup' );

/**
 * Enqueue scripts and styles.
 */
function mtsn1_scripts() {
    // Google Fonts - Plus Jakarta Sans
    wp_enqueue_style( 'mtsn1-google-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap', array(), null );

    // Font Awesome 6
    wp_enqueue_style( 'mtsn1-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );

    // Compiled Static Tailwind CSS (Blazing Fast, Zero Render-Blocking)
    wp_enqueue_style( 'mtsn1-tailwind', get_template_directory_uri() . '/assets/css/tailwind.min.css', array(), '1.0.1' );

    // Theme main stylesheet
    wp_enqueue_style( 'mtsn1-style', get_stylesheet_uri(), array( 'mtsn1-tailwind' ), '1.0.1' );
}
add_action( 'wp_enqueue_scripts', 'mtsn1_scripts' );

/**
 * Helper: Indonesian formatted date
 */
function mtsn1_indonesian_date( $timestamp = null ) {
    if ( ! $timestamp ) {
        $timestamp = current_time( 'timestamp' );
    }

    $days = array(
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    );

    $months = array(
        'January'   => 'Januari',
        'February'  => 'Februari',
        'March'     => 'Maret',
        'April'     => 'April',
        'May'       => 'Mei',
        'June'      => 'Juni',
        'July'      => 'Juli',
        'August'    => 'Agustus',
        'September' => 'September',
        'October'   => 'Oktober',
        'November'  => 'November',
        'December'  => 'Desember',
    );

    $day_name   = $days[ gmdate( 'l', $timestamp ) ];
    $month_name = $months[ gmdate( 'F', $timestamp ) ];
    $date_num   = gmdate( 'j', $timestamp );
    $year_num   = gmdate( 'Y', $timestamp );

    return "{$day_name}, {$date_num} {$month_name} {$year_num}";
}

/**
 * Helper: Approximate Hijri Date
 */
function mtsn1_hijri_date( $time = null ) {
    if ( ! $time ) {
        $time = current_time( 'timestamp' );
    }

    $hijri_months = array(
        1 => 'Muharram', 2 => 'Safar', 3 => "Rabi'ul Awwal", 4 => "Rabi'ul Akhir",
        5 => 'Jumadil Awwal', 6 => 'Jumadil Akhir', 7 => 'Rajab', 8 => "Sya'ban",
        9 => 'Ramadhan', 10 => 'Syawwal', 11 => "Dzulqa'dah", 12 => 'Dzulhijjah'
    );

    $d = intval( gmdate( 'j', $time ) );
    $m = intval( gmdate( 'n', $time ) );
    $y = intval( gmdate( 'Y', $time ) );

    if ( ( $y > 1582 ) || ( ( $y == 1582 ) && ( $m > 10 ) ) || ( ( $y == 1582 ) && ( $m == 10 ) && ( $d > 14 ) ) ) {
        $jd = intval( ( 1461 * ( $y + 4800 + intval( ( $m - 14 ) / 12 ) ) ) / 4 ) +
              intval( ( 367 * ( $m - 2 - 12 * ( intval( ( $m - 14 ) / 12 ) ) ) ) / 12 ) -
              intval( ( 3 * ( intval( ( $y + 4900 + intval( ( $m - 14 ) / 12 ) ) / 100 ) ) ) / 4 ) +
              $d - 32075;
    } else {
        $jd = 367 * $y - intval( ( 7 * ( $y + 5001 + intval( ( $m - 9 ) / 7 ) ) ) / 4 ) +
              intval( ( 275 * $m ) / 9 ) + $d + 1729777;
    }

    $l = $jd - 1948440 + 10632;
    $n = intval( ( $l - 1 ) / 10631 );
    $l = $l - 10631 * $n + 354;
    $j = ( intval( ( 10985 - $l ) / 5316 ) ) * ( intval( ( 50 * $l ) / 17719 ) ) +
         ( intval( $l / 5670 ) ) * ( intval( ( 43 * $l ) / 15238 ) );
    $l = $l - ( intval( ( 30 - $j ) / 15 ) ) * ( intval( ( 17719 * $j ) / 50 ) ) -
         ( intval( $j / 16 ) ) * ( intval( ( 15238 * $j ) / 43 ) ) + 29;
    $month = intval( ( 24 * $l ) / 709 );
    $day = $l - intval( ( 709 * $month ) / 24 );
    $year = 30 * $n + $j - 30;

    $month_name = isset( $hijri_months[ $month ] ) ? $hijri_months[ $month ] : '';

    return "{$day} {$month_name} {$year} H";
}

/**
 * Reading time calculation
 */
function mtsn1_reading_time() {
    global $post;
    $content = get_post_field( 'post_content', $post->ID );
    $word_count = str_word_count( strip_tags( $content ) );
    $readingtime = ceil( $word_count / 200 );
    return max( 1, $readingtime ) . ' menit baca';
}

/**
 * Numeric pagination
 */
function mtsn1_pagination() {
    global $wp_query;
    $big = 999999999;
    $links = paginate_links( array(
        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format'    => '?paged=%#%',
        'current'   => max( 1, get_query_var( 'paged' ) ),
        'total'     => $wp_query->max_num_pages,
        'type'      => 'array',
        'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
        'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
    ) );

    if ( $links ) {
        echo '<div class="flex justify-center items-center gap-2 mt-10">';
        foreach ( $links as $link ) {
            $active_class = strpos( $link, 'current' ) !== false ? 'bg-emerald-600 text-white font-bold' : 'bg-white hover:bg-emerald-50 text-slate-700 border border-slate-200';
            $clean_link = str_replace( 'page-numbers', "px-4 py-2 rounded-xl text-sm transition shadow-sm {$active_class}", $link );
            echo $clean_link;
        }
        echo '</div>';
    }
}

/**
 * Custom Walker for Modern Dropdown Navigation Menu
 */
class MTSN1_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        if ( $depth === 0 ) {
            $output .= "\n{$indent}<div class=\"absolute left-0 top-full pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 min-w-[260px] pointer-events-none group-hover:pointer-events-auto shadow-2xl\">\n";
            $output .= "{$indent}  <div class=\"relative bg-[#0c1b69] text-white rounded-2xl p-3 shadow-2xl border border-blue-900/60\">\n";
            $output .= "{$indent}    <div class=\"absolute -top-1.5 left-7 w-3 h-3 bg-[#0c1b69] rotate-45 border-t border-l border-blue-900/60\"></div>\n";
            $output .= "{$indent}    <ul class=\"space-y-1 relative z-10\">\n";
        } else {
            $output .= "\n{$indent}<ul class=\"pl-3 py-1 space-y-1 border-l border-blue-800\">\n";
        }
    }

    function end_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        if ( $depth === 0 ) {
            $output .= "{$indent}    </ul>\n";
            $output .= "{$indent}  </div>\n";
            $output .= "{$indent}</div>\n";
        } else {
            $output .= "{$indent}</ul>\n";
        }
    }

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $has_children = in_array( 'menu-item-has-children', (array) $item->classes );
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $url   = ! empty( $item->url ) ? esc_url( $item->url ) : '#';

        if ( $depth === 0 ) {
            $is_spmb = ( stripos( $title, 'spmb' ) !== false || stripos( $title, 'ppdb' ) !== false );
            if ( $is_spmb ) {
                $target = ( strpos( $url, 'http' ) === 0 && strpos( $url, home_url() ) === false ) ? ' target="_blank" rel="noopener noreferrer"' : '';
                $output .= "<li class=\"relative shrink-0 my-auto ml-1 2xl:ml-2\">";
                $output .= "<a href=\"{$url}\"{$target} class=\"group/spmb relative inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gradient-to-r from-amber-400 via-amber-300 to-yellow-400 hover:from-amber-300 hover:to-yellow-300 text-slate-950 font-black text-xs sm:text-[13px] shadow-sm hover:shadow-md hover:-translate-y-0.5 border border-amber-200 transition-all duration-300\">";
                $output .= "<span class=\"w-6 h-6 rounded-lg bg-slate-950 text-gold-400 flex items-center justify-center text-[11px] shadow-inner group-hover/spmb:scale-105 group-hover/spmb:bg-emerald-950 group-hover/spmb:text-amber-300 transition-all shrink-0\"><i class=\"fa-solid fa-graduation-cap\"></i></span>";
                $output .= "<span class=\"tracking-tight whitespace-nowrap\">{$title}</span>";
                $output .= "<i class=\"fa-solid fa-arrow-right text-[10px] text-slate-800 group-hover/spmb:translate-x-0.5 transition-transform ml-0.5\"></i>";
                $output .= "</a>";
                return;
            }

            $li_classes = 'relative group py-2';
            $a_classes  = 'flex items-center gap-1.5 text-xs 2xl:text-sm font-extrabold text-slate-800 hover:text-emerald-700 transition uppercase tracking-wider whitespace-nowrap';

            $output .= "<li class=\"{$li_classes}\">";
            $output .= "<a href=\"{$url}\" class=\"{$a_classes}\">";
            $output .= "<span>{$title}</span>";
            if ( $has_children ) {
                $output .= '<i class="fa-solid fa-chevron-down text-[10px] ml-1 text-slate-400 group-hover:text-emerald-700 group-hover:rotate-180 transition-transform duration-200"></i>';
            }
            $output .= "</a>";
        } else {
            $output .= "<li>";
            $output .= "<a href=\"{$url}\" class=\"block px-3 py-2 rounded-xl text-xs font-bold text-white hover:text-gold-300 hover:bg-white/10 transition leading-snug\">";
            $output .= "{$title}";
            $output .= "</a>";
        }
    }

    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }
}

/**
 * -----------------------------------------------------------------------------
 * OPTIMASI KECEPATAN WP-ADMIN & PERFORMANCE BOOST (LOCAL & PRODUCTION)
 * -----------------------------------------------------------------------------
 */

// 1. Hapus widget RSS eksternal di Dashboard yang memicu cURL lambat
add_action( 'wp_dashboard_setup', 'mtsn1_disable_slow_dashboard_widgets', 999 );
function mtsn1_disable_slow_dashboard_widgets() {
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );     // WordPress Events & News
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Draft
}

// 2. Matikan pengecekan update eksternal yang memblokir navigasi menu admin secara sinkron
add_action( 'admin_init', function() {
    remove_action( 'admin_init', 'wp_version_check' );
    remove_action( 'admin_init', 'wp_update_plugins' );
    remove_action( 'admin_init', 'wp_update_themes' );
}, 1 );

// 3. Cegah timeout cURL dari updater ciuss.com (sekolahpro / mading) dan planet.wordpress.org di localhost
add_filter( 'pre_http_request', function( $pre, $args, $url ) {
    if ( is_string( $url ) ) {
        if ( strpos( $url, 'update.ciuss.com' ) !== false || strpos( $url, 'planet.wordpress.org' ) !== false ) {
            return new WP_Error( 'http_request_blocked', 'External update check bypassed for local speed' );
        }
    }
    return $pre;
}, 10, 3 );

// 4. Pastikan transient updater sekolahpro selalu terisi agar tidak memicu fallback wp_remote_get
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
    // Hapus hook updater tema mading jika aktif (karena tema aktif adalah mtsn1-modern)
    if ( function_exists( 'theme_pre_set_transient_update_mading' ) ) {
        remove_filter( 'pre_set_site_transient_update_themes', 'theme_pre_set_transient_update_mading' );
    }
}, 5 );

require_once get_template_directory() . '/inc/admin-layanan.php';
require_once get_template_directory() . '/inc/admin-blog-author.php';
require_once get_template_directory() . '/inc/admin-editorial-headline.php';
require_once get_template_directory() . '/inc/admin-medsos.php';
require_once get_template_directory() . '/inc/admin-instagram-sync.php';
require_once get_template_directory() . '/inc/admin-prestasi-meta.php';
require_once get_template_directory() . '/inc/auto-sync-content.php';
require_once get_template_directory() . '/inc/opengraph.php';
require_once get_template_directory() . '/inc/word-editor.php';
require_once get_template_directory() . '/inc/admin-berita-redaksi.php';

/**
 * Helper Ekstraksi YouTube Video ID dari URL / Embed string
 */
function mtsn1_extract_youtube_id( $s ) {
    if ( empty( $s ) ) {
        return '';
    }
    $s = trim( $s );
    if ( preg_match( '/^[a-zA-Z0-9_-]{11}$/', $s ) ) {
        return $s;
    }
    $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|live)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';
    if ( preg_match( $pattern, $s, $match ) ) {
        return $match[1];
    }
    return '';
}



