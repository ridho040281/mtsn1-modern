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

    $peraih  = get_post_meta( $post->ID, '_peraih', true );
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
    <div style="padding: 12px 6px;">
        <div style="margin-bottom: 18px;">
            <label for="mtsn1_peraih_prestasi" style="font-weight: 700; font-size: 14px; display: block; margin-bottom: 6px; color: #0f172a;">
                👤 Nama Siswa / Tim Peraih Prestasi:
            </label>
            <input type="text" 
                   id="mtsn1_peraih_prestasi" 
                   name="mtsn1_peraih" 
                   value="<?php echo esc_attr( $peraih ); ?>" 
                   placeholder="Contoh: Sahal Muamar / Cindy Nur Rahmadhani" 
                   style="width: 100%; max-width: 650px; padding: 8px 12px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: 600;">
            <p style="color: #64748b; font-size: 12px; margin-top: 5px;">
                * Nama ini yang akan ditampilkan pada label <strong>"PERAIH PRESTASI"</strong> di kartu beranda dan halaman arsip prestasi.
            </p>
        </div>

        <div>
            <label for="mtsn1_tingkat_prestasi" style="font-weight: 700; font-size: 14px; display: block; margin-bottom: 6px; color: #0f172a;">
                🏅 Tingkat Kejuaraan:
            </label>
            <select id="mtsn1_tingkat_prestasi" 
                    name="mtsn1_tingkat" 
                    style="padding: 7px 12px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 6px; min-width: 250px;">
                <?php foreach ( $daftar_tingkat as $key => $label ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $tingkat, $key ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php
}

/**
 * 2. Simpan Data Metadata Saat Postingan Prestasi Disimpan
 */
add_action( 'save_post_prestasi', 'mtsn1_save_prestasi_meta' );
function mtsn1_save_prestasi_meta( $post_id ) {
    if ( ! isset( $_POST['mtsn1_prestasi_nonce'] ) || ! wp_verify_nonce( $_POST['mtsn1_prestasi_nonce'], 'mtsn1_prestasi_nonce_action' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['mtsn1_peraih'] ) ) {
        $peraih = sanitize_text_field( wp_unslash( $_POST['mtsn1_peraih'] ) );
        update_post_meta( $post_id, '_peraih', $peraih );
        update_post_meta( $post_id, '_nama_siswa', $peraih );
    }

    if ( isset( $_POST['mtsn1_tingkat'] ) ) {
        $tingkat = sanitize_text_field( wp_unslash( $_POST['mtsn1_tingkat'] ) );
        update_post_meta( $post_id, '_tingkat', $tingkat );
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
 * Render Badge Tingkat Kejuaraan untuk Flyer (Pojok Kanan Atas)
 */
function mtsn1_render_tingkat_badge( $tingkat, $extra_class = '' ) {
    $clean = strtolower( trim( $tingkat ) );

    switch ( $clean ) {
        case 'internasional':
        case 'international':
        case 'world':
            $label = 'Internasional';
            $icon  = 'fa-solid fa-globe';
            $style = 'bg-purple-950/90 text-purple-200 border-purple-400/60 shadow-purple-950/40';
            break;
        case 'nasional':
        case 'national':
            $label = 'Nasional';
            $icon  = 'fa-solid fa-flag';
            $style = 'bg-rose-950/90 text-rose-200 border-rose-400/60 shadow-rose-950/40';
            break;
        case 'provinsi':
        case 'karesidenan':
        case 'se-jatim':
        case 'jawa timur':
        case 'jatim':
            $label = 'Provinsi';
            $icon  = 'fa-solid fa-award';
            $style = 'bg-blue-950/90 text-blue-200 border-blue-400/60 shadow-blue-950/40';
            break;
        case 'kecamatan':
        case 'kec':
            $label = 'Kecamatan';
            $icon  = 'fa-solid fa-location-dot';
            $style = 'bg-teal-950/90 text-teal-200 border-teal-400/60 shadow-teal-950/40';
            break;
        case 'kabupaten':
        case 'kota':
        case 'kab/kota':
        case 'kabupaten/kota':
        default:
            $label = 'Kab/Kota';
            $icon  = 'fa-solid fa-medal';
            $style = 'bg-amber-950/90 text-amber-200 border-amber-400/60 shadow-amber-950/40';
            break;
    }

    return sprintf(
        '<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider backdrop-blur-md border shadow-md flex items-center gap-1.5 %s %s"><i class="%s text-xs"></i> <span>%s</span></span>',
        esc_attr( $style ),
        esc_attr( $extra_class ),
        esc_attr( $icon ),
        esc_html( $label )
    );
}
