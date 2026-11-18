<?php
/**
 * Pengaturan Media Sosial & Kontak Resmi Madrasah
 * Memberikan halaman admin di WP-Admin untuk mengelola link YouTube, Instagram, Facebook, TikTok, serta Kontak.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Data default Media Sosial & Kontak
 */
function mtsn1_get_default_medsos() {
    return [
        'youtube'   => 'https://www.youtube.com/@mtsn1blitar610',
        'instagram' => 'https://www.instagram.com/mtsn1blitar69/',
        'facebook'  => 'https://www.facebook.com/mtsn1blitar',
        'tiktok'    => '',
        'alamat'    => 'Jl. Raya Kunir No. 01, Wonodadi, Blitar, Jatim',
        'whatsapp'  => '6281234567890',
        'telepon'   => '(0342) 801234',
        'email'     => 'info@mtsn1blitar.sch.id',
        'npsn'      => 'Akreditasi A (Unggul) • NPSN: 20582531',
    ];
}

/**
 * Ambil data media sosial & kontak tersimpan (fallback ke default jika kosong)
 */
function mtsn1_get_medsos() {
    $saved = get_option( 'mtsn1_medsos_settings', [] );
    $defaults = mtsn1_get_default_medsos();
    if ( empty( $saved ) || ! is_array( $saved ) ) {
        return $defaults;
    }
    return wp_parse_args( $saved, $defaults );
}

/**
 * Daftarkan menu khusus di sidebar WP-Admin
 */
add_action( 'admin_menu', 'mtsn1_register_medsos_admin_menu' );
function mtsn1_register_medsos_admin_menu() {
    add_menu_page(
        'Media Sosial & Kontak',
        'Media Sosial',
        'manage_options',
        'mtsn1-medsos',
        'mtsn1_render_medsos_admin_page',
        'dashicons-share',
        27
    );
}

/**
 * Simpan data pengaturan media sosial & kontak
 */
add_action( 'admin_init', 'mtsn1_save_medsos_settings' );
function mtsn1_save_medsos_settings() {
    if ( ! isset( $_POST['mtsn1_medsos_nonce'] ) || ! wp_verify_nonce( $_POST['mtsn1_medsos_nonce'], 'mtsn1_save_medsos' ) ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $raw = isset( $_POST['medsos'] ) && is_array( $_POST['medsos'] ) ? $_POST['medsos'] : [];
    $sanitized = [];

    $sanitized['youtube']   = ! empty( $raw['youtube'] ) ? esc_url_raw( trim( $raw['youtube'] ) ) : '';
    $sanitized['instagram'] = ! empty( $raw['instagram'] ) ? esc_url_raw( trim( $raw['instagram'] ) ) : '';
    $sanitized['facebook']  = ! empty( $raw['facebook'] ) ? esc_url_raw( trim( $raw['facebook'] ) ) : '';
    $sanitized['tiktok']    = ! empty( $raw['tiktok'] ) ? esc_url_raw( trim( $raw['tiktok'] ) ) : '';
    $sanitized['alamat']    = sanitize_text_field( trim( $raw['alamat'] ?? '' ) );
    $sanitized['whatsapp']  = sanitize_text_field( trim( $raw['whatsapp'] ?? '' ) );
    $sanitized['telepon']   = sanitize_text_field( trim( $raw['telepon'] ?? '' ) );
    $sanitized['email']     = sanitize_email( trim( $raw['email'] ?? '' ) );
    $sanitized['npsn']      = sanitize_text_field( trim( $raw['npsn'] ?? '' ) );

    update_option( 'mtsn1_medsos_settings', $sanitized );

    // Redirect kembali dengan pesan sukses
    wp_safe_redirect( add_query_arg( [ 'page' => 'mtsn1-medsos', 'saved' => '1' ], admin_url( 'admin.php' ) ) );
    exit;
}

/**
 * Tampilan Halaman Pengaturan Media Sosial & Kontak di WP-Admin
 */
function mtsn1_render_medsos_admin_page() {
    $data = mtsn1_get_medsos();
    ?>
    <div class="wrap" style="max-width: 900px; margin-top: 20px;">
        <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px;">
            <span class="dashicons dashicons-share" style="font-size: 28px; width: 28px; height: 28px; color: #059669;"></span>
            Pengaturan Media Sosial & Kontak Resmi MTsN 1 Blitar
        </h1>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 24px;">
            Atur tautan akun media sosial (YouTube, Instagram, Facebook, TikTok) dan kontak telepon/WhatsApp resmi yang tampil di bilah atas (topbar) dan footer website.
        </p>

        <?php if ( isset( $_GET['saved'] ) && $_GET['saved'] === '1' ) : ?>
            <div class="notice notice-success is-dismissible" style="padding: 12px 16px; font-size: 14px; font-weight: 600; border-left-color: #059669; background: #ecfdf5; color: #065f46; border-radius: 8px; margin-bottom: 24px;">
                <p>✓ Tautan Media Sosial & Kontak Madrasah berhasil diperbarui!</p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field( 'mtsn1_save_medsos', 'mtsn1_medsos_nonce' ); ?>

            <!-- KARTU 1: MEDIA SOSIAL -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 800; color: #1e293b; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-brands fa-hashtag" style="color: #059669;"></i> Akun Media Sosial
                </h2>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- YouTube -->
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #b91c1c; margin-bottom: 6px;">
                            <i class="fa-brands fa-youtube" style="font-size: 16px;"></i> Channel YouTube Resmi
                        </label>
                        <input type="url" name="medsos[youtube]" value="<?php echo esc_attr( $data['youtube'] ); ?>" class="widefat" placeholder="https://www.youtube.com/@namachannel" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13px;">
                        <span style="font-size: 11px; color: #64748b;">Contoh: <code>https://www.youtube.com/@mtsn1blitar610</code></span>
                    </div>

                    <!-- Instagram -->
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #be185d; margin-bottom: 6px;">
                            <i class="fa-brands fa-instagram" style="font-size: 16px;"></i> Akun Instagram Resmi
                        </label>
                        <input type="url" name="medsos[instagram]" value="<?php echo esc_attr( $data['instagram'] ); ?>" class="widefat" placeholder="https://www.instagram.com/username/" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13px;">
                        <span style="font-size: 11px; color: #64748b;">Contoh: <code>https://www.instagram.com/mtsn1blitar69/</code></span>
                    </div>

                    <!-- Facebook -->
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #1d4ed8; margin-bottom: 6px;">
                            <i class="fa-brands fa-facebook" style="font-size: 16px;"></i> Halaman Facebook Resmi
                        </label>
                        <input type="url" name="medsos[facebook]" value="<?php echo esc_attr( $data['facebook'] ); ?>" class="widefat" placeholder="https://www.facebook.com/halamanresmi" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13px;">
                        <span style="font-size: 11px; color: #64748b;">Contoh: <code>https://www.facebook.com/mtsn1blitar</code></span>
                    </div>

                    <!-- TikTok (Opsional) -->
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                            <i class="fa-brands fa-tiktok" style="font-size: 16px;"></i> Akun TikTok Resmi (Opsional)
                        </label>
                        <input type="url" name="medsos[tiktok]" value="<?php echo esc_attr( $data['tiktok'] ); ?>" class="widefat" placeholder="https://www.tiktok.com/@username (kosongkan jika belum ada)" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13px;">
                    </div>
                </div>
            </div>

            <!-- KARTU 2: KONTAK MADRASAH -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 24px;">
                <h2 style="font-size: 16px; font-weight: 800; color: #1e293b; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-address-book" style="color: #059669;"></i> Kontak & Identitas Singkat
                </h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <!-- Alamat Lengkap Footer -->
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">
                            <i class="fa-solid fa-location-dot" style="color: #059669; margin-right: 4px;"></i> Alamat Lengkap Madrasah (Tampil di Footer)
                        </label>
                        <input type="text" name="medsos[alamat]" value="<?php echo esc_attr( $data['alamat'] ?? 'Jl. Raya Kunir No. 01, Wonodadi, Blitar, Jatim' ); ?>" class="widefat" placeholder="Jl. Raya Kunir No. 01, Wonodadi, Blitar, Jatim" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13px;">
                        <span style="font-size: 11px; color: #64748b;">Alamat resmi yang tampil di footer kolom 1 website.</span>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Nomor WhatsApp PTSP / CS</label>
                        <input type="text" name="medsos[whatsapp]" value="<?php echo esc_attr( $data['whatsapp'] ); ?>" class="widefat" placeholder="6281234567890" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px;">
                        <span style="font-size: 11px; color: #64748b;">Gunakan kode negara tanpa tanda + (cth: 6281234567890)</span>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Nomor Telepon Kantor</label>
                        <input type="text" name="medsos[telepon]" value="<?php echo esc_attr( $data['telepon'] ); ?>" class="widefat" placeholder="(0342) 801234" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Email Resmi Madrasah</label>
                        <input type="email" name="medsos[email]" value="<?php echo esc_attr( $data['email'] ); ?>" class="widefat" placeholder="info@mtsn1blitar.sch.id" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Status Akreditasi / NPSN Topbar</label>
                        <input type="text" name="medsos[npsn]" value="<?php echo esc_attr( $data['npsn'] ); ?>" class="widefat" placeholder="Akreditasi A (Unggul) • NPSN: 20582531" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 8px 12px;">
                    </div>
                </div>
            </div>

            <!-- TOMBOL SIMPAN -->
            <div style="padding-top: 8px;">
                <button type="submit" class="button button-primary button-large" style="background: #059669; border-color: #047857; font-weight: 700; font-size: 14px; padding: 8px 24px; border-radius: 10px; height: auto; box-shadow: 0 4px 6px -1px rgba(5,150,105,0.3); cursor: pointer;">
                    💾 Simpan Perubahan Media Sosial & Kontak
                </button>
            </div>
        </form>
    </div>
    <?php
}
