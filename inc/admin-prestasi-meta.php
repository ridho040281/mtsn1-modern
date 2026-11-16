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
        $tingkat = get_post_meta( $post_id, '_tingkat', true );
        if ( ! empty( $tingkat ) ) {
            echo '<span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 700;">' . esc_html( $tingkat ) . '</span>';
        } else {
            echo '-';
        }
    }
}
