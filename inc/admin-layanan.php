<?php
/**
 * Pengaturan Portal Layanan Terpadu (Ekosistem Digital)
 * Memberikan halaman admin di WP-Admin untuk mengelola link, judul, dan ikon layanan madrasah.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Data default layanan
 */
function mtsn1_get_default_portal_layanan() {
    return [
        'cbt' => [
            'title'  => 'CBT Garuda',
            'desc'   => 'Ujian & Asesmen',
            'url'    => home_url( '/cbt' ),
            'icon'   => 'fa-solid fa-laptop-code',
            'color'  => 'emerald',
            'active' => 1,
            'target' => '_blank',
        ],
        'elen' => [
            'title'  => 'E-Learning',
            'desc'   => 'Kelas Daring',
            'url'    => home_url( '/elen' ),
            'icon'   => 'fa-solid fa-book-open-reader',
            'color'  => 'amber',
            'active' => 1,
            'target' => '_blank',
        ],
        'ptsp' => [
            'title'  => 'PTSP Online',
            'desc'   => 'Surat & Ijazah',
            'url'    => home_url( '/ptsp' ),
            'icon'   => 'fa-solid fa-id-card',
            'color'  => 'blue',
            'active' => 1,
            'target' => '_blank',
        ],
        'rdm' => [
            'title'  => 'RDM Kemenag',
            'desc'   => 'Rapor Digital',
            'url'    => '#',
            'icon'   => 'fa-solid fa-chart-pie',
            'color'  => 'purple',
            'active' => 1,
            'target' => '_blank',
        ],
        'pilketos' => [
            'title'  => 'E-Pilketos',
            'desc'   => 'Pemilihan OSIS',
            'url'    => home_url( '/pilketos' ),
            'icon'   => 'fa-solid fa-check-to-slot',
            'color'  => 'rose',
            'active' => 1,
            'target' => '_blank',
        ],
        'spmb' => [
            'title'  => 'SPMB Online',
            'desc'   => 'Pendaftaran Murid',
            'url'    => 'https://ppdb.mtsn1blitar.sch.id',
            'icon'   => 'fa-solid fa-user-graduate',
            'color'  => 'teal',
            'active' => 1,
            'target' => '_blank',
        ],
    ];
}

/**
 * Mengambil data layanan aktif (tersimpan di database atau default)
 */
function mtsn1_get_portal_layanan() {
    $saved = get_option( 'mtsn1_portal_layanan', [] );
    $defaults = mtsn1_get_default_portal_layanan();
    if ( empty( $saved ) || ! is_array( $saved ) ) {
        return $defaults;
    }

    $merged = [];
    foreach ( $defaults as $key => $default_item ) {
        if ( isset( $saved[ $key ] ) && is_array( $saved[ $key ] ) ) {
            $merged[ $key ] = wp_parse_args( $saved[ $key ], $default_item );
        } else {
            $merged[ $key ] = $default_item;
        }
    }
    return $merged;
}

/**
 * Daftarkan menu di sidebar WP-Admin
 */
add_action( 'admin_menu', 'mtsn1_register_layanan_admin_menu' );
function mtsn1_register_layanan_admin_menu() {
    add_menu_page(
        'Pengaturan Portal Layanan',
        'Portal Layanan',
        'manage_options',
        'mtsn1-portal-layanan',
        'mtsn1_render_layanan_admin_page',
        'dashicons-grid-view',
        26
    );
}

/**
 * Simpan data pengaturan layanan
 */
add_action( 'admin_init', 'mtsn1_save_portal_layanan_settings' );
function mtsn1_save_portal_layanan_settings() {
    if ( ! isset( $_POST['mtsn1_layanan_nonce'] ) || ! wp_verify_nonce( $_POST['mtsn1_layanan_nonce'], 'mtsn1_save_layanan' ) ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $raw = isset( $_POST['layanan'] ) && is_array( $_POST['layanan'] ) ? $_POST['layanan'] : [];
    $sanitized = [];
    $defaults = mtsn1_get_default_portal_layanan();

    foreach ( $defaults as $key => $def ) {
        if ( isset( $raw[ $key ] ) && is_array( $raw[ $key ] ) ) {
            $url_val = trim( $raw[ $key ]['url'] ?? '' );
            // Allow '#' or valid URLs
            $clean_url = ( $url_val === '#' || empty( $url_val ) ) ? $url_val : esc_url_raw( $url_val );

            $sanitized[ $key ] = [
                'title'  => sanitize_text_field( $raw[ $key ]['title'] ?? $def['title'] ),
                'desc'   => sanitize_text_field( $raw[ $key ]['desc'] ?? $def['desc'] ),
                'url'    => $clean_url,
                'icon'   => sanitize_text_field( $raw[ $key ]['icon'] ?? $def['icon'] ),
                'color'  => sanitize_key( $raw[ $key ]['color'] ?? $def['color'] ),
                'active' => isset( $raw[ $key ]['active'] ) ? 1 : 0,
                'target' => isset( $raw[ $key ]['target'] ) ? '_blank' : '_self',
            ];
        } else {
            $sanitized[ $key ] = $def;
        }
    }

    update_option( 'mtsn1_portal_layanan', $sanitized );
    wp_safe_redirect( add_query_arg( [ 'page' => 'mtsn1-portal-layanan', 'saved' => '1' ], admin_url( 'admin.php' ) ) );
    exit;
}

/**
 * Tampilan halaman admin di WP-Admin
 */
function mtsn1_render_layanan_admin_page() {
    $items = mtsn1_get_portal_layanan();
    $color_options = [
        'emerald' => 'Hijau Emerald (Default)',
        'amber'   => 'Kuning Amber / Emas',
        'blue'    => 'Biru Kemenag',
        'purple'  => 'Ungu Elegan',
        'rose'    => 'Merah Rose / Muda',
        'teal'    => 'Tosca / Teal',
    ];
    ?>
    <div class="wrap" style="max-width: 1100px; margin-top: 20px;">
        <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; display: flex; items-center; gap: 8px;">
            <span class="dashicons dashicons-grid-view" style="font-size: 28px; width: 28px; height: 28px; color: #059669; margin-top: 2px;"></span>
            Pengaturan Portal Layanan Terpadu (Ekosistem Digital)
        </h1>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 24px;">
            Kelola tautan (URL), judul, keterangan, dan ikon untuk 6 kartu layanan digital yang tampil di bagian Beranda dan Footer website MTsN 1 Blitar.
        </p>

        <?php if ( isset( $_GET['saved'] ) && $_GET['saved'] === '1' ) : ?>
            <div class="notice notice-success is-dismissible" style="padding: 12px 16px; font-size: 14px; font-weight: 600; border-left-color: #059669; background: #ecfdf5; color: #065f46; border-radius: 8px; margin-bottom: 24px;">
                <p>✓ Perubahan tautan & data Portal Layanan berhasil disimpan!</p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field( 'mtsn1_save_layanan', 'mtsn1_layanan_nonce' ); ?>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 28px;">
                <?php foreach ( $items as $key => $item ) : ?>
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); position: relative;">
                        <!-- Header Card -->
                        <div style="display: flex; align-items: center; justify-between; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 10px; background: #f8fafc; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #059669;">
                                    <i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
                                </div>
                                <div>
                                    <strong style="font-size: 15px; color: #1e293b; display: block;"><?php echo esc_html( $item['title'] ); ?></strong>
                                    <span style="font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700;">ID: <?php echo esc_html( $key ); ?></span>
                                </div>
                            </div>
                            <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #334155; cursor: pointer; margin-left: auto;">
                                <input type="checkbox" name="layanan[<?php echo esc_attr( $key ); ?>][active]" value="1" <?php checked( ! empty( $item['active'] ), true ); ?>>
                                Aktifkan
                            </label>
                        </div>

                        <!-- Form Fields -->
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px;">Judul Layanan</label>
                                <input type="text" name="layanan[<?php echo esc_attr( $key ); ?>][title]" value="<?php echo esc_attr( $item['title'] ); ?>" class="widefat" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 6px 10px;" required>
                            </div>

                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px;">Keterangan / Subjudul</label>
                                <input type="text" name="layanan[<?php echo esc_attr( $key ); ?>][desc]" value="<?php echo esc_attr( $item['desc'] ); ?>" class="widefat" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 6px 10px;">
                            </div>

                            <div>
                                <label style="display: block; font-size: 12px; font-weight: 700; color: #059669; margin-bottom: 4px;">
                                    Link / URL Tujuan
                                </label>
                                <input type="text" name="layanan[<?php echo esc_attr( $key ); ?>][url]" value="<?php echo esc_attr( $item['url'] ); ?>" class="widefat" placeholder="https://contoh-link.sch.id atau /cbt" style="border-radius: 8px; border: 1.5px solid #059669; padding: 6px 10px; font-family: monospace; font-size: 12px;">
                                <span style="font-size: 11px; color: #64748b;">Masukkan URL lengkap (cth: <code>https://rdm.mtsn1blitar.sch.id</code>) atau tanda <code>#</code> jika belum aktif.</span>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Ikon (FontAwesome)</label>
                                    <input type="text" name="layanan[<?php echo esc_attr( $key ); ?>][icon]" value="<?php echo esc_attr( $item['icon'] ); ?>" class="widefat" style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 11px; padding: 5px 8px;">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px;">Warna Card</label>
                                    <select name="layanan[<?php echo esc_attr( $key ); ?>][color]" class="widefat" style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 11px; padding: 5px 8px;">
                                        <?php foreach ( $color_options as $c_val => $c_label ) : ?>
                                            <option value="<?php echo esc_attr( $c_val ); ?>" <?php selected( $item['color'] ?? 'emerald', $c_val ); ?>><?php echo esc_html( $c_label ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div style="margin-top: 4px;">
                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #475569; cursor: pointer;">
                                    <input type="checkbox" name="layanan[<?php echo esc_attr( $key ); ?>][target]" value="_blank" <?php checked( isset( $item['target'] ) && $item['target'] === '_blank', true ); ?>>
                                    Buka di tab baru (<em>target="_blank"</em>)
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="padding-top: 10px;">
                <button type="submit" class="button button-primary button-large" style="background: #059669; border-color: #047857; font-weight: 700; font-size: 14px; padding: 8px 24px; border-radius: 10px; height: auto; box-shadow: 0 4px 6px -1px rgba(5,150,105,0.3);">
                    <span class="dashicons dashicons-saved" style="margin-top: 4px; margin-right: 4px;"></span> Simpan Perubahan Portal Layanan
                </button>
            </div>
        </form>
    </div>
    <?php
}
