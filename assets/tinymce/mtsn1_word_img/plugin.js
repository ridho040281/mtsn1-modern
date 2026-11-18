/**
 * Plugin TinyMCE: Pengaturan & Tata Letak Gambar ala Microsoft Word
 * MTsN 1 Blitar - Word Editor Module
 *
 * Fitur:
 * 1. Menu Tata Letak / Bungkus Teks (Wrap Text):
 *    - In Line with Text (Sejajar Teks Paragraf)
 *    - Square - Kiri (Teks Membungkus di Kanan Gambar)
 *    - Square - Kanan (Teks Membungkus di Kiri Gambar)
 *    - Di Tengah (Center)
 *    - Top and Bottom (Pisahkan Teks Atas & Bawah)
 * 2. Edit Gambar & Ukuran (Size & Position)
 * 3. Sisipkan / Edit Keterangan Foto (Caption)
 * 4. Hapus Gambar (Delete Picture)
 * 5. Menu Bar "Format Gambar" & Dropdown Toolbar "Tata Letak Gambar"
 * 6. Klik Kanan (Context Menu) pada Gambar
 */
(function(tinymce) {
    'use strict';

    if (!tinymce) {
        return;
    }

    tinymce.PluginManager.add('mtsn1_word_img', function(editor) {

        /**
         * Mencari elemen IMG yang sedang dipilih atau aktif
         */
        function getSelectedImage() {
            var node = editor.selection.getNode();
            if (!node) {
                return null;
            }
            if (node.nodeName === 'IMG') {
                return node;
            }
            var childImg = node.querySelector ? node.querySelector('img') : null;
            if (childImg) {
                return childImg;
            }
            var parentCaption = editor.dom.getParent(node, '.wp-caption') || editor.dom.getParent(node, 'figure');
            if (parentCaption) {
                var found = editor.dom.select('img', parentCaption);
                if (found && found.length) {
                    return found[0];
                }
            }
            // Jika ada satu gambar saja dalam seleksi
            var selImg = editor.dom.select('img[data-mce-selected="1"]');
            if (selImg && selImg.length) {
                return selImg[0];
            }
            return null;
        }

        /**
         * Mengatur Tata Letak / Wrap Text Gambar ala Microsoft Word
         */
        function applyImageWrap(wrapType) {
            var img = getSelectedImage();
            if (!img) {
                editor.windowManager.alert('Silakan klik atau pilih gambar di dalam dokumen terlebih dahulu untuk mengatur Tata Letak (Wrap Text).');
                return;
            }

            var caption = editor.dom.getParent(img, '.wp-caption') || editor.dom.getParent(img, 'figure');
            var target = caption || img;

            editor.undoManager.transact(function() {
                // Bersihkan semua class perataan sebelumnya
                editor.dom.removeClass(target, 'alignleft alignright aligncenter alignnone');
                if (caption) {
                    editor.dom.removeClass(img, 'alignleft alignright aligncenter alignnone');
                }

                if (wrapType === 'inline') {
                    // 1. In Line with Text (Sejajar Teks)
                    editor.dom.addClass(target, 'alignnone');
                    if (caption) {
                        editor.dom.addClass(img, 'alignnone');
                    }
                    editor.dom.setStyles(target, {
                        'float': 'none',
                        'display': 'inline-block',
                        'margin': '8px 12px',
                        'clear': 'none',
                        'vertical-align': 'middle'
                    });
                } else if (wrapType === 'square_left') {
                    // 2. Square - Kiri (Teks Mengalir Membungkus di Kanan Gambar)
                    editor.dom.addClass(target, 'alignleft');
                    if (caption) {
                        editor.dom.addClass(img, 'alignleft');
                    }
                    editor.dom.setStyles(target, {
                        'float': 'left',
                        'display': 'block',
                        'margin': '8px 24px 16px 0',
                        'clear': 'none'
                    });
                } else if (wrapType === 'square_right') {
                    // 3. Square - Kanan (Teks Mengalir Membungkus di Kiri Gambar)
                    editor.dom.addClass(target, 'alignright');
                    if (caption) {
                        editor.dom.addClass(img, 'alignright');
                    }
                    editor.dom.setStyles(target, {
                        'float': 'right',
                        'display': 'block',
                        'margin': '8px 0 16px 24px',
                        'clear': 'none'
                    });
                } else if (wrapType === 'center') {
                    // 4. Di Tengah (Center)
                    editor.dom.addClass(target, 'aligncenter');
                    if (caption) {
                        editor.dom.addClass(img, 'aligncenter');
                    }
                    editor.dom.setStyles(target, {
                        'float': 'none',
                        'display': 'block',
                        'margin': '20px auto',
                        'clear': 'both',
                        'text-align': 'center'
                    });
                } else if (wrapType === 'top_bottom') {
                    // 5. Top and Bottom (Pisahkan Teks Atas & Bawah / Blok Bersih)
                    editor.dom.addClass(target, 'aligncenter');
                    if (caption) {
                        editor.dom.addClass(img, 'aligncenter');
                    }
                    editor.dom.setStyles(target, {
                        'float': 'none',
                        'display': 'block',
                        'margin': '24px auto',
                        'clear': 'both'
                    });
                }
            });

            editor.selection.select(img);
            editor.nodeChanged();
            editor.fire('change');
        }

        /**
         * Membuka Dialog Pengaturan Gambar / Ukuran / Detail (Size & Position)
         */
        function editCurrentImage() {
            var img = getSelectedImage();
            if (!img) {
                editor.windowManager.alert('Silakan klik gambar yang ingin diedit pengaturannya.');
                return;
            }
            editor.selection.select(img);

            // Cek apakah ada handler bawaan wpeditimage
            if (editor.buttons && editor.buttons.wp_img_edit && typeof editor.buttons.wp_img_edit.onclick === 'function') {
                editor.buttons.wp_img_edit.onclick();
            } else if (editor.commands && editor.commands.mceImage) {
                editor.execCommand('mceImage');
            } else {
                // Fallback: klik tombol edit di toolbar mengambang jika ada
                var editBtn = document.querySelector('.inline-toolbar-grp .dashicons-edit');
                if (editBtn) {
                    editBtn.click();
                } else {
                    editor.execCommand('mceImage');
                }
            }
        }

        /**
         * Hapus Gambar Terpilih
         */
        function deleteCurrentImage() {
            var img = getSelectedImage();
            if (!img) {
                editor.windowManager.alert('Silakan klik gambar yang ingin dihapus.');
                return;
            }
            var caption = editor.dom.getParent(img, '.wp-caption') || editor.dom.getParent(img, 'figure');
            var target = caption || img;

            editor.undoManager.transact(function() {
                editor.dom.remove(target);
            });
            editor.nodeChanged();
            editor.fire('change');
        }

        /**
         * 1. Daftarkan Menu Items untuk Menubar dan Context Menu
         */
        editor.addMenuItem('mtsn1_menu_media', {
            text: 'Sisipkan Gambar Baru...',
            icon: 'image',
            onclick: function() {
                var btn = document.getElementById('insert-media-button');
                if (btn) {
                    btn.click();
                }
            }
        });

        editor.addMenuItem('mtsn1_wrap_inline', {
            text: '📄 In Line with Text (Sejajar Teks)',
            onclick: function() {
                applyImageWrap('inline');
            }
        });

        editor.addMenuItem('mtsn1_wrap_square_left', {
            text: '📑 Square - Kiri (Teks di Kanan)',
            onclick: function() {
                applyImageWrap('square_left');
            }
        });

        editor.addMenuItem('mtsn1_wrap_square_right', {
            text: '📑 Square - Kanan (Teks di Kiri)',
            onclick: function() {
                applyImageWrap('square_right');
            }
        });

        editor.addMenuItem('mtsn1_wrap_center', {
            text: '↔️ Di Tengah (Center)',
            onclick: function() {
                applyImageWrap('center');
            }
        });

        editor.addMenuItem('mtsn1_wrap_topbottom', {
            text: '↕️ Top & Bottom (Pisah Teks Atas-Bawah)',
            onclick: function() {
                applyImageWrap('top_bottom');
            }
        });

        editor.addMenuItem('mtsn1_menu_wrap_text', {
            text: 'Bungkus Teks (Wrap Text)',
            icon: 'image',
            menu: [
                {
                    text: '📄 In Line with Text (Sejajar Teks)',
                    onclick: function() { applyImageWrap('inline'); }
                },
                {
                    text: '📑 Square - Kiri (Teks di Kanan)',
                    onclick: function() { applyImageWrap('square_left'); }
                },
                {
                    text: '📑 Square - Kanan (Teks di Kiri)',
                    onclick: function() { applyImageWrap('square_right'); }
                },
                {
                    text: '↔️ Di Tengah (Center)',
                    onclick: function() { applyImageWrap('center'); }
                },
                {
                    text: '↕️ Top & Bottom (Pisah Teks)',
                    onclick: function() { applyImageWrap('top_bottom'); }
                }
            ]
        });

        editor.addMenuItem('mtsn1_menu_edit_picture', {
            text: 'Edit Gambar & Ukuran (Size & Position)...',
            icon: 'dashicon dashicons-edit',
            onclick: editCurrentImage
        });

        editor.addMenuItem('mtsn1_menu_caption', {
            text: 'Keterangan Foto (Caption)...',
            icon: 'dashicon dashicons-admin-comments',
            onclick: editCurrentImage
        });

        editor.addMenuItem('mtsn1_menu_delete', {
            text: 'Hapus Gambar',
            icon: 'dashicon dashicons-no',
            onclick: deleteCurrentImage
        });

        /**
         * 2. Daftarkan Tombol Dropdown Khusus di Toolbar Utama TinyMCE: "Tata Letak Gambar"
         */
        editor.addButton('mtsn1_image_layout', {
            type: 'menubutton',
            text: 'Tata Letak Gambar',
            icon: 'image',
            tooltip: 'Tata Letak & Bungkus Teks Gambar (Wrap Text ala Microsoft Word)',
            menu: [
                {
                    text: '📄 In Line with Text (Sejajar Teks)',
                    onclick: function() { applyImageWrap('inline'); }
                },
                {
                    text: '📑 Square - Kiri (Teks Mengalir di Kanan)',
                    onclick: function() { applyImageWrap('square_left'); }
                },
                {
                    text: '📑 Square - Kanan (Teks Mengalir di Kiri)',
                    onclick: function() { applyImageWrap('square_right'); }
                },
                {
                    text: '↔️ Di Tengah (Center)',
                    onclick: function() { applyImageWrap('center'); }
                },
                {
                    text: '↕️ Top & Bottom (Pisahkan Teks)',
                    onclick: function() { applyImageWrap('top_bottom'); }
                },
                { text: '-' },
                {
                    text: '⚙️ Edit Ukuran & Posisi (Size & Details)...',
                    icon: 'dashicon dashicons-edit',
                    onclick: editCurrentImage
                },
                {
                    text: '📝 Sisipkan / Edit Caption (Keterangan)...',
                    icon: 'dashicon dashicons-admin-comments',
                    onclick: editCurrentImage
                },
                {
                    text: '🗑️ Hapus Gambar',
                    icon: 'dashicon dashicons-no',
                    onclick: deleteCurrentImage
                }
            ]
        });

        /**
         * 3. Daftarkan Menu Bar "Format Gambar" di Menubar Dokumen TinyMCE
         */
        editor.on('BeforeRenderUI', function() {
            editor.settings.menu = editor.settings.menu || {};
            editor.settings.menu.gambar = {
                title: 'Format Gambar',
                items: 'mtsn1_menu_media | mtsn1_wrap_inline mtsn1_wrap_square_left mtsn1_wrap_square_right mtsn1_wrap_center mtsn1_wrap_topbottom | mtsn1_menu_edit_picture mtsn1_menu_caption | mtsn1_menu_delete'
            };
        });

        /**
         * 4. Perbarui Tooltip Tombol Popup Mengambang Bawaan WordPress dengan Istilah Microsoft Word
         */
        editor.on('init', function() {
            var wordTooltips = {
                'wp_img_alignnone': 'In Line with Text (Sejajar Teks)',
                'wp_img_alignleft': 'Square - Kiri (Teks Membungkus di Kanan)',
                'wp_img_aligncenter': 'Di Tengah / Top & Bottom (Teks di Atas & Bawah)',
                'wp_img_alignright': 'Square - Kanan (Teks Membungkus di Kiri)',
                'wp_img_edit': 'Edit Gambar & Ukuran (Size & Position)...',
                'wp_img_remove': 'Hapus Gambar'
            };

            for (var btn in wordTooltips) {
                if (editor.buttons && editor.buttons[btn]) {
                    editor.buttons[btn].tooltip = wordTooltips[btn];
                }
            }
        });
    });

})(window.tinymce);
