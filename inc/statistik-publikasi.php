<?php
/**
 * Integrasi Menu & Router Halaman Statistik Publikasi
 * MTsN 1 Blitar Modern Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Otomatis buat halaman 'Statistik Publikasi' di database jika belum ada
 */
add_action( 'init', function() {
    if ( ! get_page_by_path( 'statistik-publikasi' ) ) {
        wp_insert_post( array(
            'post_title'   => 'Statistik Publikasi',
            'post_name'    => 'statistik-publikasi',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => 'Pusat data, statistik publikasi kabar berita, dan rekapitulasi capaian prestasi MTsN 1 Blitar.',
        ) );
    }
} );

/**
 * 2. Injeksi Dinamis Menu "Statistik Publikasi" di Bawah Menu "INFO PUBLIKASI"
 * Memastikan menu selalu muncul di cPanel dan Localhost tanpa harus utak-atik database
 */
add_filter( 'wp_nav_menu_objects', 'mtsn1_inject_statistik_publikasi_menu', 20, 2 );
function mtsn1_inject_statistik_publikasi_menu( $items, $args ) {
    if ( empty( $items ) || ! is_array( $items ) ) {
        return $items;
    }

    $parent_id        = null;
    $last_child_index = null;
    $has_statistik    = false;

    // Cari ID menu induk "INFO PUBLIKASI" & cek apakah Statistik Publikasi sudah ada
    foreach ( $items as $idx => $item ) {
        $title_clean = strtoupper( trim( strip_tags( $item->title ) ) );
        if ( strpos( $title_clean, 'INFO PUBLIKASI' ) !== false ) {
            $parent_id = $item->ID;
        }
        if ( strpos( $title_clean, 'STATISTIK PUBLIKASI' ) !== false ) {
            $has_statistik = true;
            break;
        }
    }

    // Jika sudah ada di database atau menu induk tidak ditemukan, tidak perlu injeksi ulang
    if ( $has_statistik || ! $parent_id ) {
        return $items;
    }

    // Cari posisi anak terakhir dari "INFO PUBLIKASI"
    foreach ( $items as $idx => $item ) {
        if ( (int) $item->menu_item_parent === (int) $parent_id ) {
            $last_child_index = $idx;
        }
    }

    if ( $last_child_index === null ) {
        return $items;
    }

    $stat_url   = home_url( '/statistik-publikasi/' );
    $req_uri    = $_SERVER['REQUEST_URI'] ?? '';
    $is_current = is_page( 'statistik-publikasi' ) || ( strpos( $req_uri, 'statistik-publikasi' ) !== false );

    $stat_item = (object) array(
        'ID'                    => 999988,
        'db_id'                 => 999988,
        'menu_item_parent'      => (string) $parent_id,
        'title'                 => 'Statistik Publikasi',
        'url'                   => $stat_url,
        'classes'               => array( 'menu-item', 'menu-item-type-custom', 'menu-item-object-custom' ),
        'type'                  => 'custom',
        'type_label'            => 'Custom Link',
        'target'                => '',
        'attr_title'            => 'Statistik & Rekapitulasi Publikasi',
        'description'           => '',
        'xfn'                   => '',
        'current'               => $is_current,
        'current_item_ancestor' => false,
        'current_item_parent'   => false,
        'menu_order'            => 99,
    );

    if ( $is_current ) {
        $stat_item->classes[] = 'current-menu-item';
    }

    // Sisipkan tepat di bawah anak terakhir INFO PUBLIKASI
    array_splice( $items, $last_child_index + 1, 0, array( $stat_item ) );

    return $items;
}

/**
 * 3. Router Template Halaman Statistik Publikasi
 */
add_filter( 'template_include', function( $template ) {
    $uri = trim( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
    if ( preg_match( '#statistik-publikasi/?$#', $uri ) || is_page( 'statistik-publikasi' ) ) {
        $custom_tpl = get_template_directory() . '/page-statistik-publikasi.php';
        if ( file_exists( $custom_tpl ) ) {
            return $custom_tpl;
        }
    }
    return $template;
} );
