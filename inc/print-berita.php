<?php
/**
 * MTsN 1 Blitar - Modul Cetak Berita Format Dokumen A4
 * 
 * Menyediakan fitur cetak berita resmi ukuran A4 yang tampilannya persis
 * seperti di website, lengkap dengan Kop Resmi Madrasah, Kotak Redaksi 3 Kolom,
 * dan Footer Dokumentasi Publikasi Humas (Tahun).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Tambahkan Tombol "Cetak Berita" di Row Actions Daftar Berita (wp-admin/edit.php)
 */
add_filter( 'post_row_actions', 'mtsn1_print_berita_add_row_actions', 25, 2 );
function mtsn1_print_berita_add_row_actions( $actions, $post ) {
    if ( $post->post_type === 'post' && $post->post_status === 'publish' ) {
        $print_url = add_query_arg( 'print', 'berita', get_permalink( $post->ID ) );
        $actions['print_berita'] = '<a href="' . esc_url( $print_url ) . '" target="_blank" style="color: #047857; font-weight: 700;">' .
            '<span class="dashicons dashicons-printer" style="font-size:14px; width:14px; height:14px; vertical-align:middle; margin-right:2px;"></span> Cetak Berita</a>';
    }
    return $actions;
}

/**
 * 2. Tambahkan Tombol Cetak di Metabox Kreditasi Redaksi (Layar Edit Berita)
 */
add_action( 'post_submitbox_misc_actions', 'mtsn1_print_berita_submitbox_action' );
function mtsn1_print_berita_submitbox_action( $post ) {
    if ( $post->post_type === 'post' && $post->post_status === 'publish' ) {
        $print_url = add_query_arg( 'print', 'berita', get_permalink( $post->ID ) );
        ?>
        <div class="misc-pub-section misc-pub-print-berita" style="padding: 8px 10px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; margin: 8px 10px;">
            <a href="<?php echo esc_url( $print_url ); ?>" target="_blank" style="color: #047857; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 4px; font-size: 12px;">
                <span class="dashicons dashicons-printer" style="font-size: 16px; width: 16px; height: 16px;"></span>
                <span>Cetak Lembar Berita (A4)</span>
            </a>
        </div>
        <?php
    }
}

/**
 * 3. Handle Template Redirect ketika parameter ?print=berita dipanggil
 */
add_action( 'template_redirect', 'mtsn1_print_berita_template_redirect' );
function mtsn1_print_berita_template_redirect() {
    if ( is_singular( 'post' ) && isset( $_GET['print'] ) && in_array( $_GET['print'], array( 'berita', '1', 'a4' ), true ) ) {
        mtsn1_render_print_berita_page( get_the_ID() );
        exit;
    }
}

/**
 * 4. Render Halaman Cetak Berita Format Dokumen A4 Resmi
 */
function mtsn1_render_print_berita_page( $post_id ) {
    $post = get_post( $post_id );
    if ( ! $post ) {
        wp_die( 'Berita tidak ditemukan.' );
    }

    $title         = get_the_title( $post_id );
    $permalink     = get_permalink( $post_id );
    $post_year     = get_the_date( 'Y', $post_id );
    $date_indo     = get_the_date( 'l, d F Y', $post_id );
    $reading_time  = function_exists( 'mtsn1_reading_time' ) ? mtsn1_reading_time( $post_id ) : '2 menit baca';
    $redaksi       = function_exists( 'mtsn1_get_berita_redaksi' ) ? mtsn1_get_berita_redaksi( $post_id ) : array(
        'penulis' => 'Tim Jurnalis MTsN 1 Blitar',
        'editor'  => 'Tim Humas MTsN 1 Blitar',
        'foto'    => 'Tim Media MTsN 1 Blitar',
    );

    // Kategori
    $cat_name = 'Berita';
    $cats     = get_the_category( $post_id );
    if ( ! empty( $cats ) ) {
        $cat_name = $cats[0]->name;
    }

    // Logo Madrasah
    $logo_url = home_url( '/wp-content/uploads/2024/01/ikon_mtsn1blitar.png' );

    // Konten Berita
    $content = apply_filters( 'the_content', $post->post_content );

    // Featured Image
    $has_thumb   = has_post_thumbnail( $post_id );
    $thumb_url   = $has_thumb ? get_the_post_thumbnail_url( $post_id, 'full' ) : '';
    $thumb_cap   = $has_thumb ? get_the_post_thumbnail_caption( $post_id ) : '';
    $print_time  = date_i18n( 'd F Y, H:i' );
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo esc_html( $title ); ?> - Cetak Berita A4</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            /* Reset & Base Styles */
            *, *::before, *::after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background-color: #f1f5f9;
                color: #0f172a;
                line-height: 1.65;
                font-size: 13.5px;
                -webkit-font-smoothing: antialiased;
            }

            /* Toolbar Layar Browser (Tidak Tampil Saat Dicetak) */
            .screen-toolbar {
                position: sticky;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                background: #0f172a;
                color: #ffffff;
                padding: 10px 20px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 15px;
            }
            .screen-toolbar .info {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                font-weight: 600;
            }
            .screen-toolbar .actions {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
                border: none;
                transition: all 0.15s ease;
            }
            .btn-print {
                background: #047857;
                color: #ffffff;
            }
            .btn-print:hover {
                background: #059669;
            }
            .btn-close {
                background: #334155;
                color: #ffffff;
            }
            .btn-close:hover {
                background: #475569;
            }

            /* Lembar Dokumen Format A4 */
            .a4-document-container {
                max-width: 210mm;
                margin: 25px auto 40px auto;
                background: #ffffff;
                padding: 20mm 20mm 22mm 20mm;
                box-shadow: 0 8px 30px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(15, 23, 42, 0.05);
                border-radius: 6px;
                position: relative;
            }

            /* Kop Resmi Madrasah */
            .official-kop {
                display: flex;
                align-items: center;
                gap: 16px;
                padding-bottom: 14px;
                border-bottom: 3px solid #047857;
                position: relative;
                margin-bottom: 20px;
            }
            .official-kop::after {
                content: '';
                position: absolute;
                bottom: -6px;
                left: 0;
                right: 0;
                height: 1px;
                background: #d97706;
            }
            .kop-logo {
                width: 65px;
                height: 65px;
                object-fit: contain;
                flex-shrink: 0;
            }
            .kop-text {
                flex: 1;
                text-align: center;
                line-height: 1.25;
            }
            .kop-text h4 {
                font-size: 13px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: #334155;
            }
            .kop-text h3 {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                color: #475569;
                margin-top: 1px;
            }
            .kop-text h2 {
                font-size: 18px;
                font-weight: 900;
                text-transform: uppercase;
                color: #047857;
                letter-spacing: 0.02em;
                margin: 2px 0;
            }
            .kop-text p {
                font-size: 10.5px;
                color: #64748b;
                font-weight: 500;
            }

            /* Meta Bar Atas */
            .article-meta {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 8px;
                font-size: 11px;
                color: #64748b;
                margin-bottom: 12px;
            }
            .badge-cat {
                background: #d1fae5;
                color: #065f46;
                padding: 2px 8px;
                border-radius: 9999px;
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .meta-item {
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }
            .meta-item i {
                color: #047857;
            }
            .meta-author {
                background: #f1f5f9;
                border: 1px solid #e2e8f0;
                padding: 2px 8px;
                border-radius: 6px;
                font-weight: 600;
                color: #1e293b;
            }

            /* Judul Berita */
            .article-title {
                font-size: 23px;
                font-weight: 900;
                line-height: 1.35;
                color: #0f172a;
                margin-bottom: 16px;
            }

            /* Foto Utama */
            .featured-image-box {
                margin-bottom: 20px;
                border-radius: 10px;
                overflow: hidden;
                border: 1px solid #cbd5e1;
                background: #f8fafc;
            }
            .featured-image-box img {
                width: 100%;
                max-height: 380px;
                object-fit: cover;
                display: block;
            }
            .featured-image-caption {
                padding: 6px 12px;
                font-size: 11px;
                color: #64748b;
                font-style: italic;
                background: #f8fafc;
                border-top: 1px solid #e2e8f0;
            }

            /* Badan Konten Teks */
            .article-content {
                color: #1e293b;
                font-size: 13.5px;
                line-height: 1.75;
                margin-bottom: 24px;
            }
            .article-content p {
                margin-bottom: 1.25em;
                text-align: justify;
                text-justify: inter-word;
            }
            .article-content h2, .article-content h3 {
                color: #0f172a;
                font-weight: 800;
                margin: 1.4em 0 0.5em 0;
            }
            .article-content blockquote {
                border-left: 4px solid #047857;
                background: #f8fafc;
                padding: 8px 16px;
                margin: 1.3em 0;
                font-style: italic;
                color: #334155;
                border-radius: 0 8px 8px 0;
            }
            .article-content table {
                width: 100%;
                border-collapse: collapse;
                margin: 1.3em 0;
            }
            .article-content table th, .article-content table td {
                border: 1px solid #94a3b8;
                padding: 6px 10px;
                font-size: 12px;
            }
            .article-content table th {
                background: #f1f5f9;
                font-weight: 700;
                color: #0f172a;
            }

            /* Tata Letak Gambar dalam Teks (Wrap Text) */
            .article-content img {
                max-width: 100%;
                height: auto;
                border-radius: 4px;
            }
            .article-content img.alignleft, .article-content .wp-caption.alignleft {
                float: left;
                margin: 6px 18px 12px 0;
                max-width: 48%;
            }
            .article-content img.alignright, .article-content .wp-caption.alignright {
                float: right;
                margin: 6px 0 12px 18px;
                max-width: 48%;
            }
            .article-content img.aligncenter, .article-content .wp-caption.aligncenter {
                display: block;
                margin: 14px auto;
                clear: both;
                text-align: center;
            }
            .article-content .wp-caption {
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 6px;
                padding: 6px;
                text-align: center;
                box-sizing: border-box;
            }
            .article-content .wp-caption-text {
                font-size: 11px;
                color: #64748b;
                font-style: italic;
                margin-top: 4px;
            }

            /* Kotak Redaksi 3 Kolom */
            .editorial-team-box {
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                border-radius: 12px;
                padding: 14px 16px;
                margin: 24px 0 20px 0;
                page-break-inside: avoid;
            }
            .editorial-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-bottom: 8px;
                border-bottom: 1px solid #dcfce7;
                margin-bottom: 10px;
            }
            .editorial-header-title {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #064e3b;
            }
            .editorial-header-title .dot {
                width: 6px;
                height: 14px;
                border-radius: 3px;
                background: #047857;
            }
            .badge-official {
                font-size: 9.5px;
                font-weight: 800;
                text-transform: uppercase;
                background: #d1fae5;
                color: #047857;
                padding: 2px 7px;
                border-radius: 4px;
            }
            .editorial-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }
            .editorial-card {
                background: #ffffff;
                border: 1px solid #dcfce7;
                border-radius: 8px;
                padding: 8px 10px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .editorial-icon {
                width: 28px;
                height: 28px;
                border-radius: 6px;
                background: #d1fae5;
                color: #047857;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                flex-shrink: 0;
            }
            .editorial-info {
                min-width: 0;
            }
            .editorial-info .role {
                font-size: 9px;
                font-weight: 700;
                text-transform: uppercase;
                color: #64748b;
                display: block;
                letter-spacing: 0.03em;
            }
            .editorial-info .name {
                font-size: 11.5px;
                font-weight: 700;
                color: #0f172a;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }

            /* Footer Dokumentasi Publikasi Humas (Tahun) */
            .official-print-footer {
                margin-top: 25px;
                padding-top: 12px;
                border-top: 2px solid #cbd5e1;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 10.5px;
                color: #475569;
                page-break-inside: avoid;
            }
            .footer-left strong {
                color: #047857;
                font-weight: 800;
                font-size: 11.5px;
                display: block;
                margin-bottom: 2px;
            }
            .footer-left p {
                font-size: 10px;
                color: #64748b;
            }
            .footer-right {
                text-align: right;
                font-size: 10px;
                color: #64748b;
            }
            .footer-right .cert {
                display: inline-block;
                background: #f1f5f9;
                border: 1px solid #cbd5e1;
                color: #334155;
                font-weight: 700;
                padding: 1px 6px;
                border-radius: 4px;
                margin-top: 2px;
            }

            /* Pengaturan Khusus Media Cetak / Print A4 */
            @media print {
                @page {
                    size: A4 portrait;
                    margin: 14mm 16mm 16mm 16mm;
                }
                body {
                    background: #ffffff !important;
                    color: #000000 !important;
                    font-size: 11pt !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
                .no-print, .screen-toolbar {
                    display: none !important;
                }
                .a4-document-container {
                    margin: 0 !important;
                    padding: 0 !important;
                    box-shadow: none !important;
                    border-radius: 0 !important;
                    max-width: 100% !important;
                    width: 100% !important;
                }
                .article-title {
                    font-size: 20pt !important;
                    color: #000000 !important;
                }
                .article-content {
                    font-size: 10.5pt !important;
                    line-height: 1.6 !important;
                }
                .editorial-team-box {
                    background: #f8fafc !important;
                    border: 1px solid #cbd5e1 !important;
                }
                .editorial-card {
                    border: 1px solid #cbd5e1 !important;
                }
            }
        </style>
    </head>
    <body>

        <!-- Bilah Aksi di Layar Komputer / HP (Otomatis Sembunyi Saat Print) -->
        <div class="screen-toolbar no-print">
            <div class="info">
                <i class="fa-solid fa-file-invoice text-emerald-400"></i>
                <span>Pratinjau Cetak Berita A4 &mdash; MTsN 1 Blitar</span>
            </div>
            <div class="actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
                </button>
                <a href="<?php echo esc_url( $permalink ); ?>" class="btn btn-close">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Artikel
                </a>
            </div>
        </div>

        <!-- Lembar A4 Utama -->
        <div class="a4-document-container">

            <!-- 1. Kop Resmi Madrasah -->
            <header class="official-kop">
                <img src="<?php echo esc_url( $logo_url ); ?>" alt="Logo MTsN 1 Blitar" class="kop-logo" onerror="this.style.display='none';">
                <div class="kop-text">
                    <h4>Kementerian Agama Republik Indonesia</h4>
                    <h3>Kantor Kementerian Agama Kabupaten Blitar</h3>
                    <h2>Madrasah Tsanawiyah Negeri 1 Blitar</h2>
                    <p>Warta & Publikasi Resmi &bull; Jl. Raya Kuningan, Kanigoro, Blitar &bull; Website: mtsn1blitar.sch.id</p>
                </div>
            </header>

            <!-- 2. Meta Info Berita -->
            <div class="article-meta">
                <span class="badge-cat"><?php echo esc_html( $cat_name ); ?></span>
                <span class="meta-item"><i class="fa-regular fa-calendar"></i> <?php echo esc_html( $date_indo ); ?></span>
                <span>&bull;</span>
                <span class="meta-item meta-author">
                    <i class="fa-solid fa-pen-nib"></i> Penulis: <strong><?php echo esc_html( $redaksi['penulis'] ); ?></strong>
                </span>
                <span>&bull;</span>
                <span class="meta-item"><i class="fa-regular fa-clock"></i> <?php echo esc_html( $reading_time ); ?></span>
            </div>

            <!-- 3. Judul Berita -->
            <h1 class="article-title">
                <?php echo esc_html( $title ); ?>
            </h1>

            <!-- 4. Featured Image Utama -->
            <?php if ( $has_thumb ) : ?>
                <div class="featured-image-box">
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                    <?php if ( ! empty( $thumb_cap ) ) : ?>
                        <div class="featured-image-caption">
                            <i class="fa-solid fa-camera mr-1"></i> <?php echo esc_html( $thumb_cap ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- 5. Isi Teks Berita -->
            <article class="article-content">
                <?php echo $content; ?>
            </article>

            <!-- 6. Kotak Redaksi 3 Kolom (Persis Tampilan Website) -->
            <div class="editorial-team-box">
                <div class="editorial-header">
                    <div class="editorial-header-title">
                        <span class="dot"></span>
                        <span>Tim Liputan &amp; Redaksi Warta</span>
                    </div>
                    <span class="badge-official">Publikasi Resmi</span>
                </div>
                <div class="editorial-grid">
                    <!-- Penulis -->
                    <div class="editorial-card">
                        <div class="editorial-icon"><i class="fa-solid fa-pen-nib"></i></div>
                        <div class="editorial-info">
                            <span class="role">Penulis / Pewarta:</span>
                            <span class="name"><?php echo esc_html( $redaksi['penulis'] ); ?></span>
                        </div>
                    </div>
                    <!-- Editor -->
                    <div class="editorial-card">
                        <div class="editorial-icon"><i class="fa-solid fa-user-check"></i></div>
                        <div class="editorial-info">
                            <span class="role">Editor / Redaktur:</span>
                            <span class="name"><?php echo esc_html( $redaksi['editor'] ); ?></span>
                        </div>
                    </div>
                    <!-- Dokumentasi Foto -->
                    <div class="editorial-card">
                        <div class="editorial-icon"><i class="fa-solid fa-camera"></i></div>
                        <div class="editorial-info">
                            <span class="role">Dokumentasi Foto:</span>
                            <span class="name"><?php echo esc_html( $redaksi['foto'] ); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7. Footer Dokumentasi Publikasi Humas (Tahun) Sesuai Permintaan User -->
            <footer class="official-print-footer">
                <div class="footer-left">
                    <strong>Dokumentasi Publikasi Humas MTsN 1 Blitar &bull; Tahun <?php echo esc_html( $post_year ); ?></strong>
                    <p>Sumber rilis resmi: <?php echo esc_url( $permalink ); ?></p>
                </div>
                <div class="footer-right">
                    <div>Dicetak pada: <?php echo esc_html( $print_time ); ?> WIB</div>
                    <span class="cert"><i class="fa-solid fa-certificate text-emerald-600"></i> Dokumen Warta Resmi Madrasah</span>
                </div>
            </footer>

        </div>

    </body>
    </html>
    <?php
}
