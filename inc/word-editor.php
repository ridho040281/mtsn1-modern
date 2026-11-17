<?php
/**
 * MTsN 1 Blitar - Microsoft Word Style Editor for Articles
 *
 * Mengubah halaman editor artikel/berita menjadi Word Editor lengkap
 * dengan menu layout teks (Font, Ukuran, Rata Kiri-Kanan/Justify, Tabel,
 * Warna Teks, Highlight, Indent, Line Height, Simbol, dan Kertas Dokumen Word).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Tentukan apakah menggunakan Word Editor (Classic TinyMCE) atau Block Editor
 */
add_filter( 'use_block_editor_for_post', 'mtsn1_word_editor_toggle_editor', 20, 2 );
function mtsn1_word_editor_toggle_editor( $use_block_editor, $post ) {
    if ( ! $post ) {
        return $use_block_editor;
    }

    // Override langsung melalui parameter URL: ?editor=classic atau ?editor=block
    if ( isset( $_GET['editor'] ) ) {
        if ( $_GET['editor'] === 'classic' || $_GET['editor'] === 'word' ) {
            return false;
        }
        if ( $_GET['editor'] === 'block' || $_GET['editor'] === 'gutenberg' ) {
            return true;
        }
    }
    if ( isset( $_GET['classic-editor'] ) ) {
        return false;
    }

    // Untuk postingan artikel/berita ('post'): Default ke Word Editor
    if ( $post->post_type === 'post' ) {
        $disabled = get_option( 'mtsn1_disable_word_editor', false );
        if ( $disabled ) {
            return true;
        }
        return false; // Aktifkan Word Editor (Classic WYSIWYG)
    }

    return $use_block_editor;
}

/**
 * 2. Daftarkan Plugin Eksternal TinyMCE (Table & Search-Replace)
 */
add_filter( 'mce_external_plugins', 'mtsn1_word_editor_register_plugins', 99 );
function mtsn1_word_editor_register_plugins( $plugins ) {
    $theme_uri = get_template_directory_uri();

    if ( ! isset( $plugins['table'] ) ) {
        $table_path = get_template_directory() . '/assets/tinymce/table/plugin.min.js';
        if ( file_exists( $table_path ) ) {
            $plugins['table'] = $theme_uri . '/assets/tinymce/table/plugin.min.js';
        }
    }

    if ( ! isset( $plugins['searchreplace'] ) ) {
        $sr_path = get_template_directory() . '/assets/tinymce/searchreplace/plugin.min.js';
        if ( file_exists( $sr_path ) ) {
            $plugins['searchreplace'] = $theme_uri . '/assets/tinymce/searchreplace/plugin.min.js';
        }
    }

    return $plugins;
}

/**
 * 3. Konfigurasi Toolbar Baris 1 TinyMCE (Font, Ukuran, Format Teks & Warna)
 */
add_filter( 'mce_buttons', 'mtsn1_word_editor_buttons_row_1', 9999, 2 );
function mtsn1_word_editor_buttons_row_1( $buttons, $editor_id = 'content' ) {
    return array(
        'fontselect',       // Pilihan Font (Calibri, Arial, Times New Roman, dll.)
        'fontsizeselect',   // Ukuran Font (8pt - 72pt)
        'formatselect',     // Paragraf, Heading 1 - 6
        'bold',             // Tebal
        'italic',           // Miring
        'underline',        // Garis Bawah
        'strikethrough',    // Coret Teks
        'forecolor',        // Warna Teks
        'backcolor',        // Warna Sorotan / Highlight Teks ala Word
        'removeformat',     // Hapus Format
    );
}

/**
 * 4. Konfigurasi Toolbar Baris 2 TinyMCE (Layout Paragraf, Perataan & Indent)
 */
add_filter( 'mce_buttons_2', 'mtsn1_word_editor_buttons_row_2', 9999, 2 );
function mtsn1_word_editor_buttons_row_2( $buttons, $editor_id = 'content' ) {
    return array(
        'alignleft',        // Rata Kiri
        'aligncenter',      // Rata Tengah
        'alignright',       // Rata Kanan
        'alignjustify',     // RATA KIRI-KANAN (JUSTIFY)
        'bullist',          // Daftar Poin (Bullets)
        'numlist',          // Daftar Angka (Numbering)
        'outdent',          // Kurangi Inden (Geser Kiri)
        'indent',           // Tambah Inden (Geser Kanan)
        'blockquote',       // Kutipan
        'hr',               // Garis Pembatas Horizontal
        'wp_more',          // Tag Baca Selengkapnya
        'wp_page',          // Pisah Halaman
    );
}

/**
 * 5. Konfigurasi Toolbar Baris 3 TinyMCE (Tabel ala Word, Link & Alat Tambahan)
 */
add_filter( 'mce_buttons_3', 'mtsn1_word_editor_buttons_row_3', 9999, 2 );
function mtsn1_word_editor_buttons_row_3( $buttons, $editor_id = 'content' ) {
    return array(
        'table',            // MENU & TABEL LENGKAP (Insert Table, Kolom, Baris, Sel)
        'link',             // Sisipkan Tautan
        'unlink',           // Hapus Tautan
        'subscript',        // Teks Bawah (Subscript)
        'superscript',      // Teks Atas (Superscript)
        'charmap',          // Karakter Khusus / Simbol
        'searchreplace',    // Cari & Ganti Kata
        'undo',             // Batalkan (Undo)
        'redo',             // Ulangi (Redo)
        'fullscreen',       // Mode Menulis Layar Penuh
    );
}

/**
 * 6. Aktifkan Menu Bar Dokumen, Pilihan Font, Ukuran Font & Styling Kertas Dokumen Word
 */
add_filter( 'tiny_mce_before_init', 'mtsn1_word_editor_tinymce_init', 9999 );
function mtsn1_word_editor_tinymce_init( $init ) {
    // 1. Tampilkan Menubar Lengkap ala Microsoft Word
    $init['menubar'] = 'file edit insert view format table tools';

    // 2. Koleksi Font Populer Microsoft Word & Standar Dokumen
    $init['font_formats'] = 'Calibri=calibri,candara,segoe,segoe ui,optima,arial,sans-serif;' .
                            'Times New Roman=times new roman,times,serif;' .
                            'Arial=arial,helvetica,sans-serif;' .
                            'Segoe UI=segoe ui,arial,sans-serif;' .
                            'Georgia=georgia,palatino,serif;' .
                            'Tahoma=tahoma,arial,helvetica,sans-serif;' .
                            'Trebuchet MS=trebuchet ms,geneva,sans-serif;' .
                            'Verdana=verdana,geneva,sans-serif;' .
                            'Courier New=courier new,courier,monospace;' .
                            'Comic Sans MS=comic sans ms,sans-serif;';

    // 3. Pilihan Ukuran Font Lengkap
    $init['fontsize_formats'] = '8pt 9pt 10pt 11pt 12pt 13pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt 72pt';

    // 4. Pengaturan Grid Warna
    $init['textcolor_rows'] = 6;
    $init['textcolor_cols'] = 8;

    // 5. Gaya Lembar Kertas Dokumen Word di Dalam Iframe Editor
    $word_canvas_css = '
        html {
            background-color: #f1f5f9 !important;
            padding: 24px 12px !important;
            box-sizing: border-box !important;
        }
        body#tinymce {
            font-family: "Calibri", "Segoe UI", Arial, sans-serif !important;
            font-size: 15px !important;
            line-height: 1.75 !important;
            color: #1e293b !important;
            background-color: #ffffff !important;
            max-width: 840px !important;
            margin: 0 auto !important;
            padding: 45px 55px !important;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(15, 23, 42, 0.08) !important;
            border-radius: 4px !important;
            min-height: 850px !important;
            box-sizing: border-box !important;
        }
        p {
            margin-top: 0 !important;
            margin-bottom: 1.3em !important;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #0f172a !important;
            font-family: "Segoe UI", "Calibri", Arial, sans-serif !important;
            font-weight: 700 !important;
            margin-top: 1.5em !important;
            margin-bottom: 0.6em !important;
            line-height: 1.3 !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
            margin: 1.5em 0 !important;
        }
        table td, table th {
            border: 1px solid #94a3b8 !important;
            padding: 8px 12px !important;
        }
        table th {
            background-color: #f8fafc !important;
            font-weight: 700 !important;
            color: #0f172a !important;
        }
        blockquote {
            border-left: 4px solid #10b981 !important;
            padding: 8px 18px !important;
            margin: 1.5em 0 !important;
            background-color: #f8fafc !important;
            color: #334155 !important;
            font-style: italic !important;
            border-radius: 0 8px 8px 0 !important;
        }
        img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 6px !important;
        }
    ';

    if ( ! empty( $init['content_style'] ) ) {
        $init['content_style'] .= ' ' . $word_canvas_css;
    } else {
        $init['content_style'] = $word_canvas_css;
    }

    return $init;
}

/**
 * 7. Styling Admin: Banner Mode Word Editor & Tampilan Ribbon Word yang Elegan
 */
add_action( 'admin_head-post.php', 'mtsn1_word_editor_admin_styles' );
add_action( 'admin_head-post-new.php', 'mtsn1_word_editor_admin_styles' );
function mtsn1_word_editor_admin_styles() {
    global $post;
    if ( ! $post || $post->post_type !== 'post' ) {
        return;
    }

    $is_block = use_block_editor_for_post( $post );
    if ( $is_block ) {
        return;
    }
    ?>
    <style>
        /* Toolbar TinyMCE Ala Microsoft Word Ribbon */
        #wp-content-editor-tools {
            background: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-bottom: none !important;
            border-radius: 8px 8px 0 0 !important;
            padding: 8px 12px 6px !important;
        }
        .wp-editor-tabs .wp-switch-editor {
            border-radius: 6px 6px 0 0 !important;
            font-weight: 600 !important;
            font-size: 13px !important;
        }
        .mce-toolbar-grp {
            background: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            border-top: none !important;
            padding: 6px 8px !important;
        }
        .mce-menubar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 4px 6px !important;
        }
        .mce-menubar .mce-menubtn {
            margin-right: 4px !important;
        }
        .mce-menubar .mce-menubtn span {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #334155 !important;
        }
        .mce-menubar .mce-menubtn:hover,
        .mce-menubar .mce-menubtn.mce-active {
            background: #e2e8f0 !important;
            border-radius: 4px !important;
        }
        .mce-btn {
            border-radius: 4px !important;
            margin: 2px 1px !important;
        }
        .mce-btn:hover,
        .mce-btn.mce-active {
            background: #e2e8f0 !important;
            border-color: #cbd5e1 !important;
        }
        .mce-toolbar .mce-btn-group {
            padding: 0 4px 0 0 !important;
            margin-right: 6px !important;
            border-right: 1px solid #cbd5e1 !important;
        }
        .mce-toolbar .mce-btn-group:last-child {
            border-right: none !important;
        }
        /* Frame Editor Kontainer */
        #wp-content-wrap {
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06) !important;
            border-radius: 8px !important;
            overflow: hidden !important;
        }
        #content_ifr {
            background: #f1f5f9 !important;
        }
        /* Top Banner Word Editor Notice */
        .mtsn1-word-banner {
            background: linear-gradient(135deg, #064e3b 0%, #047857 100%);
            color: #ffffff;
            border-radius: 12px;
            padding: 12px 18px;
            margin: 15px 0 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(6, 78, 59, 0.15);
        }
        .mtsn1-word-banner .banner-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .mtsn1-word-banner .banner-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .mtsn1-word-banner .banner-title {
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -0.01em;
            margin: 0;
            line-height: 1.2;
        }
        .mtsn1-word-banner .banner-desc {
            font-size: 12px;
            opacity: 0.9;
            margin: 2px 0 0 0;
            line-height: 1.3;
        }
        .mtsn1-word-banner .banner-switch-btn {
            background: rgba(255, 255, 255, 0.95);
            color: #064e3b;
            font-size: 12px;
            font-weight: 700;
            padding: 7px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
        }
        .mtsn1-word-banner .banner-switch-btn:hover {
            background: #ffffff;
            transform: translateY(-1px);
            color: #047857;
        }
    </style>
    <?php
}

/**
 * 8. Tampilkan Banner Info Word Editor di Atas Halaman Edit Artikel
 */
add_action( 'edit_form_after_title', 'mtsn1_word_editor_render_banner' );
function mtsn1_word_editor_render_banner( $post ) {
    if ( ! $post || $post->post_type !== 'post' ) {
        return;
    }

    $is_block = use_block_editor_for_post( $post );
    if ( $is_block ) {
        return;
    }

    $block_url = add_query_arg( 'editor', 'block', get_edit_post_link( $post->ID, 'url' ) );
    ?>
    <div class="mtsn1-word-banner">
        <div class="banner-left">
            <div class="banner-icon">
                <span class="dashicons dashicons-media-document" style="font-size: 22px; width: 22px; height: 22px; line-height: 1;"></span>
            </div>
            <div>
                <p class="banner-title">Mode Microsoft Word Editor Aktif</p>
                <p class="banner-desc">Dilengkapi menu File/Edit/Format/Tabel, font, ukuran teks, perataan kiri-kanan (justify), tabel, highlight warna, dan tampilan lembar dokumen A4.</p>
            </div>
        </div>
        <div>
            <a href="<?php echo esc_url( $block_url ); ?>" class="banner-switch-btn">
                Beralih ke Block Editor &rarr;
            </a>
        </div>
    </div>
    <?php
}

/**
 * 9. Tampilkan Tombol Aksi di Daftar Artikel (Word Editor vs Block Editor)
 */
add_filter( 'post_row_actions', 'mtsn1_word_editor_add_row_actions', 10, 2 );
function mtsn1_word_editor_add_row_actions( $actions, $post ) {
    if ( $post->post_type === 'post' ) {
        $word_url  = admin_url( 'post.php?post=' . $post->ID . '&action=edit&editor=classic' );
        $block_url = admin_url( 'post.php?post=' . $post->ID . '&action=edit&editor=block' );

        $new_actions = array();
        foreach ( $actions as $key => $link ) {
            if ( $key === 'edit' ) {
                $new_actions['edit_word']  = '<a href="' . esc_url( $word_url ) . '" style="color:#047857; font-weight:700;"><span class="dashicons dashicons-media-document" style="font-size:14px; width:14px; height:14px; vertical-align:middle; margin-right:2px;"></span> Word Editor</a>';
                $new_actions['edit_block'] = '<a href="' . esc_url( $block_url ) . '" style="color:#64748b;">Block Editor</a>';
            } else {
                $new_actions[ $key ] = $link;
            }
        }
        return $new_actions;
    }
    return $actions;
}

/**
 * 10. Metabox Pengalih Mode Editor di Sidebar (Word Editor vs Gutenberg)
 */
add_action( 'add_meta_boxes', 'mtsn1_word_editor_register_metabox' );
function mtsn1_word_editor_register_metabox() {
    add_meta_box(
        'mtsn1_editor_mode_box',
        'Pilihan Mode Editor',
        'mtsn1_word_editor_render_metabox',
        'post',
        'side',
        'high'
    );
}

function mtsn1_word_editor_render_metabox( $post ) {
    $is_block = use_block_editor_for_post( $post );
    if ( $is_block ) {
        $word_url = add_query_arg( 'editor', 'classic', get_edit_post_link( $post->ID, 'url' ) );
        ?>
        <div style="padding: 6px 0;">
            <p style="margin: 0 0 10px 0; font-size: 13px; color: #475569; line-height: 1.4;">
                Saat ini aktif: <strong>Block Editor (Gutenberg)</strong>.
            </p>
            <a href="<?php echo esc_url( $word_url ); ?>" class="button button-primary" style="background: #047857; border-color: #064e3b; display: block; text-align: center; font-weight: 700; padding: 6px 12px; height: auto;">
                <span class="dashicons dashicons-media-document" style="vertical-align: middle; margin-right: 4px;"></span> Beralih ke Word Editor
            </a>
            <p style="margin: 8px 0 0 0; font-size: 11px; color: #64748b; line-height: 1.3;">
                Gunakan Word Editor untuk menulis berita dengan menu font, ukuran, justify (rata kiri-kanan), dan tabel ala Microsoft Word.
            </p>
        </div>
        <?php
    } else {
        $block_url = add_query_arg( 'editor', 'block', get_edit_post_link( $post->ID, 'url' ) );
        ?>
        <div style="padding: 6px 0;">
            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 8px; color: #047857; font-weight: 700; font-size: 13px;">
                <span class="dashicons dashicons-yes-alt" style="color: #059669;"></span>
                <span>Mode Word Editor Aktif</span>
            </div>
            <p style="margin: 0 0 12px 0; font-size: 12px; color: #475569; line-height: 1.4;">
                Menu layout teks, ukuran font, perataan justify, warna sorotan, dan tabel telah aktif.
            </p>
            <a href="<?php echo esc_url( $block_url ); ?>" class="button" style="display: block; text-align: center; font-size: 12px;">
                Beralih ke Block Editor
            </a>
        </div>
        <?php
    }
}
