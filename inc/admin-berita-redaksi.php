<?php
/**
 * MTsN 1 Blitar - Pengaturan Kreditasi Dewan Redaksi Berita
 * 
 * Mengelola kredit Penulis (Pewarta), Editor (Redaktur), dan Dokumentasi Foto
 * untuk setiap artikel berita pada website MTsN 1 Blitar.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Helper: Mengambil Data Redaksi Berita (dengan fallback default cerdas)
 */
function mtsn1_get_berita_redaksi( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $penulis = get_post_meta( $post_id, '_mtsn1_berita_penulis', true );
    $editor  = get_post_meta( $post_id, '_mtsn1_berita_editor', true );
    $foto    = get_post_meta( $post_id, '_mtsn1_berita_foto', true );

    // Fallback Penulis: Ambil nama penulis/author asli postingan
    if ( empty( $penulis ) ) {
        $author_id = get_post_field( 'post_author', $post_id );
        if ( ! empty( $author_id ) ) {
            $penulis = get_the_author_meta( 'display_name', $author_id );
        }
        if ( empty( $penulis ) ) {
            $penulis = get_the_author();
        }
        if ( empty( $penulis ) ) {
            $penulis = 'Tim Humas MTsN 1 Blitar';
        }
    }

    // Fallback Editor
    if ( empty( $editor ) ) {
        $editor = 'Tim Humas MTsN 1 Blitar';
    }

    // Fallback Foto
    if ( empty( $foto ) ) {
        $foto = 'Tim Media MTsN 1 Blitar';
    }

    return array(
        'penulis' => $penulis,
        'editor'  => $editor,
        'foto'    => $foto,
    );
}

/**
 * 2. Registrasi Metabox Kreditasi Redaksi di Sidebar Editor Post
 */
add_action( 'add_meta_boxes', 'mtsn1_berita_redaksi_register_metabox' );
function mtsn1_berita_redaksi_register_metabox() {
    add_meta_box(
        'mtsn1_berita_redaksi_box',
        'Kredit Redaksi (Penulis, Editor & Foto)',
        'mtsn1_berita_redaksi_render_metabox',
        'post',
        'side',
        'default'
    );
}

/**
 * 3. Render Form Metabox di Layar Edit Berita
 */
function mtsn1_berita_redaksi_render_metabox( $post ) {
    wp_nonce_field( 'mtsn1_berita_redaksi_nonce_action', 'mtsn1_berita_redaksi_nonce' );

    $penulis = get_post_meta( $post->ID, '_mtsn1_berita_penulis', true );
    $editor  = get_post_meta( $post->ID, '_mtsn1_berita_editor', true );
    $foto    = get_post_meta( $post->ID, '_mtsn1_berita_foto', true );

    $author_name = get_the_author_meta( 'display_name', $post->post_author );
    ?>
    <div style="font-size: 12px; color: #334155;">
        <p style="margin: 0 0 10px 0; color: #64748b; font-size: 11px; line-height: 1.4;">
            Kelola nama peliputan berita yang tampil di kotak redaksi publikasi. Jika dikosongkan, nilai default otomatis digunakan.
        </p>

        <!-- Field Penulis -->
        <div style="margin-bottom: 12px;">
            <label for="mtsn1_berita_penulis" style="display: block; font-weight: 700; margin-bottom: 4px; color: #0f172a;">
                ✍️ Penulis / Pewarta:
            </label>
            <input type="text" id="mtsn1_berita_penulis" name="mtsn1_berita_penulis" 
                   value="<?php echo esc_attr( $penulis ); ?>" 
                   placeholder="Default: <?php echo esc_attr( $author_name ? $author_name : 'Tim Humas MTsN 1 Blitar' ); ?>" 
                   style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 5px 8px; font-size: 12px;" />
        </div>

        <!-- Field Editor -->
        <div style="margin-bottom: 12px;">
            <label for="mtsn1_berita_editor" style="display: block; font-weight: 700; margin-bottom: 4px; color: #0f172a;">
                🔍 Editor / Redaktur:
            </label>
            <input type="text" id="mtsn1_berita_editor" name="mtsn1_berita_editor" 
                   value="<?php echo esc_attr( $editor ); ?>" 
                   placeholder="Default: Tim Humas MTsN 1 Blitar" 
                   style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 5px 8px; font-size: 12px;" />
        </div>

        <!-- Field Dokumentasi Foto -->
        <div style="margin-bottom: 6px;">
            <label for="mtsn1_berita_foto" style="display: block; font-weight: 700; margin-bottom: 4px; color: #0f172a;">
                📷 Dokumentasi Foto:
            </label>
            <input type="text" id="mtsn1_berita_foto" name="mtsn1_berita_foto" 
                   value="<?php echo esc_attr( $foto ); ?>" 
                   placeholder="Default: Tim Media MTsN 1 Blitar" 
                   style="width: 100%; border: 1px solid #cbd5e1; border-radius: 6px; padding: 5px 8px; font-size: 12px;" />
        </div>
    </div>
    <?php
}

/**
 * 4. Simpan Data Metabox Kreditasi Redaksi
 */
add_action( 'save_post_post', 'mtsn1_berita_redaksi_save_metabox' );
function mtsn1_berita_redaksi_save_metabox( $post_id ) {
    // Verifikasi nonce
    if ( ! isset( $_POST['mtsn1_berita_redaksi_nonce'] ) || ! wp_verify_nonce( $_POST['mtsn1_berita_redaksi_nonce'], 'mtsn1_berita_redaksi_nonce_action' ) ) {
        return;
    }

    // Cegah autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Cek izin edit
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Simpan Penulis
    if ( isset( $_POST['mtsn1_berita_penulis'] ) ) {
        $penulis = sanitize_text_field( wp_unslash( $_POST['mtsn1_berita_penulis'] ) );
        if ( ! empty( $penulis ) ) {
            update_post_meta( $post_id, '_mtsn1_berita_penulis', $penulis );
        } else {
            delete_post_meta( $post_id, '_mtsn1_berita_penulis' );
        }
    }

    // Simpan Editor
    if ( isset( $_POST['mtsn1_berita_editor'] ) ) {
        $editor = sanitize_text_field( wp_unslash( $_POST['mtsn1_berita_editor'] ) );
        if ( ! empty( $editor ) ) {
            update_post_meta( $post_id, '_mtsn1_berita_editor', $editor );
        } else {
            delete_post_meta( $post_id, '_mtsn1_berita_editor' );
        }
    }

    // Simpan Foto
    if ( isset( $_POST['mtsn1_berita_foto'] ) ) {
        $foto = sanitize_text_field( wp_unslash( $_POST['mtsn1_berita_foto'] ) );
        if ( ! empty( $foto ) ) {
            update_post_meta( $post_id, '_mtsn1_berita_foto', $foto );
        } else {
            delete_post_meta( $post_id, '_mtsn1_berita_foto' );
        }
    }
}
