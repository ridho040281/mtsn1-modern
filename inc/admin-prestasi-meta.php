<?php
/**
 * Pengaturan Metadata & Meta Box Peraih Prestasi untuk Post Type Prestasi
 * Memudahkan Admin untuk melihat dan mengedit nama peraih prestasi serta tingkat kejuaraan
 * langsung dari halaman editor maupun daftar postingan Prestasi di WP-Admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 0. Daftarkan Meta ke REST API untuk Gutenberg & Hapus Metabox Bentrok dari Plugin
 */
add_action( 'init', 'mtsn1_register_prestasi_meta_rest', 10 );
function mtsn1_register_prestasi_meta_rest() {
    register_post_meta( 'prestasi', '_peraih', array(
        'show_in_rest'  => true,
        'single'        => true,
        'type'          => 'string',
        'auth_callback' => function() { return current_user_can( 'edit_posts' ); }
    ) );
    register_post_meta( 'prestasi', '_nama_siswa', array(
        'show_in_rest'  => true,
        'single'        => true,
        'type'          => 'string',
        'auth_callback' => function() { return current_user_can( 'edit_posts' ); }
    ) );
    register_post_meta( 'prestasi', '_tingkat', array(
        'show_in_rest'  => true,
        'single'        => true,
        'type'          => 'string',
        'auth_callback' => function() { return current_user_can( 'edit_posts' ); }
    ) );

    // Hapus hook save_post dari plugin sekolahpro agar tidak menimpa data menjadi kosong
    remove_action( 'save_post', 'pres_meta', 1 );
}

// Hapus metabox duplikat lama dari plugin sekolahpro (Achiever / presstutorial)
add_action( 'add_meta_boxes', 'mtsn1_remove_conflicting_prestasi_metaboxes', 999 );
function mtsn1_remove_conflicting_prestasi_metaboxes() {
    remove_meta_box( 'sch_pres', 'prestasi', 'normal' );
    remove_meta_box( 'presstutorial', 'prestasi', 'normal' );
}

/**
 * 1. Tambahkan Meta Box di Editor Postingan Prestasi
 */
add_action( 'add_meta_boxes', 'mtsn1_add_prestasi_meta_box' );
function mtsn1_add_prestasi_meta_box() {
    add_meta_box(
        'mtsn1_prestasi_detail_box',
        '🏆 Detail Peraih Prestasi & Tingkat Kejuaraan',
        'mtsn1_render_prestasi_meta_box',
        'prestasi',
        'normal',
        'high'
    );
}

function mtsn1_render_prestasi_meta_box( $post ) {
    wp_nonce_field( 'mtsn1_prestasi_nonce_action', 'mtsn1_prestasi_nonce' );

    $peraih = get_post_meta( $post->ID, '_peraih', true );
    if ( empty( $peraih ) ) {
        $peraih = get_post_meta( $post->ID, '_nama_siswa', true );
    }
    if ( empty( $peraih ) ) {
        $peraih = get_post_meta( $post->ID, 'peraih', true );
    }

    $tingkat = get_post_meta( $post->ID, '_tingkat', true );
    if ( empty( $tingkat ) ) {
        $tingkat = 'Kabupaten/Kota';
    }

    $daftar_tingkat = [
        'Kecamatan'      => 'Tingkat Kecamatan',
        'Kabupaten/Kota' => 'Tingkat Kabupaten / Kota',
        'Karesidenan'    => 'Tingkat Karesidenan',
        'Provinsi'       => 'Tingkat Provinsi',
        'Nasional'       => 'Tingkat Nasional',
        'Internasional'  => 'Tingkat Internasional',
    ];
    ?>
    <div style="padding: 14px 8px;">
        <div style="margin-bottom: 20px;">
            <label for="mtsn1_peraih_prestasi" style="font-weight: 800; font-size: 14px; display: block; margin-bottom: 6px; color: #0f172a;">
                👤 Nama Siswa / Tim Peraih Prestasi:
            </label>
            <input type="text" 
                   id="mtsn1_peraih_prestasi" 
                   name="mtsn1_peraih" 
                   value="<?php echo esc_attr( $peraih ); ?>" 
                   placeholder="Contoh: Ihabillah Akmal Ramadhani / Tim Robotik MTsN 1 Blitar" 
                   style="width: 100%; max-width: 650px; padding: 10px 14px; font-size: 14px; border: 2px solid #cbd5e1; border-radius: 8px; font-weight: 700; color: #0f172a; background: #fff;">
            <input type="hidden" name="_peraih" value="<?php echo esc_attr( $peraih ); ?>" id="hidden_peraih_sync">
            <p style="color: #64748b; font-size: 12px; margin-top: 6px;">
                * Masukkan nama siswa peraih juara di sini. Nama ini akan otomatis tampil di kartu prestasi, detail prestasi, dan tabel rekapitulasi.
            </p>
        </div>

        <div>
            <label for="mtsn1_tingkat_prestasi" style="font-weight: 800; font-size: 14px; display: block; margin-bottom: 6px; color: #0f172a;">
                🏅 Tingkat Kejuaraan:
            </label>
            <select id="mtsn1_tingkat_prestasi" 
                    name="mtsn1_tingkat" 
                    style="padding: 8px 14px; font-size: 13px; font-weight: 700; border: 2px solid #cbd5e1; border-radius: 8px; min-width: 260px; color: #0f172a;">
                <?php foreach ( $daftar_tingkat as $key => $label ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $tingkat, $key ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <script>
    // Sinkronkan input hidden _peraih secara real-time
    document.addEventListener('DOMContentLoaded', function() {
        var inp = document.getElementById('mtsn1_peraih_prestasi');
        var hid = document.getElementById('hidden_peraih_sync');
        if (inp && hid) {
            inp.addEventListener('input', function() {
                hid.value = this.value;
            });
        }
    });
    </script>
    <?php
}

/**
 * 2. Simpan Data Metadata Saat Postingan Prestasi Disimpan (Robust Support Classic & Gutenberg)
 */
add_action( 'save_post_prestasi', 'mtsn1_save_prestasi_meta', 10, 2 );
add_action( 'save_post', 'mtsn1_save_prestasi_meta', 10, 2 );
function mtsn1_save_prestasi_meta( $post_id, $post = null ) {
    // 1. Cegah autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // 2. Jika ini adalah revisi, ambil ID induk utama
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }

    // 3. Pastikan post type adalah 'prestasi'
    if ( get_post_type( $post_id ) !== 'prestasi' ) {
        return;
    }

    // 4. Cek izin pengguna
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // 5. Verifikasi nonce jika ada (form submission standar)
    if ( isset( $_POST['mtsn1_prestasi_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['mtsn1_prestasi_nonce'], 'mtsn1_prestasi_nonce_action' ) ) {
            return;
        }
    }

    // 6. Ambil nama peraih dari berbagai kemungkinan input name
    $peraih_input = null;
    if ( isset( $_POST['mtsn1_peraih'] ) ) {
        $peraih_input = sanitize_text_field( wp_unslash( $_POST['mtsn1_peraih'] ) );
    } elseif ( isset( $_POST['_peraih'] ) ) {
        $peraih_input = sanitize_text_field( wp_unslash( $_POST['_peraih'] ) );
    } elseif ( isset( $_POST['peraih'] ) ) {
        $peraih_input = sanitize_text_field( wp_unslash( $_POST['peraih'] ) );
    }

    // Simpan hanya jika ada data yang dikirim
    if ( $peraih_input !== null ) {
        update_post_meta( $post_id, '_peraih', $peraih_input );
        update_post_meta( $post_id, '_nama_siswa', $peraih_input );
    }

    // 7. Ambil tingkat kejuaraan
    $tingkat_input = null;
    if ( isset( $_POST['mtsn1_tingkat'] ) ) {
        $tingkat_input = sanitize_text_field( wp_unslash( $_POST['mtsn1_tingkat'] ) );
    } elseif ( isset( $_POST['_tingkat'] ) ) {
        $tingkat_input = sanitize_text_field( wp_unslash( $_POST['_tingkat'] ) );
    }

    if ( $tingkat_input !== null && ! empty( $tingkat_input ) ) {
        update_post_meta( $post_id, '_tingkat', $tingkat_input );
    }
}

/**
 * 3. Tampilkan Kolom "Peraih Prestasi" & "Tingkat" di Tabel Daftar Prestasi WP-Admin
 */
add_filter( 'manage_prestasi_posts_columns', 'mtsn1_add_prestasi_admin_columns' );
function mtsn1_add_prestasi_admin_columns( $columns ) {
    $new_columns = [];
    foreach ( $columns as $key => $title ) {
        $new_columns[ $key ] = $title;
        if ( 'title' === $key ) {
            $new_columns['peraih']  = '🏆 Peraih Prestasi';
            $new_columns['tingkat'] = '🏅 Tingkat';
        }
    }
    return $new_columns;
}

add_action( 'manage_prestasi_posts_custom_column', 'mtsn1_render_prestasi_admin_columns', 10, 2 );
function mtsn1_render_prestasi_admin_columns( $column, $post_id ) {
    if ( 'peraih' === $column ) {
        $peraih = get_post_meta( $post_id, '_peraih', true );
        if ( ! empty( $peraih ) ) {
            echo '<strong style="color: #0f172a;">' . esc_html( $peraih ) . '</strong>';
        } else {
            echo '<span style="color: #94a3b8; font-style: italic;">(Belum diisi)</span>';
        }
    } elseif ( 'tingkat' === $column ) {
        $tingkat = mtsn1_get_tingkat_prestasi( $post_id );
        if ( ! empty( $tingkat ) ) {
            echo '<span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 700;">' . esc_html( $tingkat ) . '</span>';
        } else {
            echo '-';
        }
    }
}

/**
 * Dapatkan Tingkat Kejuaraan (Auto-detect jika belum terisi di meta)
 */
function mtsn1_get_tingkat_prestasi( $post_id ) {
    $tingkat = get_post_meta( $post_id, '_tingkat', true );
    if ( ! empty( $tingkat ) ) {
        $lower = strtolower( trim( $tingkat ) );
        if ( in_array( $lower, [ 'kabupaten', 'kota', 'kabupaten/kota', 'kab/kota', 'blitar' ], true ) ) {
            return 'Kab/Kota';
        }
        if ( in_array( $lower, [ 'karesidenan', 'se-jatim', 'jawa timur', 'jatim', 'regional' ], true ) ) {
            return 'Provinsi';
        }
        if ( in_array( $lower, [ 'kecamatan', 'kec' ], true ) ) {
            return 'Kecamatan';
        }
        if ( in_array( $lower, [ 'nasional', 'national', 'indonesia' ], true ) ) {
            return 'Nasional';
        }
        if ( in_array( $lower, [ 'internasional', 'international', 'world' ], true ) ) {
            return 'Internasional';
        }
        return ucfirst( $tingkat );
    }

    // Auto-detect dari judul dan konten
    $title = get_the_title( $post_id );
    $content = get_post_field( 'post_content', $post_id );
    $text = strtoupper( $title . ' ' . wp_strip_all_tags( $content ) );

    if ( strpos( $text, 'INTERNASIONAL' ) !== false || strpos( $text, 'INTERNATIONAL' ) !== false || strpos( $text, 'WORLD' ) !== false ) {
        return 'Internasional';
    }
    if ( strpos( $text, 'NASIONAL' ) !== false || strpos( $text, 'INDONESIA' ) !== false || strpos( $text, 'SE-INDONESIA' ) !== false ) {
        return 'Nasional';
    }
    if ( strpos( $text, 'PROVINSI' ) !== false || strpos( $text, 'JAWA TIMUR' ) !== false || strpos( $text, 'JATIM' ) !== false || strpos( $text, 'KARESIDENAN' ) !== false || strpos( $text, 'SE-EKS' ) !== false || strpos( $text, 'REGIONAL' ) !== false || strpos( $text, 'SE-JAWA' ) !== false ) {
        return 'Provinsi';
    }
    if ( strpos( $text, 'KECAMATAN' ) !== false || strpos( $text, 'KEC.' ) !== false || strpos( $text, 'WONODADI' ) !== false || strpos( $text, 'SE-KECAMATAN' ) !== false ) {
        return 'Kecamatan';
    }

    return 'Kab/Kota';
}

/**
 * Deteksi Dinamis Peringkat / Juara Berapa dari Judul Prestasi
 */
function mtsn1_get_peringkat_data( $post_id ) {
    $title = get_the_title( $post_id );

    // 1. Juara Harapan 1 / 2 / 3 / Harapan
    if ( preg_match( '/Juara\s+Harapan\s+1\b|Harapan\s+1\b|Juara\s+Harapan\s+I\b/i', $title ) ) {
        return array( 'label' => 'Juara Harapan 1', 'icon' => 'fa-solid fa-award', 'bg' => '#ecfdf5', 'color' => '#064e3b', 'border' => '#34d399', 'icon_color' => '#059669' );
    }
    if ( preg_match( '/Juara\s+Harapan\s+2\b|Harapan\s+2\b|Juara\s+Harapan\s+II\b/i', $title ) ) {
        return array( 'label' => 'Juara Harapan 2', 'icon' => 'fa-solid fa-award', 'bg' => '#f0fdfa', 'color' => '#134e4a', 'border' => '#2dd4bf', 'icon_color' => '#0d9488' );
    }
    if ( preg_match( '/Juara\s+Harapan\s+3\b|Harapan\s+3\b|Juara\s+Harapan\s+III\b/i', $title ) ) {
        return array( 'label' => 'Juara Harapan 3', 'icon' => 'fa-solid fa-award', 'bg' => '#ecfeff', 'color' => '#164e63', 'border' => '#22d3ee', 'icon_color' => '#0891b2' );
    }
    if ( preg_match( '/Juara\s+Harapan\b|Harapan\b/i', $title ) ) {
        return array( 'label' => 'Juara Harapan', 'icon' => 'fa-solid fa-award', 'bg' => '#ecfdf5', 'color' => '#064e3b', 'border' => '#34d399', 'icon_color' => '#059669' );
    }

    // 2. Juara 1 / Emas / Peringkat 1
    if ( preg_match( '/Juara\s+1\b|Juara\s+I\b|Juara\s+Pertama\b|Peringkat\s+1\b/i', $title ) ) {
        return array( 'label' => 'Juara 1', 'icon' => 'fa-solid fa-trophy', 'bg' => '#fef3c7', 'color' => '#78350f', 'border' => '#f59e0b', 'icon_color' => '#d97706' );
    }
    if ( preg_match( '/Gold\s+Medal|Medali\s+Emas/i', $title ) ) {
        return array( 'label' => 'Gold Medal', 'icon' => 'fa-solid fa-trophy', 'bg' => '#fef3c7', 'color' => '#78350f', 'border' => '#f59e0b', 'icon_color' => '#d97706' );
    }

    // 3. Juara 2 / Perak / Peringkat 2
    if ( preg_match( '/Juara\s+2\b|Juara\s+II\b|Juara\s+Kedua\b|Peringkat\s+2\b/i', $title ) ) {
        return array( 'label' => 'Juara 2', 'icon' => 'fa-solid fa-medal', 'bg' => '#f1f5f9', 'color' => '#1e293b', 'border' => '#94a3b8', 'icon_color' => '#64748b' );
    }
    if ( preg_match( '/Silver\s+Medal|Medali\s+Perak/i', $title ) ) {
        return array( 'label' => 'Silver Medal', 'icon' => 'fa-solid fa-medal', 'bg' => '#f1f5f9', 'color' => '#1e293b', 'border' => '#94a3b8', 'icon_color' => '#64748b' );
    }

    // 4. Juara 3 / Perunggu / Peringkat 3
    if ( preg_match( '/Juara\s+3\s+Bersama/i', $title ) ) {
        return array( 'label' => 'Juara 3 Bersama', 'icon' => 'fa-solid fa-medal', 'bg' => '#ffedd5', 'color' => '#7c2d12', 'border' => '#fb923c', 'icon_color' => '#ea580c' );
    }
    if ( preg_match( '/Juara\s+3\b|Juara\s+III\b|Juara\s+Ketiga\b|Peringkat\s+3\b/i', $title ) ) {
        return array( 'label' => 'Juara 3', 'icon' => 'fa-solid fa-medal', 'bg' => '#ffedd5', 'color' => '#7c2d12', 'border' => '#fb923c', 'icon_color' => '#ea580c' );
    }
    if ( preg_match( '/Bronze\s+Medal|Medali\s+Perunggu/i', $title ) ) {
        return array( 'label' => 'Bronze Medal', 'icon' => 'fa-solid fa-medal', 'bg' => '#ffedd5', 'color' => '#7c2d12', 'border' => '#fb923c', 'icon_color' => '#ea580c' );
    }

    // 5. Juara Umum / Borong Prestasi
    if ( preg_match( '/Juara\s+Umum|Borong\s+Prestasi|Borong\s+Juara/i', $title ) ) {
        return array( 'label' => 'Juara Umum', 'icon' => 'fa-solid fa-crown', 'bg' => '#faf5ff', 'color' => '#581c87', 'border' => '#c084fc', 'icon_color' => '#9333ea' );
    }

    // 6. Juara Favorit
    if ( preg_match( '/Favorit/i', $title ) ) {
        return array( 'label' => 'Juara Favorit', 'icon' => 'fa-solid fa-heart', 'bg' => '#fff1f2', 'color' => '#881337', 'border' => '#fb7185', 'icon_color' => '#e11d48' );
    }

    // 7. Honorable Mention
    if ( preg_match( '/Honorable\s+Mention/i', $title ) ) {
        return array( 'label' => 'Honorable Mention', 'icon' => 'fa-solid fa-award', 'bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#94a3b8', 'icon_color' => '#475569' );
    }

    // 8. Juara Madya
    if ( preg_match( '/Juara\s+Madya/i', $title ) ) {
        return array( 'label' => 'Juara Madya', 'icon' => 'fa-solid fa-medal', 'bg' => '#f0fdfa', 'color' => '#134e4a', 'border' => '#2dd4bf', 'icon_color' => '#0d9488' );
    }

    // 9. Juara Utama
    if ( preg_match( '/Juara\s+Utama/i', $title ) ) {
        return array( 'label' => 'Juara Utama', 'icon' => 'fa-solid fa-trophy', 'bg' => '#fef3c7', 'color' => '#78350f', 'border' => '#f59e0b', 'icon_color' => '#d97706' );
    }

    // 10. Peringkat Terbaik
    if ( preg_match( '/Peringkat\s+Terbaik/i', $title ) ) {
        return array( 'label' => 'Peringkat Terbaik', 'icon' => 'fa-solid fa-star', 'bg' => '#fef3c7', 'color' => '#78350f', 'border' => '#f59e0b', 'icon_color' => '#d97706' );
    }

    // 11. Finalis / Lolos Seleksi / Top N
    if ( preg_match( '/Finalis|Top\s+\d+|Best|Lolos\s+Seleksi|Lolos\s+\d+\s+Besar/i', $title ) ) {
        return array( 'label' => 'Finalis', 'icon' => 'fa-solid fa-certificate', 'bg' => '#eff6ff', 'color' => '#1e3a8a', 'border' => '#60a5fa', 'icon_color' => '#2563eb' );
    }

    // 12. Delegasi / Kontingen / Mewakili
    if ( preg_match( '/Delegasi|Kontingen|Mewakili/i', $title ) ) {
        return array( 'label' => 'Delegasi', 'icon' => 'fa-solid fa-flag-checkered', 'bg' => '#e0e7ff', 'color' => '#312e81', 'border' => '#818cf8', 'icon_color' => '#4f46e5' );
    }

    // 13. Penghargaan / Piagam
    if ( preg_match( '/Penghargaan|Piagam/i', $title ) ) {
        return array( 'label' => 'Penghargaan', 'icon' => 'fa-solid fa-certificate', 'bg' => '#f8fafc', 'color' => '#334155', 'border' => '#cbd5e1', 'icon_color' => '#64748b' );
    }

    // 14. Juara (Umum / Tanpa Angka)
    if ( preg_match( '/\bJuara\b/i', $title ) ) {
        return array( 'label' => 'Juara', 'icon' => 'fa-solid fa-trophy', 'bg' => '#fef3c7', 'color' => '#78350f', 'border' => '#f59e0b', 'icon_color' => '#d97706' );
    }

    // Default Fallback
    return array( 'label' => 'Prestasi', 'icon' => 'fa-solid fa-award', 'bg' => '#ecfdf5', 'color' => '#064e3b', 'border' => '#34d399', 'icon_color' => '#059669' );
}

/**
 * Render Badge Peringkat Juara Dinamis (Juara 1, Juara 2, Juara 3, Juara Harapan, dst.)
 */
function mtsn1_render_peringkat_badge( $post_id, $extra_class = '' ) {
    $data = mtsn1_get_peringkat_data( $post_id );

    return sprintf(
        '<span class="shrink-0 %s" style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1.2; background-color: %s; color: %s; border: 1px solid %s; box-shadow: 0 1px 2px rgba(0,0,0,0.06);"><i class="%s" style="font-size: 11px; color: %s;"></i> <span>%s</span></span>',
        esc_attr( $extra_class ),
        esc_attr( $data['bg'] ),
        esc_attr( $data['color'] ),
        esc_attr( $data['border'] ),
        esc_attr( $data['icon'] ),
        esc_attr( $data['icon_color'] ),
        esc_html( $data['label'] )
    );
}

/**
 * Render Badge Tingkat Kejuaraan (Tampil Jelas, Tajam, dan Kontras Tinggi)
 */
function mtsn1_render_tingkat_badge( $tingkat, $extra_class = '' ) {
    $clean = strtolower( trim( $tingkat ) );

    switch ( $clean ) {
        case 'internasional':
        case 'international':
        case 'world':
            $label      = 'Internasional';
            $icon       = 'fa-solid fa-globe';
            $bg         = '#4c1d95';
            $text       = '#ffffff';
            $border     = '#a855f7';
            $icon_color = '#e9d5ff';
            break;
        case 'nasional':
        case 'national':
            $label      = 'Nasional';
            $icon       = 'fa-solid fa-flag';
            $bg         = '#881337';
            $text       = '#ffffff';
            $border     = '#f43f5e';
            $icon_color = '#fecdd3';
            break;
        case 'provinsi':
        case 'karesidenan':
        case 'se-jatim':
        case 'jawa timur':
        case 'jatim':
            $label      = 'Provinsi';
            $icon       = 'fa-solid fa-award';
            $bg         = '#1e3a8a';
            $text       = '#ffffff';
            $border     = '#3b82f6';
            $icon_color = '#93c5fd';
            break;
        case 'kecamatan':
        case 'kec':
            $label      = 'Kecamatan';
            $icon       = 'fa-solid fa-location-dot';
            $bg         = '#134e4a';
            $text       = '#ffffff';
            $border     = '#14b8a6';
            $icon_color = '#99f6e4';
            break;
        case 'sekolah':
        case 'internal':
            $label      = 'Sekolah';
            $icon       = 'fa-solid fa-school';
            $bg         = '#1e293b';
            $text       = '#ffffff';
            $border     = '#64748b';
            $icon_color = '#cbd5e1';
            break;
        case 'kabupaten':
        case 'kota':
        case 'kab/kota':
        case 'kabupaten/kota':
        default:
            $label      = 'Kab/Kota';
            $icon       = 'fa-solid fa-medal';
            $bg         = '#064e3b';
            $text       = '#ffffff';
            $border     = '#10b981';
            $icon_color = '#6ee7b7';
            break;
    }

    return sprintf(
        '<span class="shrink-0 %s" style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1.2; background-color: %s; color: %s; border: 1px solid %s; box-shadow: 0 1px 2px rgba(0,0,0,0.1);"><i class="%s" style="font-size: 11px; color: %s;"></i> <span>%s</span></span>',
        esc_attr( $extra_class ),
        esc_attr( $bg ),
        esc_attr( $text ),
        esc_attr( $border ),
        esc_attr( $icon ),
        esc_attr( $icon_color ),
        esc_html( $label )
    );
}
