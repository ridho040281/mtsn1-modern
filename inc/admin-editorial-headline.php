<?php
/**
 * Pengaturan Judul Kutipan Besar (Headline Sambutan Kepala Madrasah) untuk Post Type Editorial
 * Menjadikan judul kutipan di halaman beranda dinamis dan dapat diedit langsung dari:
 * 1. Menu Sub-halaman: Editorial -> ⭐ Judul Sambutan Beranda (Paling Mudah)
 * 2. Editor Postingan Editorial (Meta Box di bawah teks dan di sidebar)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Pastikan post type editorial mendukung excerpt
add_action( 'init', function() {
    add_post_type_support( 'editorial', 'excerpt' );
}, 20 );

/**
 * 1. TAMBAHKAN MENU KHUSUS DI SIDEBAR WP-ADMIN:
 *    Editorial -> ⭐ Judul Sambutan Beranda
 */
add_action( 'admin_menu', 'mtsn1_register_editorial_settings_submenu' );
function mtsn1_register_editorial_settings_submenu() {
    add_submenu_page(
        'edit.php?post_type=editorial',
        'Edit Judul Sambutan Beranda',
        '⭐ Judul Sambutan Beranda',
        'edit_posts',
        'editorial-headline-settings',
        'mtsn1_render_editorial_settings_page'
    );
}

/**
 * Halaman Pengaturan Khusus yang Sangat Mudah Diakses
 */
function mtsn1_render_editorial_settings_page() {
    // Cari postingan editorial aktif (post ID 384 atau yang pertama)
    $editorial_post = get_posts( array(
        'post_type'      => 'editorial',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
    ) );

    $post_id = ! empty( $editorial_post ) ? $editorial_post[0]->ID : 384;

    // Handle Form Submit
    $saved = false;
    if ( isset( $_POST['mtsn1_save_headline_nonce'] ) && wp_verify_nonce( $_POST['mtsn1_save_headline_nonce'], 'mtsn1_save_headline_action' ) ) {
        if ( isset( $_POST['editorial_headline'] ) ) {
            $new_headline = sanitize_text_field( wp_unslash( $_POST['editorial_headline'] ) );
            update_post_meta( $post_id, '_editorial_headline', $new_headline );
            $saved = true;
        }

        if ( isset( $_POST['editorial_badge_title'] ) && ! empty( $_POST['editorial_badge_title'] ) ) {
            $new_badge = sanitize_text_field( wp_unslash( $_POST['editorial_badge_title'] ) );
            wp_update_post( array(
                'ID'         => $post_id,
                'post_title' => $new_badge,
            ) );
        }
    }

    $current_headline = get_post_meta( $post_id, '_editorial_headline', true );
    if ( empty( $current_headline ) ) {
        $current_headline = 'Mendidik Generasi Beradab, Unggul dalam Prestasi, dan Cakap Digital';
    }

    $current_badge = get_the_title( $post_id );
    if ( empty( $current_badge ) ) {
        $current_badge = 'SEKAPUR SIRIH KEPALA MADRASAH';
    }
    ?>
    <div class="wrap" style="max-width: 900px; margin-top: 25px;">
        <h1 style="font-weight: 900; font-size: 24px; color: #0f172a; margin-bottom: 8px;">
            <span style="color: #059669;">⭐</span> Edit Judul Kutipan Sambutan Kepala Madrasah
        </h1>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 25px;">
            Halaman ini khusus untuk mengatur teks judul kutipan besar dan badge sambutan yang muncul pada halaman depan (Beranda) website MTsN 1 Blitar.
        </p>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible" style="padding: 12px 16px; border-radius: 8px; border-left-color: #059669; font-weight: bold; font-size: 13px;">
                ✓ Berhasil Disimpan! Teks kutipan di halaman depan website sudah diperbarui. <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" style="text-decoration: underline; margin-left: 8px;">Lihat Beranda &rarr;</a>
            </div>
        <?php endif; ?>

        <div style="background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <form method="post" action="">
                <?php wp_nonce_field( 'mtsn1_save_headline_action', 'mtsn1_save_headline_nonce' ); ?>

                <!-- 1. Judul Kutipan Besar -->
                <div style="margin-bottom: 24px;">
                    <label for="editorial_headline" style="display: block; font-weight: 800; font-size: 14px; color: #0f172a; margin-bottom: 8px;">
                        1. Teks Judul Kutipan Besar (Headline di Beranda):
                    </label>
                    <input type="text" 
                           id="editorial_headline" 
                           name="editorial_headline" 
                           value="<?php echo esc_attr( $current_headline ); ?>" 
                           style="width: 100%; padding: 12px 16px; font-size: 15px; font-weight: 700; color: #0f172a; border: 2px solid #cbd5e1; border-radius: 10px; box-sizing: border-box;" 
                           placeholder="Contoh: Mendidik Generasi Beradab, Unggul dalam Prestasi, dan Cakap Digital" 
                           required />
                    <p style="font-size: 12px; color: #64748b; margin-top: 6px;">
                        Teks ini adalah kutipan besar di bawah badge hijau pada halaman depan beranda.
                    </p>
                </div>

                <!-- 2. Badge Atas -->
                <div style="margin-bottom: 28px;">
                    <label for="editorial_badge_title" style="display: block; font-weight: 800; font-size: 14px; color: #0f172a; margin-bottom: 8px;">
                        2. Teks Badge Hijau di Atas Judul:
                    </label>
                    <input type="text" 
                           id="editorial_badge_title" 
                           name="editorial_badge_title" 
                           value="<?php echo esc_attr( $current_badge ); ?>" 
                           style="width: 100%; padding: 10px 14px; font-size: 13px; font-weight: 600; color: #334155; border: 1px solid #cbd5e1; border-radius: 10px; box-sizing: border-box;" 
                           placeholder="Contoh: SEKAPUR SIRIH KEPALA MADRASAH" 
                           required />
                    <p style="font-size: 12px; color: #64748b; margin-top: 6px;">
                        Teks badge hijau kecil (misal: <em>SEKAPUR SIRIH KEPALA MADRASAH</em>).
                    </p>
                </div>

                <!-- Pratinjau Tampilan -->
                <div style="background: #f8fafc; border: 1px dashed #cbd5e1; padding: 20px; border-radius: 12px; margin-bottom: 28px;">
                    <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                        Pratinjau Hasil di Beranda:
                    </span>
                    <div style="display: inline-block; background: #d1fae5; color: #065f46; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; margin-bottom: 8px;">
                        ❝ <?php echo esc_html( $current_badge ); ?>
                    </div>
                    <div style="font-size: 20px; font-weight: 900; color: #0f172a; line-height: 1.3;">
                        "<?php echo esc_html( $current_headline ); ?>"
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div style="display: flex; items-center; gap: 12px;">
                    <button type="submit" class="button button-primary button-large" style="background: #059669; border-color: #047857; font-weight: 800; font-size: 14px; padding: 6px 24px; border-radius: 8px; height: auto;">
                        💾 Simpan Perubahan
                    </button>
                    <a href="<?php echo esc_url( get_edit_post_link( $post_id ) ); ?>" class="button button-secondary" style="font-size: 13px; padding: 6px 16px; border-radius: 8px; height: auto;">
                        📝 Edit Isi Paragraf Sambutan & Foto di Editor Lengkap
                    </a>
                </div>
            </form>
        </div>
    </div>
    <?php
}

/**
 * 2. TAMBAHKAN META BOX PADA EDITOR POSTINGAN EDITORIAL:
 *    Tampil di bagian bawah (normal) dan sidebar (side)
 */
function mtsn1_add_editorial_meta_box() {
    add_meta_box(
        'mtsn1_editorial_headline_box',
        '💬 Judul Kutipan Besar Sambutan (Headline di Beranda)',
        'mtsn1_render_editorial_headline_box',
        'editorial',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'mtsn1_add_editorial_meta_box' );

function mtsn1_render_editorial_headline_box( $post ) {
    wp_nonce_field( 'mtsn1_editorial_nonce_action', 'mtsn1_editorial_nonce' );

    $headline = get_post_meta( $post->ID, '_editorial_headline', true );
    if ( empty( $headline ) ) {
        $headline = ! empty( $post->post_excerpt ) ? $post->post_excerpt : 'Mendidik Generasi Beradab, Unggul dalam Prestasi, dan Cakap Digital';
    }
    ?>
    <div style="padding: 12px 0;">
        <label for="mtsn1_editorial_headline" style="display: block; font-weight: 700; font-size: 13px; margin-bottom: 6px; color: #1e293b;">
            Teks Judul Kutipan Besar (Tampil di Beranda):
        </label>
        <input type="text" 
               id="mtsn1_editorial_headline" 
               name="mtsn1_editorial_headline" 
               value="<?php echo esc_attr( $headline ); ?>" 
               placeholder="Contoh: Mendidik Generasi Beradab, Unggul dalam Prestasi, dan Cakap Digital"
               style="width: 100%; max-width: 100%; padding: 10px 14px; font-size: 14px; font-weight: 600; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box;" />
        
        <p style="margin-top: 8px; font-size: 12px; color: #64748b; line-height: 1.5;">
            💡 <strong>Panduan:</strong> Teks di atas adalah kalimat kutipan berhuruf tebal yang muncul di bawah label <em>"SEKAPUR SIRIH KEPALA MADRASAH"</em> pada halaman depan website. Anda dapat menggantinya kapan saja dan klik tombol <strong>Perbarui / Update</strong> di kanan atas untuk menyimpan perubahan.
        </p>
    </div>
    <?php
}

/**
 * Simpan Data saat Postingan Editorial Diperbarui di Editor Gutenberg / Classic
 */
function mtsn1_save_editorial_meta_box( $post_id ) {
    if ( ! isset( $_POST['mtsn1_editorial_nonce'] ) || ! wp_verify_nonce( $_POST['mtsn1_editorial_nonce'], 'mtsn1_editorial_nonce_action' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['mtsn1_editorial_headline'] ) ) {
        $val = sanitize_text_field( wp_unslash( $_POST['mtsn1_editorial_headline'] ) );
        update_post_meta( $post_id, '_editorial_headline', $val );
    }
}
add_action( 'save_post_editorial', 'mtsn1_save_editorial_meta_box' );
