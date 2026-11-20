<?php
/**
 * Pengaturan Judul & Foto Khusus Sambutan Kepala Madrasah (Post Type Editorial)
 * Memisahkan secara mandiri:
 * 1. Foto Potret Kepala Madrasah (Tampilan Depan / Beranda)
 * 2. Banner Ilustrasi Lanskap di Dalam Artikel Sambutan Lengkap
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Pastikan post type editorial & pengumuman & agenda mendukung thumbnail & excerpt & editor
add_action( 'init', function() {
    $types = array( 'editorial', 'pengumuman', 'agenda' );
    foreach ( $types as $t ) {
        add_post_type_support( $t, 'thumbnail' );
        add_post_type_support( $t, 'excerpt' );
        add_post_type_support( $t, 'editor' );
    }
}, 20 );

/**
 * Enqueue Media Scripts untuk halaman admin editorial
 */
add_action( 'admin_enqueue_scripts', function( $hook ) {
    global $post_type;
    if ( $post_type === 'editorial' || ( isset( $_GET['page'] ) && $_GET['page'] === 'editorial-headline-settings' ) ) {
        wp_enqueue_media();
    }
} );

/**
 * 1. TAMBAHKAN MENU KHUSUS DI SIDEBAR WP-ADMIN:
 *    Editorial -> ⭐ Judul & Foto Sambutan
 */
add_action( 'admin_menu', 'mtsn1_register_editorial_settings_submenu' );
function mtsn1_register_editorial_settings_submenu() {
    add_submenu_page(
        'edit.php?post_type=editorial',
        'Edit Judul & Foto Sambutan',
        '⭐ Judul & Foto Sambutan',
        'edit_posts',
        'editorial-headline-settings',
        'mtsn1_render_editorial_settings_page'
    );
}

/**
 * Halaman Pengaturan Khusus yang Sangat Mudah Diakses
 */
function mtsn1_render_editorial_settings_page() {
    // Cari postingan editorial aktif
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
            $saved = true;
        }

        if ( isset( $_POST['foto_kepala_depan'] ) ) {
            $new_foto_depan = esc_url_raw( trim( wp_unslash( $_POST['foto_kepala_depan'] ) ) );
            update_post_meta( $post_id, '_foto_kepala_depan', $new_foto_depan );
            $saved = true;
        }

        if ( isset( $_POST['editorial_inside_banner'] ) ) {
            $new_inside_banner = esc_url_raw( trim( wp_unslash( $_POST['editorial_inside_banner'] ) ) );
            update_post_meta( $post_id, '_editorial_inside_banner', $new_inside_banner );
            $saved = true;
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

    $current_foto_depan = get_post_meta( $post_id, '_foto_kepala_depan', true );
    if ( empty( $current_foto_depan ) ) {
        $current_foto_depan = get_template_directory_uri() . '/assets/img/kepala-madrasah.webp';
    }

    $current_inside_banner = get_post_meta( $post_id, '_editorial_inside_banner', true );
    ?>
    <div class="wrap" style="max-width: 950px; margin-top: 25px;">
        <h1 style="font-weight: 900; font-size: 24px; color: #0f172a; margin-bottom: 8px;">
            <span style="color: #059669;">⭐</span> Edit Judul &amp; Foto Sambutan Kepala Madrasah
        </h1>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 25px;">
            Halaman ini memisahkan secara mandiri antara <strong>Foto Potret di Halaman Depan</strong> dan <strong>Banner Gambar di Dalam Artikel Sambutan</strong> agar foto tidak terpotong.
        </p>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible" style="padding: 12px 16px; border-radius: 8px; border-left-color: #059669; font-weight: bold; font-size: 13px;">
                ✓ Berhasil Disimpan! Pengaturan judul dan foto sambutan telah diperbarui. <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" style="text-decoration: underline; margin-left: 8px;">Lihat Beranda &rarr;</a> | <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" target="_blank" style="text-decoration: underline; margin-left: 6px;">Lihat Halaman Sambutan Lengkap &rarr;</a>
            </div>
        <?php endif; ?>

        <div style="background: #ffffff; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <form method="post" action="">
                <?php wp_nonce_field( 'mtsn1_save_headline_action', 'mtsn1_save_headline_nonce' ); ?>

                <!-- 1. Foto Potret Beranda (Tampilan Depan) -->
                <div style="margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid #f1f5f9;">
                    <label style="display: block; font-weight: 800; font-size: 14px; color: #0f172a; margin-bottom: 6px;">
                        📸 1. Foto Profil Kepala Madrasah (Khusus Tampilan Depan / Beranda):
                    </label>
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 12px;">
                        Gunakan foto potret (tegak) berbusana resmi jas &amp; peci. Foto ini tampil proporsional di sisi kiri sambutan halaman beranda.
                    </p>
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div style="width: 110px; height: 140px; border-radius: 12px; overflow: hidden; background: #0f172a; border: 2px solid #e2e8f0; flex-shrink: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <img id="preview_foto_depan" src="<?php echo esc_url( $current_foto_depan ); ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                        </div>
                        <div style="flex: 1;">
                            <input type="text" id="foto_kepala_depan" name="foto_kepala_depan" value="<?php echo esc_attr( $current_foto_depan ); ?>" style="width: 100%; padding: 10px 14px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; margin-bottom: 8px;">
                            <button type="button" class="button button-secondary" onclick="mtsn1UploadMedia('foto_kepala_depan', 'preview_foto_depan')">
                                🖼️ Pilih / Upload Foto dari Media Library
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Banner di Dalam Halaman Sambutan Lengkap -->
                <div style="margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid #f1f5f9;">
                    <label style="display: block; font-weight: 800; font-size: 14px; color: #0f172a; margin-bottom: 6px;">
                        🖼️ 2. Banner Gambar di Dalam Halaman Sambutan Lengkap (Opsional):
                    </label>
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 12px;">
                        Banner lanskap melebar (misal: 1400x600 px berupa foto kegiatan madrasah atau gedung). Jika dikosongkan, halaman sambutan akan menampilkan foto potret resmi Kepala Madrasah di samping teks secara elegan tanpa banner terpotong.
                    </p>
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <div style="width: 160px; height: 90px; border-radius: 12px; overflow: hidden; background: #f8fafc; border: 2px dashed #cbd5e1; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            <img id="preview_inside_banner" src="<?php echo esc_url( $current_inside_banner ); ?>" style="width: 100%; height: 100%; object-fit: cover; display: <?php echo ! empty( $current_inside_banner ) ? 'block' : 'none'; ?>;">
                            <span id="placeholder_inside_banner" style="font-size: 11px; color: #94a3b8; display: <?php echo empty( $current_inside_banner ) ? 'block' : 'none'; ?>;">(Tanpa Banner)</span>
                        </div>
                        <div style="flex: 1;">
                            <input type="text" id="editorial_inside_banner" name="editorial_inside_banner" value="<?php echo esc_attr( $current_inside_banner ); ?>" placeholder="https://... (Kosongkan jika tidak memakai banner lanskap)" style="width: 100%; padding: 10px 14px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 8px; margin-bottom: 8px;">
                            <div style="display: flex; gap: 8px;">
                                <button type="button" class="button button-secondary" onclick="mtsn1UploadMedia('editorial_inside_banner', 'preview_inside_banner', 'placeholder_inside_banner')">
                                    🖼️ Pilih / Upload Banner Lanskap
                                </button>
                                <button type="button" class="button" onclick="document.getElementById('editorial_inside_banner').value=''; document.getElementById('preview_inside_banner').style.display='none'; document.getElementById('placeholder_inside_banner').style.display='block';">
                                    Hapus Banner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Judul Kutipan Besar -->
                <div style="margin-bottom: 24px;">
                    <label for="editorial_headline" style="display: block; font-weight: 800; font-size: 14px; color: #0f172a; margin-bottom: 8px;">
                        💬 3. Teks Judul Kutipan Besar (Headline Sambutan di Beranda):
                    </label>
                    <input type="text" 
                           id="editorial_headline" 
                           name="editorial_headline" 
                           value="<?php echo esc_attr( $current_headline ); ?>" 
                           style="width: 100%; padding: 12px 16px; font-size: 15px; font-weight: 700; color: #0f172a; border: 2px solid #cbd5e1; border-radius: 10px; box-sizing: border-box;" 
                           placeholder="Contoh: Mendidik Generasi Beradab, Unggul dalam Prestasi, dan Cakap Digital" 
                           required />
                </div>

                <!-- 4. Badge Atas -->
                <div style="margin-bottom: 28px;">
                    <label for="editorial_badge_title" style="display: block; font-weight: 800; font-size: 14px; color: #0f172a; margin-bottom: 8px;">
                        🏷️ 4. Teks Label Badge di Atas Judul:
                    </label>
                    <input type="text" 
                           id="editorial_badge_title" 
                           name="editorial_badge_title" 
                           value="<?php echo esc_attr( $current_badge ); ?>" 
                           style="width: 100%; padding: 10px 14px; font-size: 13px; font-weight: 600; color: #334155; border: 1px solid #cbd5e1; border-radius: 10px; box-sizing: border-box;" 
                           placeholder="Contoh: SEKAPUR SIRIH KEPALA MADRASAH" 
                           required />
                </div>

                <!-- Tombol Simpan -->
                <div style="display: flex; align-items: center; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="button button-primary button-large" style="background: #059669; border-color: #047857; font-weight: 800; font-size: 14px; padding: 6px 24px; border-radius: 8px; height: auto;">
                        💾 Simpan Perubahan
                    </button>
                    <a href="<?php echo esc_url( get_edit_post_link( $post_id ) ); ?>" class="button button-secondary" style="font-size: 13px; padding: 6px 16px; border-radius: 8px; height: auto;">
                        📝 Buka Editor Teks Sambutan Lengkap
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
    function mtsn1UploadMedia(inputId, previewId, placeholderId) {
        var frame = wp.media({
            title: 'Pilih atau Upload Gambar',
            button: { text: 'Gunakan Gambar Ini' },
            multiple: false
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            document.getElementById(inputId).value = attachment.url;
            var preview = document.getElementById(previewId);
            if (preview) {
                preview.src = attachment.url;
                preview.style.display = 'block';
            }
            if (placeholderId) {
                var ph = document.getElementById(placeholderId);
                if (ph) ph.style.display = 'none';
            }
        });
        frame.open();
    }
    </script>
    <?php
}

/**
 * 2. TAMBAHKAN META BOX PADA EDITOR POSTINGAN EDITORIAL:
 */
function mtsn1_add_editorial_meta_box() {
    add_meta_box(
        'mtsn1_editorial_headline_box',
        '⚙️ Pengaturan Foto & Headline Sambutan (Beranda vs Di Dalam)',
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

    $foto_depan = get_post_meta( $post->ID, '_foto_kepala_depan', true );
    if ( empty( $foto_depan ) ) {
        $foto_depan = get_template_directory_uri() . '/assets/img/kepala-madrasah.webp';
    }

    $inside_banner = get_post_meta( $post->ID, '_editorial_inside_banner', true );
    ?>
    <div style="padding: 12px 0;">
        <div style="margin-bottom: 20px;">
            <label for="mtsn1_editorial_headline" style="display: block; font-weight: 700; font-size: 13px; margin-bottom: 6px; color: #1e293b;">
                Teks Judul Kutipan Besar (Headline di Beranda):
            </label>
            <input type="text" 
                   id="mtsn1_editorial_headline" 
                   name="mtsn1_editorial_headline" 
                   value="<?php echo esc_attr( $headline ); ?>" 
                   style="width: 100%; padding: 10px 14px; font-size: 14px; font-weight: 600; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box;" />
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <!-- Foto Depan -->
            <div>
                <label style="display: block; font-weight: 700; font-size: 13px; margin-bottom: 4px; color: #0f172a;">
                    📸 Foto Potret Kepala Madrasah (Tampilan Depan):
                </label>
                <p style="font-size: 11px; color: #64748b; margin-bottom: 8px;">
                    Foto potret resmi di sisi kiri halaman depan beranda.
                </p>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <img id="meta_preview_foto_depan" src="<?php echo esc_url( $foto_depan ); ?>" style="width: 60px; height: 80px; object-fit: cover; object-position: top; border-radius: 8px; border: 1px solid #cbd5e1; background: #0f172a;">
                    <div style="flex: 1;">
                        <input type="text" id="mtsn1_foto_depan" name="mtsn1_foto_depan" value="<?php echo esc_attr( $foto_depan ); ?>" style="width: 100%; font-size: 11px; margin-bottom: 4px;">
                        <button type="button" class="button button-small" onclick="mtsn1UploadMediaMeta('mtsn1_foto_depan', 'meta_preview_foto_depan')">Pilih Foto</button>
                    </div>
                </div>
            </div>

            <!-- Banner Dalam -->
            <div>
                <label style="display: block; font-weight: 700; font-size: 13px; margin-bottom: 4px; color: #0f172a;">
                    🖼️ Banner Gambar di Dalam Halaman (Opsional):
                </label>
                <p style="font-size: 11px; color: #64748b; margin-bottom: 8px;">
                    Banner lanskap di halaman sambutan lengkap. Kosongkan jika tanpa banner.
                </p>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <img id="meta_preview_banner_dalam" src="<?php echo esc_url( $inside_banner ); ?>" style="width: 80px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; display: <?php echo ! empty( $inside_banner ) ? 'block' : 'none'; ?>;">
                    <div style="flex: 1;">
                        <input type="text" id="mtsn1_inside_banner" name="mtsn1_inside_banner" value="<?php echo esc_attr( $inside_banner ); ?>" placeholder="(Opsional)" style="width: 100%; font-size: 11px; margin-bottom: 4px;">
                        <button type="button" class="button button-small" onclick="mtsn1UploadMediaMeta('mtsn1_inside_banner', 'meta_preview_banner_dalam')">Pilih Banner</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function mtsn1UploadMediaMeta(inputId, previewId) {
        var frame = wp.media({
            title: 'Pilih Gambar',
            button: { text: 'Gunakan Gambar' },
            multiple: false
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            document.getElementById(inputId).value = attachment.url;
            var preview = document.getElementById(previewId);
            if (preview) {
                preview.src = attachment.url;
                preview.style.display = 'block';
            }
        });
        frame.open();
    }
    </script>
    <?php
}

/**
 * Simpan Data saat Postingan Editorial Diperbarui
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
        update_post_meta( $post_id, '_editorial_headline', sanitize_text_field( wp_unslash( $_POST['mtsn1_editorial_headline'] ) ) );
    }

    if ( isset( $_POST['mtsn1_foto_depan'] ) ) {
        update_post_meta( $post_id, '_foto_kepala_depan', esc_url_raw( trim( wp_unslash( $_POST['mtsn1_foto_depan'] ) ) ) );
    }

    if ( isset( $_POST['mtsn1_inside_banner'] ) ) {
        update_post_meta( $post_id, '_editorial_inside_banner', esc_url_raw( trim( wp_unslash( $_POST['mtsn1_inside_banner'] ) ) ) );
    }
}
add_action( 'save_post_editorial', 'mtsn1_save_editorial_meta_box' );
