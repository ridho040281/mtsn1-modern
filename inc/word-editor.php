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
 * Helper: Daftar post type yang menggunakan Word Editor
 */
function mtsn1_word_editor_supported_post_types() {
    return apply_filters( 'mtsn1_word_editor_post_types', array( 'post', 'editorial', 'blog' ) );
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

    // Untuk post type yang didukung (post, editorial, blog): Default ke Word Editor
    $supported = mtsn1_word_editor_supported_post_types();
    if ( in_array( $post->post_type, $supported, true ) ) {
        $disabled = get_option( 'mtsn1_disable_word_editor', false );
        if ( $disabled ) {
            return true;
        }
        return false; // Aktifkan Word Editor (Classic WYSIWYG)
    }

    return $use_block_editor;
}

add_filter( 'use_block_editor_for_post_type', 'mtsn1_word_editor_toggle_by_post_type', 20, 2 );
function mtsn1_word_editor_toggle_by_post_type( $use_block_editor, $post_type ) {
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

    $supported = mtsn1_word_editor_supported_post_types();
    if ( in_array( $post_type, $supported, true ) ) {
        $disabled = get_option( 'mtsn1_disable_word_editor', false );
        if ( $disabled ) {
            return true;
        }
        return false;
    }

    return $use_block_editor;
}

/**
 * 2. Daftarkan Plugin Eksternal TinyMCE (Table, Search-Replace, Code, dll.)
 */
add_filter( 'mce_external_plugins', 'mtsn1_word_editor_register_plugins', 9999 );
function mtsn1_word_editor_register_plugins( $plugins ) {
    $theme_dir = get_template_directory() . '/assets/tinymce/';
    $theme_uri = get_template_directory_uri() . '/assets/tinymce/';

    $needed = array(
        'table'          => 'table/plugin.min.js',
        'searchreplace'  => 'searchreplace/plugin.min.js',
        'code'           => 'code/plugin.min.js',
        'insertdatetime' => 'insertdatetime/plugin.min.js',
        'nonbreaking'    => 'nonbreaking/plugin.min.js',
        'print'          => 'print/plugin.min.js',
        'visualblocks'   => 'visualblocks/plugin.min.js',
        'visualchars'    => 'visualchars/plugin.min.js',
        'advlist'        => 'advlist/plugin.min.js',
        'wptadv'         => 'wptadv/plugin.min.js',
        'contextmenu'    => 'contextmenu/plugin.min.js',
    );

    foreach ( $needed as $name => $rel ) {
        if ( ! isset( $plugins[ $name ] ) && file_exists( $theme_dir . $rel ) ) {
            $plugins[ $name ] = $theme_uri . $rel;
        }
    }

    // Plugin Tata Letak & Pengaturan Gambar ala Microsoft Word
    $plugins['mtsn1_word_img'] = $theme_uri . 'mtsn1_word_img/plugin.js';

    return $plugins;
}

/**
 * 3. Konfigurasi Toolbar TinyMCE Berderet Kompak (1 Baris Mengalir Rapi)
 * Termasuk tombol "Tambah Media" & "Tata Letak Gambar" langsung di toolbar.
 */
add_filter( 'mce_buttons', 'mtsn1_word_editor_buttons_row_1', 9999, 2 );
function mtsn1_word_editor_buttons_row_1( $buttons, $editor_id = 'content' ) {
    return array(
        'mtsn1_add_media',     // Tombol Tambah Media Langsung di Baris Toolbar
        'mtsn1_image_layout',  // TOMBOL TATA LETAK & WRAP TEXT GAMBAR ALA MICROSOFT WORD
        'fontselect',          // Pilihan Font (Calibri, Arial, Times New Roman, dll.)
        'fontsizeselect',      // Ukuran Font (8pt - 72pt)
        'formatselect',        // Paragraf, Heading 1 - 6
        'bold',                // Tebal
        'italic',              // Miring
        'underline',           // Garis Bawah
        'strikethrough',       // Coret Teks
        'forecolor',           // Warna Teks
        'backcolor',           // Warna Sorotan / Highlight Teks ala Word
        'alignleft',           // Rata Kiri
        'aligncenter',         // Rata Tengah
        'alignright',          // Rata Kanan
        'alignjustify',        // RATA KIRI-KANAN (JUSTIFY)
        'bullist',             // Daftar Poin (Bullets)
        'numlist',             // Daftar Angka (Numbering)
        'outdent',             // Kurangi Inden (Geser Kiri)
        'indent',              // Tambah Inden (Geser Kanan)
        'blockquote',          // Kutipan
        'hr',                  // Garis Pembatas Horizontal
        'table',               // MENU & TABEL LENGKAP (Insert Table, Kolom, Baris, Sel)
        'link',                // Sisipkan Tautan
        'unlink',              // Hapus Tautan
        'charmap',             // Karakter Khusus / Simbol
        'subscript',           // Teks Bawah (Subscript)
        'superscript',         // Teks Atas (Superscript)
        'searchreplace',       // Cari & Ganti Kata
        'removeformat',        // Hapus Format
        'undo',                // Batalkan (Undo)
        'redo',                // Ulangi (Redo)
        'fullscreen',          // Mode Menulis Layar Penuh
    );
}

/**
 * 4. Kosongkan Baris 2 & 3 agar Semua Ikon Berderet di Baris Utama (Tidak Makan Tempat)
 */
add_filter( 'mce_buttons_2', 'mtsn1_word_editor_buttons_row_2', 9999, 2 );
function mtsn1_word_editor_buttons_row_2( $buttons, $editor_id = 'content' ) {
    return array();
}

add_filter( 'mce_buttons_3', 'mtsn1_word_editor_buttons_row_3', 9999, 2 );
function mtsn1_word_editor_buttons_row_3( $buttons, $editor_id = 'content' ) {
    return array();
}

/**
 * 5. Aktifkan Menu Bar Dokumen, Pilihan Font, Ukuran Font & Matikan Autoresize yang Bikin Tembus
 */
add_filter( 'tiny_mce_before_init', 'mtsn1_word_editor_tinymce_init', 9999 );
function mtsn1_word_editor_tinymce_init( $init ) {
    // 1. Tampilkan Menubar Lengkap ala Microsoft Word termasuk Menu Format Gambar
    $init['menubar'] = 'file edit view insert format tools table gambar';

    // 2. Context Menu Lengkap saat Klik Kanan pada Gambar atau Tabel
    $init['contextmenu'] = 'mtsn1_menu_wrap_text | mtsn1_menu_edit_picture mtsn1_menu_caption | mtsn1_menu_delete | inserttable cell row column deletetable';

    // 3. Pastikan Toolbar Terbuka
    $init['wordpress_adv_hidden'] = false;

    // 4. Matikan Autoresize yang Menyebabkan Toolbar Mengambang & Teks Tembus saat di-scroll
    $init['wp_autoresize_on'] = false;
    $init['height'] = 650;

    // 5. Tambahkan Tombol "Tambah Media" Langsung di Dalam Toolbar TinyMCE
    $init['setup'] = 'function(editor) {
        editor.addButton("mtsn1_add_media", {
            text: "Tambah Media",
            icon: "image",
            tooltip: "Sisipkan Gambar / Foto / Audio / Dokumen",
            onclick: function() {
                var btn = document.getElementById("insert-media-button");
                if (btn) {
                    btn.click();
                }
            }
        });
    }';

    // 5. Koleksi Font Populer Microsoft Word & Standar Dokumen
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

    // 6. Pilihan Ukuran Font Lengkap (pt)
    $init['fontsize_formats'] = '8pt 9pt 10pt 11pt 12pt 13pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt 72pt';

    // 7. Muat Stylesheet Kertas Word ke dalam Editor Iframe
    $css_url = get_template_directory_uri() . '/assets/css/word-editor-content.css';
    if ( ! empty( $init['content_css'] ) ) {
        $init['content_css'] .= ',' . $css_url;
    } else {
        $init['content_css'] = $css_url;
    }

    return $init;
}

/**
 * 6. Nonaktifkan Skrip editor-expand yang Membuat Toolbar Lepas / Tembus
 */
add_action( 'admin_enqueue_scripts', 'mtsn1_word_editor_disable_expand', 999 );
function mtsn1_word_editor_disable_expand( $hook ) {
    if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
        wp_dequeue_script( 'editor-expand' );
    }
}

/**
 * 7. Styling Admin: Tata Letak Kompak, Berderet, Solid (100% Anti-Tembus saat Scroll)
 */
add_action( 'admin_head-post.php', 'mtsn1_word_editor_admin_styles' );
add_action( 'admin_head-post-new.php', 'mtsn1_word_editor_admin_styles' );
function mtsn1_word_editor_admin_styles() {
    global $post, $typenow;
    $post_type = $post ? $post->post_type : ( isset( $typenow ) ? $typenow : ( isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : 'post' ) );

    $supported = mtsn1_word_editor_supported_post_types();
    if ( ! in_array( $post_type, $supported, true ) ) {
        return;
    }

    if ( $post && use_block_editor_for_post( $post ) ) {
        return;
    }
    ?>
    <style>
        /* Sembunyikan bar tombol media lama karena sudah menyatu langsung di dalam toolbar */
        #wp-content-media-buttons {
            display: none !important;
        }

        /* Frame Editor Kontainer: Mengunci Posisi Rapi & Modern */
        #wp-content-wrap {
            position: relative !important;
            background: #ffffff !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06) !important;
        }
        #wp-content-editor-container {
            border: none !important;
            background: transparent !important;
        }

        /* Pindahkan Tabs Switcher [Visual | Code] Menyatu di Sudut Kanan Menubar */
        #wp-content-editor-tools {
            position: absolute !important;
            top: 4px !important;
            right: 8px !important;
            z-index: 30 !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            border: none !important;
            height: auto !important;
            line-height: 1 !important;
            display: inline-flex !important;
            justify-content: flex-end !important;
            align-items: center !important;
            pointer-events: auto !important;
        }

        /* Segmented Pill Capsule Modern untuk Tabs Visual & Code */
        .wp-editor-tabs {
            float: none !important;
            margin: 0 !important;
            background: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 20px !important;
            padding: 2px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 2px !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        }
        .wp-editor-tabs .wp-switch-editor {
            float: none !important;
            position: static !important;
            top: auto !important;
            margin: 0 !important;
            border: none !important;
            background: transparent !important;
            color: #64748b !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            padding: 0 12px !important;
            height: 24px !important;
            line-height: 24px !important;
            border-radius: 14px !important;
            cursor: pointer !important;
            transition: all 0.15s ease-in-out !important;
            box-shadow: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: border-box !important;
            outline: none !important;
            text-decoration: none !important;
        }
        .wp-editor-tabs #content-tmce::before {
            content: "\f177";
            font-family: dashicons;
            font-size: 13px;
            margin-right: 5px;
            line-height: 1;
            display: inline-block;
            vertical-align: middle;
        }
        .wp-editor-tabs #content-html::before {
            content: "\f475";
            font-family: dashicons;
            font-size: 13px;
            margin-right: 5px;
            line-height: 1;
            display: inline-block;
            vertical-align: middle;
        }
        .wp-editor-tabs .wp-switch-editor:hover {
            color: #0f172a !important;
            background: rgba(255, 255, 255, 0.75) !important;
        }
        .wp-editor-tabs .wp-switch-editor.html-active,
        .wp-editor-tabs .wp-switch-editor.tmce-active {
            background: #047857 !important;
            color: #ffffff !important;
            box-shadow: 0 1px 4px rgba(4, 120, 87, 0.35) !important;
        }

        /* TinyMCE Shell Container */
        .mce-tinymce {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            background: #ffffff !important;
            position: static !important;
            overflow: hidden !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
        }
        .mce-top-part {
            background: #ffffff !important;
            position: static !important;
        }

        /* Menubar ala Microsoft Word dengan Ruang Khusus Pill Tabs di Kanan */
        .mce-menubar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 3px 180px 3px 8px !important;
            position: static !important;
            border-radius: 8px 8px 0 0 !important;
            min-height: 34px !important;
            box-sizing: border-box !important;
        }
        .mce-menubar .mce-menubtn {
            margin-right: 2px !important;
        }
        .mce-menubar .mce-menubtn span {
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #334155 !important;
        }

        /* Toolbar TinyMCE Berderet Horizontal Compact, Rapi & Solid */
        .mce-toolbar-grp {
            background: #f8fafc !important;
            border-bottom: 1px solid #cbd5e1 !important;
            padding: 4px 6px !important;
            position: static !important;
        }
        .mce-toolbar-grp .mce-toolbar {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 2px !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .mce-toolbar .mce-btn-group {
            display: inline-flex !important;
            align-items: center !important;
            padding: 0 4px 0 0 !important;
            margin-right: 4px !important;
            border-right: 1px solid #cbd5e1 !important;
            margin-bottom: 2px !important;
        }
        .mce-toolbar .mce-btn-group:last-child {
            border-right: none !important;
        }
        .mce-btn {
            border-radius: 4px !important;
            margin: 1px !important;
        }
        .mce-btn:hover,
        .mce-btn.mce-active {
            background: #e2e8f0 !important;
            border-color: #cbd5e1 !important;
        }

        /* Tombol Tambah Media Spesial di Toolbar */
        .mce-btn[aria-label*="Tambah Media"] {
            background: #047857 !important;
            border: 1px solid #064e3b !important;
            border-radius: 5px !important;
            margin-right: 4px !important;
        }
        .mce-btn[aria-label*="Tambah Media"] .mce-ico {
            color: #ffffff !important;
        }
        .mce-btn[aria-label*="Tambah Media"] .mce-txt {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            padding-left: 2px !important;
        }
        .mce-btn[aria-label*="Tambah Media"]:hover {
            background: #059669 !important;
            border-color: #047857 !important;
        }

        /* Tombol Pengaturan & Tata Letak Gambar Spesial di Toolbar */
        .mce-btn[aria-label*="Tata Letak Gambar"] {
            background: #ecfdf5 !important;
            border: 1px solid #a7f3d0 !important;
            border-radius: 5px !important;
            margin-right: 4px !important;
        }
        .mce-btn[aria-label*="Tata Letak Gambar"] .mce-ico {
            color: #047857 !important;
        }
        .mce-btn[aria-label*="Tata Letak Gambar"] .mce-txt {
            color: #065f46 !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            padding-left: 2px !important;
        }
        .mce-btn[aria-label*="Tata Letak Gambar"]:hover {
            background: #d1fae5 !important;
            border-color: #6ee7b7 !important;
        }

        /* Toolbar Mengambang Gambar (Inline Floating Toolbar ala Microsoft Word) */
        .mce-toolbar-grp.inline-toolbar-grp {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.14) !important;
            padding: 3px 4px !important;
            z-index: 99999 !important;
        }
        .inline-toolbar-grp .mce-btn {
            border-radius: 4px !important;
            margin: 1px !important;
            background: transparent !important;
            border: 1px solid transparent !important;
            transition: all 0.15s ease !important;
        }
        .inline-toolbar-grp .mce-btn:hover {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
        }
        .inline-toolbar-grp .mce-btn.mce-active {
            background: #047857 !important;
            border-color: #064e3b !important;
        }
        .inline-toolbar-grp .mce-btn.mce-active .dashicons,
        .inline-toolbar-grp .mce-btn.mce-active .mce-ico {
            color: #ffffff !important;
        }
        .inline-toolbar-grp .mce-btn[aria-label*="Edit"] {
            background: #ecfdf5 !important;
            border-color: #a7f3d0 !important;
        }
        .inline-toolbar-grp .mce-btn[aria-label*="Edit"] .dashicons,
        .inline-toolbar-grp .mce-btn[aria-label*="Edit"] .mce-ico {
            color: #047857 !important;
        }
        .inline-toolbar-grp .mce-btn[aria-label*="Hapus"],
        .inline-toolbar-grp .mce-btn[aria-label*="Remove"] {
            background: #fef2f2 !important;
            border-color: #fecaca !important;
        }
        .inline-toolbar-grp .mce-btn[aria-label*="Hapus"] .dashicons,
        .inline-toolbar-grp .mce-btn[aria-label*="Remove"] .dashicons {
            color: #ef4444 !important;
        }

        /* Statusbar Baris Bawah */
        .mce-statusbar {
            background: #f8fafc !important;
            border-top: 1px solid #e2e8f0 !important;
            border-radius: 0 0 8px 8px !important;
        }

        /* Iframe Editor Area: Terkunci Rapi dengan Scroll di Dalam (Persis Microsoft Word) */
        #content_ifr {
            background: #f1f5f9 !important;
            height: 650px !important;
            min-height: 500px !important;
            display: block !important;
        }

        /* Dukungan Mode Code (HTML / Quicktags) saat Switch */
        .quicktags-toolbar {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-bottom: 1px solid #e2e8f0 !important;
            border-radius: 8px 8px 0 0 !important;
            padding: 4px 180px 4px 8px !important;
            min-height: 36px !important;
            box-sizing: border-box !important;
            position: static !important;
        }
        .quicktags-toolbar input.button {
            background: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px !important;
            color: #334155 !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            padding: 2px 8px !important;
            height: 26px !important;
            line-height: 24px !important;
            margin: 1px 2px !important;
            cursor: pointer !important;
            box-shadow: none !important;
        }
        .quicktags-toolbar input.button:hover {
            background: #e2e8f0 !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }
        #wp-content-wrap.html-active textarea#content {
            border-radius: 0 0 8px 8px !important;
            border: 1px solid #cbd5e1 !important;
            border-top: none !important;
            font-family: Consolas, Monaco, 'Courier New', monospace !important;
            font-size: 13px !important;
            line-height: 1.6 !important;
            padding: 16px !important;
            background: #ffffff !important;
            height: 600px !important;
            box-sizing: border-box !important;
        }

        /* Responsif untuk Layar Tablet / Ponsel */
        @media screen and (max-width: 782px) {
            #wp-content-editor-tools {
                position: static !important;
                background: #f8fafc !important;
                border-bottom: 1px solid #cbd5e1 !important;
                padding: 6px 8px !important;
            }
            .mce-menubar {
                padding-right: 8px !important;
            }
            .quicktags-toolbar {
                padding-right: 8px !important;
            }
        }
    </style>
    <?php
}

/**
 * 8. Tampilkan Tombol Aksi di Daftar Artikel (Word Editor vs Block Editor)
 */
add_filter( 'post_row_actions', 'mtsn1_word_editor_add_row_actions', 10, 2 );
add_filter( 'page_row_actions', 'mtsn1_word_editor_add_row_actions', 10, 2 );
function mtsn1_word_editor_add_row_actions( $actions, $post ) {
    $supported = mtsn1_word_editor_supported_post_types();
    if ( in_array( $post->post_type, $supported, true ) ) {
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
 * 9. Metabox Pengalih Mode Editor di Sidebar (Word Editor vs Gutenberg)
 */
add_action( 'add_meta_boxes', 'mtsn1_word_editor_register_metabox' );
function mtsn1_word_editor_register_metabox() {
    $supported = mtsn1_word_editor_supported_post_types();
    foreach ( $supported as $pt ) {
        add_meta_box(
            'mtsn1_editor_mode_box',
            'Pilihan Mode Editor',
            'mtsn1_word_editor_render_metabox',
            $pt,
            'side',
            'high'
        );
    }
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
