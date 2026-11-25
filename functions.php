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
        set_post_thumbnail_size( 1200, 9999, false );
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

    // Theme main stylesheet (dengan cache-busting otomatis)
    $style_ver = file_exists( get_stylesheet_directory() . '/style.css' ) ? filemtime( get_stylesheet_directory() . '/style.css' ) : '1.0.2';
    wp_enqueue_style( 'mtsn1-style', get_stylesheet_uri(), array( 'mtsn1-tailwind' ), $style_ver );
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
 * Helper: Ambil URL thumbnail postingan, atau ekstrak gambar pertama dari isi konten (post_content)
 * jika tidak ada Featured Image (Gambar Andalan), dan fallback ke default santri.jpg jika kosong sama sekali.
 */
function mtsn1_get_post_thumbnail_url( $post_id = null, $size = 'full' ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    if ( ! $post_id ) {
        return home_url( '/wp-content/uploads/2026/10/santri.jpg' );
    }

    // 1. Cek Featured Image bawaan WordPress
    if ( has_post_thumbnail( $post_id ) ) {
        $thumb = get_the_post_thumbnail_url( $post_id, $size );
        if ( ! empty( $thumb ) ) {
            return $thumb;
        }
    }

    // 2. Ekstrak gambar pertama dari post_content jika ada
    $post = get_post( $post_id );
    if ( $post && ! empty( $post->post_content ) ) {
        if ( preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $post->post_content, $matches ) ) {
            return $matches[1];
        }
    }

    // 3. Cek lampiran media gambar pada postingan
    $attachments = get_posts( array(
        'post_type'      => 'attachment',
        'posts_per_page' => 1,
        'post_parent'    => $post_id,
        'post_mime_type' => 'image',
    ) );
    if ( ! empty( $attachments ) ) {
        $att_url = wp_get_attachment_image_url( $attachments[0]->ID, $size );
        if ( ! empty( $att_url ) ) {
            return $att_url;
        }
    }

    // 4. Fallback gambar default
    return home_url( '/wp-content/uploads/2026/10/santri.jpg' );
}

/**
 * Helper: Cek apakah postingan memiliki Featured Image atau gambar di dalam konten
 */
function mtsn1_has_post_thumbnail( $post_id = null ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    if ( has_post_thumbnail( $post_id ) ) {
        return true;
    }
    $post = get_post( $post_id );
    if ( $post && ! empty( $post->post_content ) && preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $post->post_content ) ) {
        return true;
    }
    return false;
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

            $output .= "<li class=\"{$li_classes}\" data-menu=\"" . esc_attr( $title ) . "\">";
            $output .= "<a href=\"{$url}\" class=\"{$a_classes}\">";
            $output .= "<span>{$title}</span>";
            if ( $has_children ) {
                $output .= '<i class="fa-solid fa-chevron-down text-[10px] ml-1 text-slate-400 group-hover:text-emerald-700 group-hover:rotate-180 transition-transform duration-200"></i>';
            }
            $output .= "</a>";
        } else {
            // Cegah submenu "Statistik" ganda muncul di dropdown mana pun
            if ( stripos( $title, 'Statistik' ) !== false || stripos( $url, 'statistik' ) !== false ) {
                if ( stripos( $title, 'Humas' ) === false ) {
                    return;
                }
            }
            $output .= "<li>";
            $output .= "<a href=\"{$url}\" class=\"block px-3 py-2 rounded-xl text-xs font-bold text-white hover:text-gold-300 hover:bg-white/10 transition leading-snug\">";
            $output .= "{$title}";
            $output .= "</a>";
        }
    }

    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $url   = ! empty( $item->url ) ? esc_url( $item->url ) : '#';
        if ( $depth > 0 && ( stripos( $title, 'Statistik' ) !== false || stripos( $url, 'statistik' ) !== false ) ) {
            if ( stripos( $title, 'Humas' ) === false ) {
                return;
            }
        }
        $output .= "</li>\n";
    }
}

/**
 * Filter objek menu navigasi WordPress:
 * 1. Buang submenu 'Statistik' dari dropdown manapun (karena sudah diwakili oleh menu 'Bidang Humas').
 * 2. Pastikan submenu 'Bidang Humas' selalu mengarah ke /statistik-publikasi/.
 */
add_filter( 'wp_nav_menu_objects', 'mtsn1_filter_nav_menu_objects', 20, 2 );
function mtsn1_filter_nav_menu_objects( $items, $args ) {
    if ( ! is_array( $items ) || empty( $items ) ) {
        return $items;
    }

    foreach ( $items as $key => $item ) {
        $title     = trim( apply_filters( 'the_title', $item->title, $item->ID ) );
        $url       = trim( $item->url );
        $is_child  = ! empty( $item->menu_item_parent );

        // Jika merupakan submenu dan bernama Statistik atau URL-nya statistik -> buang dari navigasi!
        if ( $is_child && ( stripos( $title, 'Statistik' ) !== false || stripos( $url, 'statistik' ) !== false ) ) {
            if ( stripos( $title, 'Humas' ) === false ) {
                unset( $items[ $key ] );
                continue;
            }
        }

        // Pastikan menu Bidang Humas mengarah ke halaman statistik publikasi
        if ( stripos( $title, 'Bidang Humas' ) !== false || ( stripos( $title, 'Humas' ) !== false && stripos( $title, 'Bidang' ) !== false ) ) {
            $item->url = home_url( '/statistik-publikasi/' );
        }

        // Pastikan menu Sambutan Kepala Madrasah mengarah ke postingan Editorial resmi
        if ( stripos( $title, 'Sambutan Kepala Madrasah' ) !== false || ( stripos( $title, 'Sambutan' ) !== false && stripos( $title, 'Kepala' ) !== false ) ) {
            $editorial_posts = get_posts( array(
                'post_type'      => 'editorial',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
            ) );
            if ( ! empty( $editorial_posts ) ) {
                $item->url = get_permalink( $editorial_posts[0]->ID );
            }
        }
    }

    return array_values( $items );
}

add_filter( 'walker_nav_menu_start_el', function( $item_output, $item, $depth, $args ) {
    $title = trim( apply_filters( 'the_title', $item->title, $item->ID ) );
    $url   = trim( $item->url );
    if ( $depth > 0 && ( stripos( $title, 'Statistik' ) !== false || stripos( $url, 'statistik' ) !== false ) ) {
        if ( stripos( $title, 'Humas' ) === false ) {
            return '';
        }
    }
    return $item_output;
}, 99, 4 );

/**
 * Redirect otomatis dari URL laman lama /sambutan-kepala-madrasah/ ke halaman resmi Editorial
 */
add_action( 'template_redirect', function() {
    $current_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
    if ( is_page( 'sambutan-kepala-madrasah' ) || strpos( $current_uri, 'sambutan-kepala-madrasah' ) !== false ) {
        $editorial_posts = get_posts( array(
            'post_type'      => 'editorial',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ) );
        if ( ! empty( $editorial_posts ) ) {
            wp_safe_redirect( get_permalink( $editorial_posts[0]->ID ), 301 );
            exit;
        }
    }
} );

// Otomatis bersihkan LiteSpeed Cache saat tema diperbarui
add_action( 'init', function() {
    if ( get_option( 'mtsn1_lscache_purged_v5' ) !== 'yes' || isset( $_GET['purge_cache'] ) ) {
        if ( function_exists( 'do_action' ) ) {
            do_action( 'litespeed_purge_all' );
        }
        update_option( 'mtsn1_lscache_purged_v5', 'yes' );
    }
}, 1 );

/**
 * -----------------------------------------------------------------------------
 * OPTIMASI KECEPATAN WP-ADMIN & PERFORMANCE BOOST (LOCAL & PRODUCTION)
 * -----------------------------------------------------------------------------
 */
require_once get_template_directory() . '/inc/admin-performance.php';

require_once get_template_directory() . '/inc/admin-layanan.php';
require_once get_template_directory() . '/inc/admin-blog-author.php';
require_once get_template_directory() . '/inc/admin-editorial-headline.php';
require_once get_template_directory() . '/inc/admin-medsos.php';
require_once get_template_directory() . '/inc/admin-instagram-sync.php';
require_once get_template_directory() . '/inc/admin-prestasi-meta.php';

// Hanya muat migrasi auto-sync jika belum pernah disinkronkan atau dipicu manual (?mtsn1_run_sync=1)
if ( isset( $_GET['mtsn1_run_sync'] ) || get_option( 'mtsn1_alqalam_sync_version' ) !== 'v3_clean_series' || get_option( 'mtsn1_menu_and_slug_fix_version' ) !== 'v5_unify_sambutan_editorial' ) {
    require_once get_template_directory() . '/inc/auto-sync-content.php';
}
if ( isset( $_GET['mtsn1_run_sync'] ) || get_option( 'mtsn1_prestasi_sync_version_ig' ) !== 'v4_fix_flyer_mismatch' ) {
    require_once get_template_directory() . '/inc/auto-sync-prestasi.php';
}

require_once get_template_directory() . '/inc/opengraph.php';
require_once get_template_directory() . '/inc/word-editor.php';
require_once get_template_directory() . '/inc/admin-berita-redaksi.php';
require_once get_template_directory() . '/inc/print-berita.php';
require_once get_template_directory() . '/inc/statistik-publikasi.php';
require_once get_template_directory() . '/inc/auto-webp-converter.php';
require_once get_template_directory() . '/inc/post-views.php';

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

/**
 * Pratinjau Featured Image di Admin Panel: Tampil Penuh & Zero-Crop
 * Memastikan gambar utama di halaman admin (Classic Editor, Word Editor, Gutenberg)
 * tampil utuh mengikuti rasio aslinya tanpa terpotong di sisi kiri-kanan.
 */
add_filter( 'admin_post_thumbnail_size', function( $size, $thumbnail_id, $post ) {
    return 'full';
}, 10, 3 );

add_action( 'admin_head', 'mtsn1_admin_featured_image_styles' );
function mtsn1_admin_featured_image_styles() {
    ?>
    <style>
        /* Tampilan Kotak Featured Image di Admin Panel */
        #postimagediv .inside {
            padding: 12px 14px 16px 14px !important;
        }
        #postimagediv .inside a {
            display: block !important;
            width: 100% !important;
            text-align: center !important;
        }
        #postimagediv .inside img {
            max-width: 100% !important;
            width: 100% !important;
            height: auto !important;
            max-height: 520px !important;
            object-fit: contain !important;
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 2px 8px -2px rgba(15, 23, 42, 0.08) !important;
            display: block !important;
            margin: 0 auto 10px auto !important;
            background-color: #f8fafc !important;
        }
        #postimagediv #set-post-thumbnail-desc {
            font-size: 11px !important;
            color: #64748b !important;
            text-align: center !important;
            margin: 6px 0 8px 0 !important;
        }
        #postimagediv #remove-post-thumbnail {
            display: inline-block !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #ef4444 !important;
        }
        /* Dukungan untuk Gutenberg Block Editor */
        .editor-post-featured-image__preview,
        .editor-post-featured-image img {
            width: 100% !important;
            height: auto !important;
            max-height: 520px !important;
            object-fit: contain !important;
            border-radius: 8px !important;
        }
    </style>
    <?php
}



