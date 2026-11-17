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
            if ( has_post_thumbnail( $post_id ) ) {
                $thumb_id  = get_post_thumbnail_id( $post_id );
                $img_large = wp_get_attachment_image_src( $thumb_id, 'large' );
                $img_full  = wp_get_attachment_image_src( $thumb_id, 'full' );
                $img_data  = ! empty( $img_large ) ? $img_large : $img_full;

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
