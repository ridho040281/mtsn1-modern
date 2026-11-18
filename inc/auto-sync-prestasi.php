<?php
/**
 * Auto-sync Data Prestasi Siswa 2024 - Juli 2025
 * MTsN 1 Blitar (Auto-seeder dengan Flyer Asli Instagram)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'mtsn1_sync_prestasi_2024_2025_ig', 20 );
function mtsn1_sync_prestasi_2024_2025_ig() {
    $sync_version = 'v4_fix_flyer_mismatch';
    if ( get_option( 'mtsn1_prestasi_sync_version_ig' ) === $sync_version ) {
        return;
    }

    global $wpdb;

    // 0. Bersihkan semua data dummy/placeholder Excel dari database jika masih tersisa
    $dummy_posts = $wpdb->get_col( "
        SELECT DISTINCT p.ID 
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_kategori_prestasi'
        LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_instagram_url'
        LEFT JOIN {$wpdb->postmeta} pm3 ON p.ID = pm3.post_id AND pm3.meta_key = '_flyer_ig_filename'
        WHERE p.post_type = 'prestasi'
          AND (
              pm1.meta_value IS NOT NULL 
              OR (pm2.meta_value = 'https://www.instagram.com/mtsn1blitar69/' AND pm3.meta_value IS NULL)
              OR (p.post_title LIKE '%Ajang Kompetisi%' AND pm3.meta_value IS NULL)
          )
    " );

    if ( ! empty( $dummy_posts ) ) {
        foreach ( $dummy_posts as $d_id ) {
            wp_delete_post( (int) $d_id, true );
        }
    }

    // Bersihkan attachment flyer placeholder lama
    $dummy_att = $wpdb->get_col( "
        SELECT post_id 
        FROM {$wpdb->postmeta} 
        WHERE meta_key = '_wp_attached_file' 
          AND (meta_value LIKE '%prestasi_assets/%' OR meta_value LIKE '%prestasi/flyer_%')
    " );
    if ( ! empty( $dummy_att ) ) {
        foreach ( $dummy_att as $d_aid ) {
            wp_delete_post( (int) $d_aid, true );
        }
    }

    delete_option( 'mtsn1_prestasi_sync_version_2024_2025' );
    delete_option( 'mtsn1_prestasi_sync_version_ig' ); // reset semua versi lama

    // Hapus SEMUA post prestasi IG yang sudah ada (clean slate)
    $all_ig_posts = $wpdb->get_col( "
        SELECT DISTINCT p.ID 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_flyer_ig_filename'
        WHERE p.post_type = 'prestasi' AND p.post_status != 'trash'
    " );
    if ( ! empty( $all_ig_posts ) ) {
        foreach ( $all_ig_posts as $old_id ) {
            wp_delete_post( (int) $old_id, true );
        }
    }

    // Hapus juga post prestasi yang memakai flyer non-prestasi (Dokumen Sumatif, PKKM)
    $bad_flyers = array( 'flyer_DDZqcrdTM0E.webp', 'flyer_DDXLSL4TPal.webp', 'flyer_DDMDajgxvfS.webp', 'flyer_DFwSaNizIj2.webp' );
    foreach ( $bad_flyers as $bad_flyer ) {
        $bad_posts = $wpdb->get_col( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_flyer_ig_filename' AND meta_value = %s",
            $bad_flyer
        ) );
        foreach ( $bad_posts as $bp_id ) {
            wp_delete_post( (int) $bp_id, true );
        }
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $theme_img_dir = get_template_directory() . '/assets/images/prestasi_ig/';
    $upload_dir    = wp_upload_dir();
    $target_base   = $upload_dir['basedir'] . '/prestasi_ig/';
    if ( ! is_dir( $target_base ) ) {
        wp_mkdir_p( $target_base );
    }

    $items = array (
  // ============================================================
  // 2025
  // ============================================================
  0 => 
  array (
    'shortcode' => 'DLru60nxwGV',
    'flyer' => 'flyer_DLru60nxwGV.webp',
    'date' => '2025-07-04 10:57:22',
    'title' => 'Juara 3 Krenotek Kab. Blitar 2025 - Sa\'iidatus Sakinah, Khansa Naflah & Elsa Ghina',
    'peraih' => 'Sa\'iidatus Sakinah, Khansa Naflah, Elsa Ghina',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/DLru60nxwGV/',
    'content' => 'Alhamdulillah, segenap keluarga besar MTsN 1 Blitar mengucapkan selamat dan sukses atas raihan Juara 3 Lomba Kreativitas dan Inovasi Teknologi (KRENOTEK) Tingkat Kabupaten Blitar Tahun 2025 Bidang Agribisnis & Energi dengan inovasi "Alat Pemilah Sampah Organik dan Anorganik Berbasis IoT" yang diraih oleh Sa\'iidatus Sakinah, Khansa Naflah, dan Elsa Ghina.',
  ),
  1 => 
  array (
    // Gambar aktual: Selamat & Sukses - Porseni Jenjang MTs Kab. Blitar 2025 (logo PORSENI MTs)
    'shortcode' => 'DFufu9uRT94',
    'flyer' => 'flyer_DFufu9uRT94.webp',
    'date' => '2025-02-06 11:41:00',
    'title' => 'Borongan Juara Porseni Jenjang MTs Kab. Blitar 2025 - Kontingen MTsN 1 Blitar',
    'peraih' => 'Kontingen Porseni MTsN 1 Blitar',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/DFufu9uRT94/',
    'content' => 'Selamat & Sukses atas perolehan Juara pada berbagai cabang Pekan Olahraga dan Seni (PORSENI) Jenjang MTs Tingkat Kabupaten Blitar Tahun 2025 oleh kontingen atlet dan duta seni MTsN 1 Blitar.',
  ),
  2 => 
  array (
    // Gambar aktual: Selamat & Sukses - OSSMA DIGNITI (Islamic Science Olympiad Jawa Timur)
    'shortcode' => 'DFuuQ97z6Qa',
    'flyer' => 'flyer_DFuuQ97z6Qa.webp',
    'date' => '2025-02-06 09:35:00',
    'title' => 'Juara 1 ONSB Digniti Bidang Bahasa Inggris 2024 - Muhammad Fadhlan Azzahir',
    'peraih' => 'Muhammad Fadhlan Azzahir',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'content' => 'Selamat & Sukses kepada Muhammad Fadhlan Azzahir atas raihan Juara 1 dalam Olimpiade Nasional Sains & Bahasa (ONSB) Digniti Tingkat Nasional Tahun 2024.',
  ),
  3 => 
  array (
    // Gambar aktual: Liga Olimpiade - Medali Perak Bidang Bahasa Indonesia
    'shortcode' => 'DFuuQKvzEHZ',
    'flyer' => 'flyer_DFuuQKvzEHZ.webp',
    'date' => '2025-02-06 09:34:53',
    'title' => 'Medali Perak Liga Olimpiade Bahasa Indonesia 2025 - Delegasi MTsN 1 Blitar',
    'peraih' => 'Delegasi Siswa MTsN 1 Blitar',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/DFuuQKvzEHZ/',
    'content' => 'Selamat & Sukses atas raihan medali dalam ajang Liga Olimpiade Bidang Studi Bahasa Indonesia Tingkat Nasional Tahun 2025.',
  ),
  4 => 
  array (
    // Gambar aktual: Medali Emas YAPRESINDO Olimpiade Informatika
    'shortcode' => 'DFuuPVtTISL',
    'flyer' => 'flyer_DFuuPVtTISL.webp',
    'date' => '2025-02-06 09:34:01',
    'title' => 'Medali Emas Pekan Olimpiade Sains Nasional (POSN) Yapresindo Bidang Informatika 2025',
    'peraih' => 'Delegasi Siswa MTsN 1 Blitar',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/DFuuPVtTISL/',
    'content' => 'Selamat & Sukses atas raihan Medali Emas dalam ajang Pekan Olimpiade Sains Nasional (POSN) Yapresindo bidang Informatika Tingkat Nasional Tahun 2025.',
  ),
  5 => 
  array (
    // Gambar aktual: Juara 1 OSSMA cabang Luar Negeri Sa'iidatus Sakinah (ada trophei & foto siswi berjilbab)
    'shortcode' => 'DFufxDURGcF',
    'flyer' => 'flyer_DFufxDURGcF.webp',
    'date' => '2024-12-10 14:21:00',
    'title' => 'Juara 1 Olimpiade Sains OSSMA MAN 2 Kota Kediri 2024 - Sa\'iidatus Sakinah',
    'peraih' => 'Sa\'iidatus Sakinah (Kelas VII-1)',
    'tingkat' => 'Provinsi',
    'ig_url' => 'https://www.instagram.com/p/DFufxDURGcF/',
    'content' => 'Selamat & Sukses kepada Sa\'iidatus Sakinah yang berhasil meraih Juara 1 Olimpiade Sains IPA Terintegrasi dalam ajang OSSMA di MAN 2 Kota Kediri Tingkat Jawa Timur Tahun 2024.',
  ),
  // ============================================================
  // 2024
  // ============================================================
  11 => 
  array (
    'shortcode' => 'DCVLGFyTBBA',
    'flyer' => 'flyer_DCVLGFyTBBA.webp',
    'date' => '2024-11-13 23:58:30',
    'title' => 'Juara 1 Bulu Tangkis Ganda Putra Hari Guru Nasional 2024 - Adang Masdariyana, S.Pd',
    'peraih' => 'Adang Masdariyana, S.Pd & Rizki Mahakharisma, S.Pd',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/DCVLGFyTBBA/',
    'content' => 'Selamat Kepada Bapak Adang Masdariyana, S.Pd (Guru MTsN 1 Blitar) berpasangan dengan Bapak Rizki Mahakharisma S.Pd (Guru MAN 2 Blitar) atas raihan Juara 1 Bulu Tangkis Ganda Putra dalam rangka Peringatan Hari Guru Nasional 2024 se-Kabupaten Blitar.',
  ),
  12 => 
  array (
    'shortcode' => 'DCTBgEMTrcq',
    'flyer' => 'flyer_DCTBgEMTrcq.webp',
    'date' => '2024-11-13 03:56:11',
    'title' => 'Juara Pencak Silat MA Alma Cup 2024 - Kontingen Pencak Silat MTsN 1 Blitar',
    'peraih' => 'Tim Pencak Silat MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'ig_url' => 'https://www.instagram.com/p/DCTBgEMTrcq/',
    'content' => 'Selamat & Sukses kepada Tim Pencak Silat MTsN 1 Blitar yang berhasil memborong prestasi kejuaraan dalam ajang MA Alma Cup Tingkat Jawa Timur Tahun 2024.',
  ),
  13 => 
  array (
    'shortcode' => 'DCO__4LTpn-',
    'flyer' => 'flyer_DCO__4LTpn-.webp',
    'date' => '2024-11-11 14:26:06',
    'title' => 'Prestasi Gemilang Turnamen Robotik 2024 - Tim Robotik MTsN 1 Blitar',
    'peraih' => 'Tim Robotik MTsN 1 Blitar',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/DCO__4LTpn-/',
    'content' => 'Selamat & Sukses kepada Tim Robotik MTsN 1 Blitar atas capaian medali dan prestasi membanggakan di turnamen robotik tingkat nasional tahun 2024.',
  ),
  14 => 
  array (
    'shortcode' => 'DCJUVfkzWfX',
    'flyer' => 'flyer_DCJUVfkzWfX.webp',
    'date' => '2024-11-09 09:28:23',
    'title' => 'Juara International Robot Training and Competition (IRCT) 2024 - Tim Robotik MTsN 1 Blitar',
    'peraih' => 'Tim Robotik MTsN 1 Blitar',
    'tingkat' => 'Internasional',
    'ig_url' => 'https://www.instagram.com/p/DCJUVfkzWfX/',
    'content' => 'Selamat kepada Tim Robotik MTsN 1 Blitar yang berhasil meraih prestasi kejuaraan dalam ajang International Robot Training and Competition (IRCT) 2024.',
  ),
  15 => 
  array (
    'shortcode' => 'DB8DR-DxXFU',
    'flyer' => 'flyer_DB8DR-DxXFU.webp',
    'date' => '2024-11-04 05:49:12',
    'title' => 'Borongan Juara Olimpiade MIPA, MIPS, dan PAI MAN 1 Blitar 2024',
    'peraih' => 'Sa\'iidatus Sakinah, Addin Amalia, Lenita & Delegasi Siswa',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/DB8DR-DxXFU/',
    'content' => 'Selamat Kepada para Juara: MIPA Juara 1 (Sakinah 9.11), Harapan 3 (Nabila 9.9); MIPS Juara 1 (Addin Amalia 9.11), Harapan 3 (Ainun 9.5); PAI Juara 3 (Lenita 9.11), Harapan 1 (Ahmad Fikri 9.5), Harapan 2 (M. Fajar 9.8) dalam ajang kompetisi di MAN 1 Blitar Tahun 2024.',
  ),
  16 => 
  array (
    'shortcode' => 'DBh4gvlTjCh',
    'flyer' => 'flyer_DBh4gvlTjCh.webp',
    'date' => '2024-10-25 01:54:52',
    'title' => 'Juara Kejuaraan Pencak Silat Ketua Ansor Purwoasri Kediri 2024',
    'peraih' => 'Pesilat MTsN 1 Blitar',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/DBh4gvlTjCh/',
    'content' => 'Selamat kepada siswa & siswi yang telah meraih juara dalam Kejuaraan Pencak Silat Ketua Ansor Purwoasri Kediri Tahun 2024.',
  ),
  17 => 
  array (
    'shortcode' => 'DBY47zFzlkN',
    'flyer' => 'flyer_DBY47zFzlkN.webp',
    'date' => '2024-10-21 14:05:23',
    'title' => 'Juara 1 Porsadin Tingkat Kabupaten Blitar 2024 - M. Luqmanul Hakim Al Azizi',
    'peraih' => 'M. Luqmanul Hakim Al Azizi (Kelas 8.5)',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'content' => 'Selamat kepada Siswa MTsN 1 Blitar M. Luqmanul Hakim Al Azizi Kelas 8.5 atas raihan Juara 1 dalam Pekan Olahraga dan Seni Antar Diniyah (PORSADIN) Tingkat Kabupaten Blitar Tahun 2024.',
  ),
  18 => 
  array (
    'shortcode' => 'DBY4kArzP3U',
    'flyer' => 'flyer_DBY4kArzP3U.webp',
    'date' => '2024-10-21 14:02:08',
    'title' => 'Juara Pencak Silat Ansor Kediri 2024 - M. Sandy Saputra Pratama',
    'peraih' => 'M. Sandy Saputra Pratama (Kelas 8.5)',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/DBY4kArzP3U/',
    'content' => 'Selamat kepada siswa MTsN 1 Blitar M. Sandy Saputra Pratama Kelas 8.5 yang berhasil meraih Juara Pencak Silat Kejuaraan Ansor Kediri Tahun 2024.',
  ),
  19 => 
  array (
    'shortcode' => 'DBGfvV2zzYK',
    'flyer' => 'flyer_DBGfvV2zzYK.webp',
    'date' => '2024-10-14 10:38:54',
    'title' => 'Finalis Nasional KREASI Kemenag RI 2024 di Jakarta - Tim Robotik & Riset MTsN 1 Blitar',
    'peraih' => 'Tim Robotik & Riset MTsN 1 Blitar (Elfara Diah Erenata & Tim)',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/DBGfvV2zzYK/',
    'content' => 'Alhamdulillahirobbilalamin, Tim Robotik dan Riset MTsN 1 Blitar berhasil lolos sebagai Finalis Nasional pada ajang Kompetisi Riset dan Inovasi Siswa Indonesia (KREASI) Kemenag RI di Jakarta Tahun 2024.',
  ),
  20 => 
  array (
    'shortcode' => 'DBGfidfzYue',
    'flyer' => 'flyer_DBGfidfzYue.webp',
    'date' => '2024-10-14 10:37:09',
    'title' => 'Juara 1 Wushu Sanda Piala Rektor UNIPDU Jombang 2024 - Muhammad Dhaniel Aufa',
    'peraih' => 'Muhammad Dhaniel Aufa (Kelas 8.4)',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/DBGfidfzYue/',
    'content' => 'Selamat atas prestasi membanggakan yang telah diraih Ananda Muhammad Dhaniel Aufa Kelas 8.4 sebagai Juara 1 Cabor Wushu Sanda Piala Rektor UNIPDU Jombang Tahun 2024.',
  ),
  21 => 
  array (
    'shortcode' => 'DBEUF2KzL-o',
    'flyer' => 'flyer_DBEUF2KzL-o.webp',
    'date' => '2024-10-13 14:18:38',
    'title' => 'Juara Lomba Cipta Puisi Bulan Bahasa 2024 - Wildan Khubbi & Duta Literasi',
    'peraih' => 'Wildan Khubbi & Siswa-Siswi MTsN 1 Blitar (Pembina: Ibu Suci Lestari, S.Pd)',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/DBEUF2KzL-o/',
    'content' => 'Selamat kepada siswa-siswi MTsN 1 Blitar yang telah meraih prestasi membanggakan dalam Lomba Puisi, dan terimakasih kepada Ibu Suci Lestari, S.Pd selaku pembina yang telah menghantarkan siswa-siswi menjadi juara.',
  ),
  22 => 
  array (
    'shortcode' => 'C-fn4FGPQ9F',
    'flyer' => 'flyer_C-fn4FGPQ9F.webp',
    'date' => '2024-08-10 15:16:49',
    'title' => 'Juara 1 Lomba Menyanyi Solo Wonodadi 2024 - Naila Sa\'adah',
    'peraih' => 'Naila Sa\'adah',
    'tingkat' => 'Kecamatan',
    'ig_url' => 'https://www.instagram.com/p/C-fn4FGPQ9F/',
    'content' => 'Alhamdulillah, selamat dan sukses kepada siswi MTsN 1 Blitar Naila Sa\'adah atas torehan prestasi Juara 1 dalam Lomba Menyanyi Solo Tingkat Wonodadi Tahun 2024.',
  ),
  23 => 
  array (
    'shortcode' => 'C-fn4FGPQ9F_slide2',
    'flyer' => 'flyer_C-fn4FGPQ9F_slide2.webp',
    'date' => '2024-08-10 15:16:40',
    'title' => 'Juara 2 Lomba Menyanyi Solo Wonodadi 2024 - Rizki Alamsyah',
    'peraih' => 'Rizki Alamsyah',
    'tingkat' => 'Kecamatan',
    'ig_url' => 'https://www.instagram.com/p/C-fn4FGPQ9F/',
    'content' => 'Alhamdulillah, selamat dan sukses kepada siswa MTsN 1 Blitar Rizki Alamsyah atas raihan Juara 2 dalam ajang Lomba Menyanyi Solo Tingkat Wonodadi Tahun 2024.',
  ),
  24 => 
  array (
    'shortcode' => 'C-ahZRdvHEB',
    'flyer' => 'flyer_C-ahZRdvHEB.webp',
    'date' => '2024-08-08 15:43:59',
    'title' => 'Juara 1 MTQ Putri & Juara 2 MTQ Putra Wonodadi 2024 - Serly Celsiana & M. Zakki Mubarok',
    'peraih' => 'Serly Celsiana Anggraini & M. Zakki Mubarok',
    'tingkat' => 'Kecamatan',
    'ig_url' => 'https://www.instagram.com/p/C-ahZRdvHEB/',
    'content' => 'Setiap usaha, doa, dan dukungan akhirnya membuahkan hasil. Selamat kepada Serly Celsiana Anggraini (Juara 1 MTQ Putri) dan M. Zakki Mubarok (Juara 2 MTQ Putra) dalam ajang MTQ Tingkat Wonodadi Tahun 2024.',
  ),
  25 => 
  array (
    'shortcode' => 'C-ahZRdvHEB_slide2',
    'flyer' => 'flyer_C-ahZRdvHEB_slide2.webp',
    'date' => '2024-08-08 15:43:50',
    'title' => 'Juara 1 Turnamen Volly Putra Tingkat SMP/MTs Wonodadi 2024 - Tim Volly MTsN 1 Blitar',
    'peraih' => 'Tim Volly Putra MTsN 1 Blitar',
    'tingkat' => 'Kecamatan',
    'ig_url' => 'https://www.instagram.com/p/C-ahZRdvHEB/',
    'content' => 'Selamat kepada Tim Bola Voli Putra MTsN 1 Blitar yang berhasil meraih Juara 1 dalam Turnamen Volly Tingkat SMP/MTs Wonodadi Tahun 2024.',
  ),
  26 => 
  array (
    'shortcode' => 'C-ahZRdvHEB_slide3',
    'flyer' => 'flyer_C-ahZRdvHEB_slide3.webp',
    'date' => '2024-08-08 15:43:40',
    'title' => 'Juara 2 Turnamen Volly Putri Tingkat SMP/MTs Wonodadi 2024 - Tim Volly MTsN 1 Blitar',
    'peraih' => 'Tim Volly Putri MTsN 1 Blitar',
    'tingkat' => 'Kecamatan',
    'ig_url' => 'https://www.instagram.com/p/C-ahZRdvHEB/',
    'content' => 'Selamat kepada Tim Bola Voli Putri MTsN 1 Blitar yang berhasil meraih Juara 2 dalam Turnamen Volly Tingkat SMP/MTs Wonodadi Tahun 2024.',
  ),
  27 => 
  array (
    'shortcode' => 'C9FE6BhpdPd',
    'flyer' => 'flyer_C9FE6BhpdPd.webp',
    'date' => '2024-07-06 11:19:36',
    'title' => 'Duta Blitar di O2SN Bulutangkis Tingkat Jawa Timur 2024 - Abhista Azhar Zaiddan',
    'peraih' => 'Abhista Azhar Zaiddan (Kelas 7-5)',
    'tingkat' => 'Provinsi',
    'ig_url' => 'https://www.instagram.com/p/C9FE6BhpdPd/',
    'content' => 'Mohon Doa & Restu: Abhista Azhar Zaiddan Kelas 7-5 Mewakili Kabupaten Blitar dalam kompetisi Olimpiade Olahraga Siswa Nasional (O2SN) tingkat Provinsi cabang Bulu Tangkis di Surabaya 10-12 Juli 2024.',
  ),
  28 => 
  array (
    'shortcode' => 'C9EewlJJtEC',
    'flyer' => 'flyer_C9EewlJJtEC.webp',
    'date' => '2024-07-06 05:46:16',
    'title' => 'Juara 1 KSM IPA Terintegrasi Tingkat Kab. Blitar 2024 - Sa\'iidatus Sakinah',
    'peraih' => 'Sa\'iidatus Sakinah',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/C9EewlJJtEC/',
    'content' => 'Selamat & sukses, dengan raihan Juara 1 Kompetisi Sains Madrasah (KSM) Mapel IPA Terintegrasi tingkat Kabupaten Blitar, Ananda Sa\'iidatus Sakinah melaju ke KSM tingkat Provinsi Jawa Timur Tahun 2024.',
  ),
  29 => 
  array (
    'shortcode' => 'C7dUhFjJxdI',
    'flyer' => 'flyer_C7dUhFjJxdI.webp',
    'date' => '2024-05-27 04:14:59',
    'title' => 'Juara International Youth Robot Competition (IYRC) 2024 - Tim Robotik MTsN 1 Blitar',
    'peraih' => 'Tim Robotik MTsN 1 Blitar (Sa\'iidatus Sakinah, Khansa, Elfara Diah, Elsa Ghina)',
    'tingkat' => 'Internasional',
    'ig_url' => 'https://www.instagram.com/p/C7dUhFjJxdI/',
    'content' => 'Selamat & Sukses selalu untuk Tim Robotik MTsN 1 Blitar atas torehan prestasi dunia di ajang International Youth Robot Competition (IYRC) Tahun 2024.',
  ),
  30 => 
  array (
    'shortcode' => 'C7dUlKapeOG',
    'flyer' => 'flyer_C7dUlKapeOG.webp',
    'date' => '2024-05-27 04:15:33',
    'title' => 'Juara Java Robotic Competition (JRC) 2024 - Tim Robotik MTsN 1 Blitar',
    'peraih' => 'Tim Robotik MTsN 1 Blitar (Elsa Ghina & Vanisa Amaira Putri)',
    'tingkat' => 'Provinsi',
    'ig_url' => 'https://www.instagram.com/p/C7dUlKapeOG/',
    'content' => 'Selamat & Sukses selalu untuk Tim Robotik MTsN 1 Blitar atas raihan Juara pada Java Robotic Competition (JRC) Tingkat Jawa Timur Tahun 2024.',
  ),
  31 => 
  array (
    'shortcode' => 'C7NHCOHyI67',
    'flyer' => 'flyer_C7NHCOHyI67.webp',
    'date' => '2024-05-20 21:09:20',
    'title' => 'Sabet 5 Juara di Turnamen Robotik Indonesia Piala Ketua MPR RI 2024 - Tim Robotik MTsN 1 Blitar',
    'peraih' => 'Tim Robotik MTsN 1 Blitar',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/C7NHCOHyI67/',
    'content' => 'KEMBALI TOREHKAN PRESTASI, Tim Robotik MTsN 1 Blitar sukses menyabet 5 Juara sekaligus dalam Turnamen Robotik Indonesia Piala Ketua MPR RI Tingkat Nasional Tahun 2024.',
  ),
  32 => 
  array (
    'shortcode' => 'C7Ardw6PQaP',
    'flyer' => 'flyer_C7Ardw6PQaP.webp',
    'date' => '2024-05-16 01:17:33',
    'title' => 'Juara 1 Lomba Bulutangkis O2SN Jenjang MTs/SMP Kab. Blitar 2024 - Abhista Azhar Zaidan',
    'peraih' => 'Abhista Azhar Zaidan',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/C7Ardw6PQaP/',
    'content' => 'Selamat kepada Abhista Azhar Zaidan yang telah meraih Juara 1 dalam Lomba Bulutangkis O2SN untuk jenjang MTs/SMP Tingkat Kabupaten Blitar Tahun 2024.',
  ),
  33 => 
  array (
    'shortcode' => 'C4GEqhYJ_hS',
    'flyer' => 'flyer_C4GEqhYJ_hS.webp',
    'date' => '2024-03-04 14:00:50',
    'title' => 'Juara 3 Olimpiade MIPA di SMAN 1 Blitar 2024 - Hirdan Ma\'ruf Besari',
    'peraih' => 'Hirdan Ma\'ruf Besari',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/C4GEqhYJ_hS/',
    'content' => 'Dalam rangka MIPA competition SMAN 1 BLITAR pada 3 Maret 2024, MTsN 1 Blitar mendapatkan Juara 3 oleh Hirdan Ma\'ruf Besari dan 8 siswa masuk sebagai finalis bersama 30 peserta finalis lainnya.',
  ),
  34 => 
  array (
    'shortcode' => 'C4GEVClpEMO',
    'flyer' => 'flyer_C4GEVClpEMO.webp',
    'date' => '2024-03-04 14:01:08',
    'title' => 'Juara 3 Mantsani Research Competition (MICO) MAN 2 Kota Kediri 2024 - Aluna Silvi Urbita Oktavia',
    'peraih' => 'Aluna Silvi Urbita Oktavia',
    'tingkat' => 'Provinsi',
    'ig_url' => 'https://www.instagram.com/p/C4GEVClpEMO/',
    'content' => 'JUARA 3 MANTSANI RESEARCH COMPETITION (MICO) DI MAN 2 KOTA KEDIRI diraih oleh ALUNA SILVI URBITA OKTAVIA dengan judul karya: INOVASI "KABAR INTI" DENGAN MEMANFAATKAN CHATGPT UNTUK MENINGKATKAN KEMAMPUAN LITERASI INFORMASI SISWA MTSN 1 BLITAR.',
  ),
  35 => 
  array (
    'shortcode' => 'C3iKu2Gp7hl',
    'flyer' => 'flyer_C3iKu2Gp7hl.webp',
    'date' => '2024-02-20 13:25:53',
    'title' => 'Juara 1 Liga Pelajar Bulutangkis Kota Blitar 2024 - Abhista Azhar Zaidan & Alvira Nyka Firstynala',
    'peraih' => 'Abhista Azhar Zaidan & Alvira Nyka Firstynala',
    'tingkat' => 'Kabupaten/Kota',
    'ig_url' => 'https://www.instagram.com/p/C3iKu2Gp7hl/',
    'content' => 'Selamat & Sukses kepada siswa dan siswi MTsN 1 Blitar "Abhista Azhar Zaidan" (Juara 1 Tunggal Putra & Juara 2 Ganda Putra U14) dan "Alvira Nyka Firstynala" (Juara 1 Tunggal Putri U16) yang telah meraih Juara dalam ajang Liga Pelajar 2024 Kota Blitar cabang Bulutangkis.',
  ),
  36 => 
  array (
    'shortcode' => 'C2pfSGvJCo6',
    'flyer' => 'flyer_C2pfSGvJCo6.webp',
    'date' => '2024-01-28 15:02:59',
    'title' => 'Borongan Juara Kejuaraan Robotika Nasional (Kejurnas) 2024 - Tim Robotik MTsN 1 Blitar',
    'peraih' => 'Elfara Diah, Kumilaila, Adziqni, Elsa Ghina, Vanisa, Amadeo, Ahmada Fajri, M. Zaki, Lais Ahmad',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/C2pfSGvJCo6/',
    'content' => 'Bismillahirrohmanirrohim. Kerja keras selalu membuahkan hasil. Perolehan Piala dan Medali MTsN 1 Blitar Pada Kejuaraan Robotika Nasional (Kejurnas) 2024: Juara 1 Sumo IoT 3kg, Juara 2 Utama Soccer 500gr, Juara 3 Sumo IoT 500gr, dan Juara 3 Open Soccer 500gr.',
  ),
  37 => 
  array (
    'shortcode' => 'C2pfDbYJNGF',
    'flyer' => 'flyer_C2pfDbYJNGF.webp',
    'date' => '2024-01-28 15:00:59',
    'title' => 'Juara 1 Wushu Sanda Piala Rektor UNIPDU Jombang 2024 - Muhammad Danil Aufa',
    'peraih' => 'Muhammad Danil Aufa (Kelas 7-11)',
    'tingkat' => 'Nasional',
    'ig_url' => 'https://www.instagram.com/p/C2pfDbYJNGF/',
    'content' => 'Tabarakallah atas pencapaian prestasi yang diraih oleh Muhammad Danil Aufa kelas 7-11 dalam ajang Lomba Wushu Sanda yang diselenggarakan oleh Kepala Rektor UNIPDU Jombang sebagai Juara 1.',
  ),
);

    foreach ( $items as $item ) {
        $shortcode = $item['shortcode'];
        $flyerName = $item['flyer'];
        $title     = $item['title'];
        $peraih    = $item['peraih'];
        $tingkat   = $item['tingkat'];
        $postDate  = $item['date'];
        $igUrl     = $item['ig_url'];
        $content   = $item['content'];

        // 1. Siapkan file attachment gambar flyer asli
        $src_file  = $theme_img_dir . $flyerName;
        $dest_file = $target_base . $flyerName;
        $attach_id = 0;

        if ( file_exists( $src_file ) ) {
            if ( ! file_exists( $dest_file ) ) {
                copy( $src_file, $dest_file );
            }

            global $wpdb;
            $existing_att = $wpdb->get_var( $wpdb->prepare(
                "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
                'prestasi_ig/' . $flyerName
            ) );

            if ( $existing_att ) {
                $attach_id = (int) $existing_att;
            } else {
                $attach_id = wp_insert_attachment( array(
                    'post_mime_type' => 'image/webp',
                    'post_title'     => $title,
                    'post_status'    => 'inherit',
                ), $dest_file );

                if ( $attach_id && ! is_wp_error( $attach_id ) ) {
                    $meta_data = wp_generate_attachment_metadata( $attach_id, $dest_file );
                    wp_update_attachment_metadata( $attach_id, $meta_data );
                    update_post_meta( $attach_id, '_wp_attached_file', 'prestasi_ig/' . $flyerName );
                } else {
                    $attach_id = 0;
                }
            }
        }

        // 2. Cek apakah postingan sudah ada berdasarkan shortcode unik atau judul
        $existing_id = $wpdb->get_var( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_instagram_media_id' AND meta_value = %s LIMIT 1",
            $shortcode
        ) );

        if ( ! $existing_id ) {
            $existing_id = $wpdb->get_var( $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND post_type = 'prestasi' LIMIT 1",
                $title
            ) );
        }

        if ( $existing_id ) {
            if ( $attach_id && ! get_post_thumbnail_id( $existing_id ) ) {
                set_post_thumbnail( $existing_id, $attach_id );
            }
            if ( ! get_post_meta( $existing_id, '_flyer_ig_filename', true ) ) {
                update_post_meta( $existing_id, '_flyer_ig_filename', $flyerName );
            }
            continue;
        }

        // 3. Masukkan Post Prestasi Baru
        $post_data = array(
            'post_title'    => $title,
            'post_content'  => '<p>' . nl2br( esc_html( $content ) ) . '</p>',
            'post_status'   => 'publish',
            'post_type'     => 'prestasi',
            'post_author'   => 1,
            'post_date'     => $postDate,
            'post_date_gmt' => get_gmt_from_date( $postDate ),
            'meta_input'    => array(
                '_peraih'          => $peraih,
                '_nama_siswa'      => $peraih,
                '_tingkat'         => $tingkat,
                'tingkat_prestasi' => $tingkat,
                '_instagram_url'   => $igUrl,
                '_instagram_media_id' => $shortcode,
                '_flyer_ig_filename'  => $flyerName,
            ),
        );

        if ( $attach_id ) {
            $post_data['meta_input']['_thumbnail_id'] = $attach_id;
        }

        $post_id = wp_insert_post( $post_data );
        if ( $post_id && ! is_wp_error( $post_id ) && $attach_id ) {
            set_post_thumbnail( $post_id, $attach_id );
        }
    }

    update_option( 'mtsn1_prestasi_sync_version_ig', $sync_version );
}
