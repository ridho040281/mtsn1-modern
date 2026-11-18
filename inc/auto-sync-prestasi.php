<?php
/**
 * Auto-sync Data Prestasi Siswa 2024 - Juli 2025
 * MTsN 1 Blitar (Auto-seeder untuk cPanel Deploy)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'mtsn1_sync_prestasi_2024_2025', 20 );
function mtsn1_sync_prestasi_2024_2025() {
    $sync_version = 'v1_2024_2025_complete';
    if ( get_option( 'mtsn1_prestasi_sync_version_2024_2025' ) === $sync_version ) {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';

    // 1. Daftarkan / cek attachment flyer kategori
    $theme_img_dir = get_template_directory() . '/assets/images/prestasi/';
    $flyers = array(
        'bulutangkis'      => 'flyer_bulutangkis.webp',
        'robotik'          => 'flyer_robotik.webp',
        'robotik_inovasi'  => 'flyer_robotik_inovasi.webp',
        'riset'            => 'flyer_riset.webp',
        'wushu_silat'      => 'flyer_wushu_silat.webp',
        'pmr'              => 'flyer_pmr.webp',
        'pramuka'          => 'flyer_pramuka.webp',
        'tahfidz'          => 'flyer_tahfidz.webp',
        'catur'            => 'flyer_catur.webp',
        'mtq_pidato'       => 'flyer_mtq_pidato.webp',
        'sains_mipa'       => 'flyer_sains_mipa.webp',
        'krenotek'         => 'flyer_krenotek.webp',
    );

    $upload_dir = wp_upload_dir();
    $target_base = $upload_dir['basedir'] . '/prestasi_assets/';
    if ( ! is_dir( $target_base ) ) {
        wp_mkdir_p( $target_base );
    }

    $attachment_ids = array();
    foreach ( $flyers as $key => $fname ) {
        $src_file = $theme_img_dir . $fname;
        if ( ! file_exists( $src_file ) ) {
            continue;
        }
        $dest_file = $target_base . $fname;
        if ( ! file_exists( $dest_file ) ) {
            copy( $src_file, $dest_file );
        }

        $existing = get_posts( array(
            'post_type'      => 'attachment',
            'meta_key'       => '_wp_attached_file',
            'meta_value'     => 'prestasi_assets/' . $fname,
            'posts_per_page' => 1,
        ) );

        if ( ! empty( $existing ) ) {
            $attachment_ids[$key] = $existing[0]->ID;
        } else {
            $attach_id = wp_insert_attachment( array(
                'post_mime_type' => 'image/webp',
                'post_title'     => 'Flyer Prestasi ' . ucfirst( $key ),
                'post_status'    => 'inherit',
            ), $dest_file );

            if ( $attach_id && ! is_wp_error( $attach_id ) ) {
                $meta_data = wp_generate_attachment_metadata( $attach_id, $dest_file );
                wp_update_attachment_metadata( $attach_id, $meta_data );
                update_post_meta( $attach_id, '_wp_attached_file', 'prestasi_assets/' . $fname );
                $attachment_ids[$key] = $attach_id;
            }
        }
    }

    // 2. Data Prestasi 157 postingan
    $items = array (
  0 => 
  array (
    'title' => 'Juara 1 Wushu Kompetisi Wushu Sanda Kepala Rektor UNIPDU Jombang 2024 - Muhammad Dhaniel Aufa',
    'peraih' => 'Muhammad Dhaniel Aufa',
    'tingkat' => 'Nasional',
    'category' => 'wushu_silat',
    'date' => '2024-01-28 08:00:00',
    'ig_url' => 'https://www.instagram.com/p/C_rkr3bTkXK/',
    'ajang' => 'Kompetisi Wushu Sanda Kepala Rektor UNIPDU Jombang',
    'prestasi' => 'Juara 1 Wushu',
    'year' => '2024',
  ),
  1 => 
  array (
    'title' => 'Juara 3 Kategori Sumo IoT 500 gr Kompetisi Robotik Kejurnas 2024 - Ahmada Fajri Al Aziz',
    'peraih' => 'Ahmada Fajri Al Aziz',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-01-28 08:13:29',
    'ig_url' => 'https://www.instagram.com/p/C2pfSGvJCo6/',
    'ajang' => 'Kompetisi Robotik Kejurnas',
    'prestasi' => 'Juara 3 Kategori Sumo IoT 500 gr',
    'year' => '2024',
  ),
  2 => 
  array (
    'title' => 'Juara 3 Kategori Sumo IoT 500 gr Kompetisi Robotik Kejurnas 2024 - M. Zakki Yusron Fauzu',
    'peraih' => 'M. Zakki Yusron Fauzu',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-01-28 08:26:58',
    'ig_url' => 'https://www.instagram.com/p/C2pfSGvJCo6/',
    'ajang' => 'Kompetisi Robotik Kejurnas',
    'prestasi' => 'Juara 3 Kategori Sumo IoT 500 gr',
    'year' => '2024',
  ),
  3 => 
  array (
    'title' => 'Juara 1 Kategori Sumo IoT 3kg Kompetisi Robotik Kejurnas 2024 - Elfara Diah Erenata',
    'peraih' => 'Elfara Diah Erenata',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-01-28 08:39:27',
    'ig_url' => 'https://www.instagram.com/p/C2pfSGvJCo6/',
    'ajang' => 'Kompetisi Robotik Kejurnas',
    'prestasi' => 'Juara 1 Kategori Sumo IoT 3kg',
    'year' => '2024',
  ),
  4 => 
  array (
    'title' => 'Juara 1 Kategori Sumo IoT 3kg Kompetisi Robotik Kejurnas 2024 - Kumilaila Choiru',
    'peraih' => 'Kumilaila Choiru',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-01-28 08:52:56',
    'ig_url' => 'https://www.instagram.com/p/C2pfSGvJCo6/',
    'ajang' => 'Kompetisi Robotik Kejurnas',
    'prestasi' => 'Juara 1 Kategori Sumo IoT 3kg',
    'year' => '2024',
  ),
  5 => 
  array (
    'title' => 'Juara 1 Kategori Sumo IoT 3kg Kompetisi Robotik Kejurnas 2024 - Adziqni Khalawata Dzikri',
    'peraih' => 'Adziqni Khalawata Dzikri',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-01-28 09:05:25',
    'ig_url' => 'https://www.instagram.com/p/C2pfSGvJCo6/',
    'ajang' => 'Kompetisi Robotik Kejurnas',
    'prestasi' => 'Juara 1 Kategori Sumo IoT 3kg',
    'year' => '2024',
  ),
  6 => 
  array (
    'title' => 'Juara 3 Kategori Soccer 500 gr Kejuaraan Nasional IRTC 2024 - Amadeo Abqary Dhiyaulhaq',
    'peraih' => 'Amadeo Abqary Dhiyaulhaq',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-01-28 09:18:54',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Nasional IRTC',
    'prestasi' => 'Juara 3 Kategori Soccer 500 gr',
    'year' => '2024',
  ),
  7 => 
  array (
    'title' => 'Juara 3 Kategori Soccer 500 gr Kejuaraan Nasional IRTC 2024 - Lais Ahmad Zamia',
    'peraih' => 'Lais Ahmad Zamia',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-01-28 09:31:23',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Nasional IRTC',
    'prestasi' => 'Juara 3 Kategori Soccer 500 gr',
    'year' => '2024',
  ),
  8 => 
  array (
    'title' => 'Juara 2 Utama Kategori Soccer 500 gr Kejuaraan Nasional IRTC 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-01-28 09:44:52',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Nasional IRTC',
    'prestasi' => 'Juara 2 Utama Kategori Soccer 500 gr',
    'year' => '2024',
  ),
  9 => 
  array (
    'title' => 'Juara 1 Tunggal U14 Liga Pelajar 2024 Kota Blitar 2024 - Abhista Azhar Zaidan',
    'peraih' => 'Abhista Azhar Zaidan',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-02-19 10:57:21',
    'ig_url' => 'https://www.instagram.com/p/C3iKu2Gp7hl/',
    'ajang' => 'Liga Pelajar 2024 Kota Blitar',
    'prestasi' => 'Juara 1 Tunggal U14',
    'year' => '2024',
  ),
  10 => 
  array (
    'title' => 'Juara 1 Tunggal U16 Putri Liga Pelajar 2024 Kota Blitar 2024 - Alvira Nyka Firstynala',
    'peraih' => 'Alvira Nyka Firstynala',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-02-19 10:10:50',
    'ig_url' => 'https://www.instagram.com/p/C3iKu2Gp7hl/',
    'ajang' => 'Liga Pelajar 2024 Kota Blitar',
    'prestasi' => 'Juara 1 Tunggal U16 Putri',
    'year' => '2024',
  ),
  11 => 
  array (
    'title' => 'Juara 3 MICO Lomba Karya Tulis Ilmiah MICO 2024 di MAN 2 KOTA KEDIRI 2024 - Aluna Silvi Urbita Oktavia',
    'peraih' => 'Aluna Silvi Urbita Oktavia',
    'tingkat' => 'Provinsi',
    'category' => 'riset',
    'date' => '2024-03-04 10:23:19',
    'ig_url' => 'https://www.instagram.com/p/C4GEVClpEMO/',
    'ajang' => 'Lomba Karya Tulis Ilmiah MICO 2024 di MAN 2 KOTA KEDIRI',
    'prestasi' => 'Juara 3 MICO',
    'year' => '2024',
  ),
  12 => 
  array (
    'title' => 'Juara 3 Olimpiade MIPA MIPA Competition SMAN 1 BLITAR 2024 - Hirdan Ma’ruf Besari',
    'peraih' => 'Hirdan Ma’ruf Besari',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2024-03-04 10:36:48',
    'ig_url' => 'https://www.instagram.com/p/C4GEqhYJ_hS/',
    'ajang' => 'MIPA Competition SMAN 1 BLITAR',
    'prestasi' => 'Juara 3 Olimpiade MIPA',
    'year' => '2024',
  ),
  13 => 
  array (
    'title' => 'Juara 1 Bulutangkis Lomba Bulutangkis O2SN jenjang MTs/SMP 2024 - Abhista Azhar Zaidan',
    'peraih' => 'Abhista Azhar Zaidan',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'bulutangkis',
    'date' => '2024-05-20 11:49:17',
    'ig_url' => 'https://www.instagram.com/p/C-Ag-gQzffw/',
    'ajang' => 'Lomba Bulutangkis O2SN jenjang MTs/SMP',
    'prestasi' => 'Juara 1 Bulutangkis',
    'year' => '2024',
  ),
  14 => 
  array (
    'title' => 'Juara 1 Olimpiade IPS Kompetisi Olimpiade Festival Zistion Fest SMA AL IZZAH KOTA BATU 2024 - Hirdan Ma’ruf Besari',
    'peraih' => 'Hirdan Ma’ruf Besari',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2024-05-20 11:02:46',
    'ig_url' => 'https://www.instagram.com/p/C7Ardw6PQaP/',
    'ajang' => 'Kompetisi Olimpiade Festival Zistion Fest SMA AL IZZAH KOTA BATU',
    'prestasi' => 'Juara 1 Olimpiade IPS',
    'year' => '2024',
  ),
  15 => 
  array (
    'title' => 'Juara 2 Olimpiade Matematika Kompetisi Olimpiade Festival Zistion Fest SMA AL IZZAH KOTA BATU 2024 - Andis Aura Every',
    'peraih' => 'Andis Aura Every',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2024-05-20 11:15:15',
    'ig_url' => 'https://www.instagram.com/p/C7Ardw6PQaP/',
    'ajang' => 'Kompetisi Olimpiade Festival Zistion Fest SMA AL IZZAH KOTA BATU',
    'prestasi' => 'Juara 2 Olimpiade Matematika',
    'year' => '2024',
  ),
  16 => 
  array (
    'title' => 'Juara 1 Sumo Junior Turnamen Robotik Indonesia Piala MPR RI 2024 - Adziqni Khalawata Dzikri',
    'peraih' => 'Adziqni Khalawata Dzikri',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-05-20 11:28:44',
    'ig_url' => 'https://www.instagram.com/p/C7NHCOHyI67/',
    'ajang' => 'Turnamen Robotik Indonesia Piala MPR RI',
    'prestasi' => 'Juara 1 Sumo Junior',
    'year' => '2024',
  ),
  17 => 
  array (
    'title' => 'Juara 2 Wifi Car Adventure Junior Turnamen Robotik Indonesia Piala MPR RI 2024 - Khansa Mazaya Kamilia',
    'peraih' => 'Khansa Mazaya Kamilia',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-05-20 12:41:13',
    'ig_url' => 'https://www.instagram.com/p/C7NHCOHyI67/',
    'ajang' => 'Turnamen Robotik Indonesia Piala MPR RI',
    'prestasi' => 'Juara 2 Wifi Car Adventure Junior',
    'year' => '2024',
  ),
  18 => 
  array (
    'title' => 'Juara 3 Sumo Junior Turnamen Robotik Indonesia Piala MPR RI 2024 - Kumilaila Choiru Anil Hawa',
    'peraih' => 'Kumilaila Choiru Anil Hawa',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-05-20 12:54:42',
    'ig_url' => 'https://www.instagram.com/p/C7NHCOHyI67/',
    'ajang' => 'Turnamen Robotik Indonesia Piala MPR RI',
    'prestasi' => 'Juara 3 Sumo Junior',
    'year' => '2024',
  ),
  19 => 
  array (
    'title' => 'Juara 3 Wifi Car Adventure Junior Turnamen Robotik Indonesia Piala MPR RI 2024 - Amadeo Abqary Dhiyaulhaq',
    'peraih' => 'Amadeo Abqary Dhiyaulhaq',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-05-20 12:07:11',
    'ig_url' => 'https://www.instagram.com/p/C7NHCOHyI67/',
    'ajang' => 'Turnamen Robotik Indonesia Piala MPR RI',
    'prestasi' => 'Juara 3 Wifi Car Adventure Junior',
    'year' => '2024',
  ),
  20 => 
  array (
    'title' => 'Juara 2 Wifi Car Adventure Senior Turnamen Robotik Indonesia Piala MPR RI 2024 - M. Amir Setiawan',
    'peraih' => 'M. Amir Setiawan',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-05-20 12:20:40',
    'ig_url' => 'https://www.instagram.com/p/C7NHCOHyI67/',
    'ajang' => 'Turnamen Robotik Indonesia Piala MPR RI',
    'prestasi' => 'Juara 2 Wifi Car Adventure Senior',
    'year' => '2024',
  ),
  21 => 
  array (
    'title' => 'Juara 3 Bronze Kategori Rancang Bangun Kompetisi Robotik IYRC 2024 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-05-20 13:33:09',
    'ig_url' => 'https://www.instagram.com/p/C7dUhFjJxdI/',
    'ajang' => 'Kompetisi Robotik IYRC 2024',
    'prestasi' => 'Juara 3 Bronze Kategori Rancang Bangun',
    'year' => '2024',
  ),
  22 => 
  array (
    'title' => 'Juara Excellent Rancang Bangun Robotik Jatim Robot Competition (JRC) 2024 di Kemenag Kanwil Jatim 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-05-20 13:46:38',
    'ig_url' => 'https://www.instagram.com/p/C7dUlKapeOG/',
    'ajang' => 'Jatim Robot Competition (JRC) 2024 di Kemenag Kanwil Jatim',
    'prestasi' => 'Juara Excellent Rancang Bangun Robotik',
    'year' => '2024',
  ),
  23 => 
  array (
    'title' => 'Juara 1 IPA Kompetisi Sains Madrasah Mapel IPA 2024 - Sa’iidatus Sakinah',
    'peraih' => 'Sa’iidatus Sakinah',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-07-29 13:59:07',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Kompetisi Sains Madrasah Mapel IPA',
    'prestasi' => 'Juara 1 IPA',
    'year' => '2024',
  ),
  24 => 
  array (
    'title' => 'Juara 1 MTQ Kec. Wonodadi Ajang Kompetisi Lomba MTQ Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79 2024 - Serly Celsiana Anggraini',
    'peraih' => 'Serly Celsiana Anggraini',
    'tingkat' => 'Kecamatan',
    'category' => 'mtq_pidato',
    'date' => '2024-08-10 13:12:36',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Ajang Kompetisi Lomba MTQ Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79',
    'prestasi' => 'Juara 1 MTQ Kec. Wonodadi',
    'year' => '2024',
  ),
  25 => 
  array (
    'title' => 'Juara 1 Lomba Volly Putra SMP/MTs Ajang Kompetisi Lomba Volly Putra Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79 2024 - Tim Volly Putra MTsN 1 Blitar',
    'peraih' => 'Tim Volly Putra MTsN 1 Blitar',
    'tingkat' => 'Kecamatan',
    'category' => 'sains_mipa',
    'date' => '2024-08-10 14:25:05',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Ajang Kompetisi Lomba Volly Putra Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79',
    'prestasi' => 'Juara 1 Lomba Volly Putra SMP/MTs',
    'year' => '2024',
  ),
  26 => 
  array (
    'title' => 'Juara 2 MTQ Ajang Kompetisi Lomba MTQ Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79 2024 - M. Zakki Mubarok',
    'peraih' => 'M. Zakki Mubarok',
    'tingkat' => 'Kecamatan',
    'category' => 'mtq_pidato',
    'date' => '2024-08-10 14:38:34',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Ajang Kompetisi Lomba MTQ Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79',
    'prestasi' => 'Juara 2 MTQ',
    'year' => '2024',
  ),
  27 => 
  array (
    'title' => 'Juara 2 Lomba Volly Putri SMP/MTs Ajang Kompetisi Lomba Volly Putri Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79 2024 - Tim Volly Putri MTsN 1 Blitar',
    'peraih' => 'Tim Volly Putri MTsN 1 Blitar',
    'tingkat' => 'Kecamatan',
    'category' => 'sains_mipa',
    'date' => '2024-08-10 14:51:03',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Ajang Kompetisi Lomba Volly Putri Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79',
    'prestasi' => 'Juara 2 Lomba Volly Putri SMP/MTs',
    'year' => '2024',
  ),
  28 => 
  array (
    'title' => 'Juara 1 Lomba Menyanyi Solo Ajang Kompetisi Lomba Menyanyi Solo Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79 2024 - Naila Sa’adah',
    'peraih' => 'Naila Sa’adah',
    'tingkat' => 'Kecamatan',
    'category' => 'mtq_pidato',
    'date' => '2024-08-10 14:04:32',
    'ig_url' => 'https://www.instagram.com/p/C-fn4FGPQ9F/',
    'ajang' => 'Ajang Kompetisi Lomba Menyanyi Solo Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79',
    'prestasi' => 'Juara 1 Lomba Menyanyi Solo',
    'year' => '2024',
  ),
  29 => 
  array (
    'title' => 'Juara 2 Lomba Menyanyi Solo Ajang Kompetisi Lomba Menyanyi Solo Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79 2024 - Rizki Alamsyah',
    'peraih' => 'Rizki Alamsyah',
    'tingkat' => 'Kecamatan',
    'category' => 'mtq_pidato',
    'date' => '2024-08-10 15:17:01',
    'ig_url' => 'https://www.instagram.com/p/C-fn4FGPQ9F/',
    'ajang' => 'Ajang Kompetisi Lomba Menyanyi Solo Dalam Rangka Hari Jadi Blitar ke 700 dan HUT Kemerdekaan RI ke 79',
    'prestasi' => 'Juara 2 Lomba Menyanyi Solo',
    'year' => '2024',
  ),
  30 => 
  array (
    'title' => 'Juara 2 Puisi Liga Baca Puisi 2024 2024 - Wildan Khubbi',
    'peraih' => 'Wildan Khubbi',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2024-10-20 15:30:30',
    'ig_url' => 'https://www.instagram.com/p/C_ejbvmvPQH/',
    'ajang' => 'Liga Baca Puisi 2024',
    'prestasi' => 'Juara 2 Puisi',
    'year' => '2024',
  ),
  31 => 
  array (
    'title' => 'Juara Harapan 1 Puisi Liga Baca Puisi 2024 2024 - Faza Fidda Roini',
    'peraih' => 'Faza Fidda Roini',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2024-10-20 15:43:59',
    'ig_url' => 'https://www.instagram.com/p/C_ejbvmvPQH/',
    'ajang' => 'Liga Baca Puisi 2024',
    'prestasi' => 'Juara Harapan 1 Puisi',
    'year' => '2024',
  ),
  32 => 
  array (
    'title' => 'Juara 2 Kelas 65 KG Pra Remaja Junior UNIPDU WUSHU CHAMPIONSHIP 2024 2024 - M. Dhaniel Aufa',
    'peraih' => 'M. Dhaniel Aufa',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-10-20 15:56:28',
    'ig_url' => 'https://www.instagram.com/p/C_rkr3bTkXK/',
    'ajang' => 'UNIPDU WUSHU CHAMPIONSHIP 2024',
    'prestasi' => 'Juara 2 Kelas 65 KG Pra Remaja Junior',
    'year' => '2024',
  ),
  33 => 
  array (
    'title' => 'Juara 1 Olimpiade Lomba PMR Bramajaya III Tingkat Madya 2024 2024 - Yusifatus Sholichah',
    'peraih' => 'Yusifatus Sholichah',
    'tingkat' => 'Provinsi',
    'category' => 'pmr',
    'date' => '2024-10-20 16:09:57',
    'ig_url' => 'https://www.instagram.com/p/DBEUF2KzL-o/',
    'ajang' => 'Lomba PMR Bramajaya III Tingkat Madya 2024',
    'prestasi' => 'Juara 1 Olimpiade',
    'year' => '2024',
  ),
  34 => 
  array (
    'title' => 'Juara 1 Gerakan Kepalangmerahan Lomba PMR Bramajaya III Tingkat Madya 2024 2024 - Nabila Nova Aulia',
    'peraih' => 'Nabila Nova Aulia',
    'tingkat' => 'Provinsi',
    'category' => 'pmr',
    'date' => '2024-10-20 16:22:26',
    'ig_url' => 'https://www.instagram.com/p/DBEUF2KzL-o/',
    'ajang' => 'Lomba PMR Bramajaya III Tingkat Madya 2024',
    'prestasi' => 'Juara 1 Gerakan Kepalangmerahan',
    'year' => '2024',
  ),
  35 => 
  array (
    'title' => 'Juara 3 Donor Darah Lomba PMR Bramajaya III Tingkat Madya 2024 2024 - Sheila Zakiah Wahyudi',
    'peraih' => 'Sheila Zakiah Wahyudi',
    'tingkat' => 'Provinsi',
    'category' => 'pmr',
    'date' => '2024-10-20 16:35:55',
    'ig_url' => 'https://www.instagram.com/p/DBEUF2KzL-o/',
    'ajang' => 'Lomba PMR Bramajaya III Tingkat Madya 2024',
    'prestasi' => 'Juara 3 Donor Darah',
    'year' => '2024',
  ),
  36 => 
  array (
    'title' => 'Juara 3 Sanitasi Kesehatan Lomba PMR Bramajaya III Tingkat Madya 2024 2024 - Izzatin Naaimah',
    'peraih' => 'Izzatin Naaimah',
    'tingkat' => 'Provinsi',
    'category' => 'pmr',
    'date' => '2024-10-20 16:48:24',
    'ig_url' => 'https://www.instagram.com/p/DBEUF2KzL-o/',
    'ajang' => 'Lomba PMR Bramajaya III Tingkat Madya 2024',
    'prestasi' => 'Juara 3 Sanitasi Kesehatan',
    'year' => '2024',
  ),
  37 => 
  array (
    'title' => 'Juara Harapan 3 Pertolongan Pertama Lomba PMR Bramajaya III Tingkat Madya 2024 2024 - Tasya Mailadina Zuhriyah',
    'peraih' => 'Tasya Mailadina Zuhriyah',
    'tingkat' => 'Provinsi',
    'category' => 'pmr',
    'date' => '2024-10-20 17:01:53',
    'ig_url' => 'https://www.instagram.com/p/DBEUF2KzL-o/',
    'ajang' => 'Lomba PMR Bramajaya III Tingkat Madya 2024',
    'prestasi' => 'Juara Harapan 3 Pertolongan Pertama',
    'year' => '2024',
  ),
  38 => 
  array (
    'title' => 'Juara Harapan 2 Simulasi Pertolongan Pertama Lomba PMR Bramajaya III Tingkat Madya 2024 2024 - Tim A Simulasi Pertolongan Pertama PMR MTsN 1 Blitar',
    'peraih' => 'Tim A Simulasi Pertolongan Pertama PMR MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'category' => 'pmr',
    'date' => '2024-10-20 17:14:22',
    'ig_url' => 'https://www.instagram.com/p/DBEUF2KzL-o/',
    'ajang' => 'Lomba PMR Bramajaya III Tingkat Madya 2024',
    'prestasi' => 'Juara Harapan 2 Simulasi Pertolongan Pertama',
    'year' => '2024',
  ),
  39 => 
  array (
    'title' => 'Juara 2.1 Lomba PMR Bramajaya III Tingkat Madya 2024 2024 - Tim PMR MTsN 1 Blitar',
    'peraih' => 'Tim PMR MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'category' => 'pmr',
    'date' => '2024-10-20 17:27:51',
    'ig_url' => 'https://www.instagram.com/p/DBEUF2KzL-o/',
    'ajang' => 'Lomba PMR Bramajaya III Tingkat Madya 2024',
    'prestasi' => 'Juara 2.1',
    'year' => '2024',
  ),
  40 => 
  array (
    'title' => 'Juara 1 Puisi Putra Pekan Olahraga dan Seni antar Diniyah Kab. Blitar 2024 - M. Luqmanul Hakim Alzizi',
    'peraih' => 'M. Luqmanul Hakim Alzizi',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2024-10-20 17:40:20',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Pekan Olahraga dan Seni antar Diniyah Kab. Blitar',
    'prestasi' => 'Juara 1 Puisi Putra',
    'year' => '2024',
  ),
  41 => 
  array (
    'title' => 'Juara 1 Ganda Tangan Kosong Pra Remaja Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri 2024 - Maha’ Biyadil Ngafafi Asunul Jismi',
    'peraih' => 'Maha’ Biyadil Ngafafi Asunul Jismi',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-10-20 17:53:49',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri',
    'prestasi' => 'Juara 1 Ganda Tangan Kosong Pra Remaja',
    'year' => '2024',
  ),
  42 => 
  array (
    'title' => 'Juara 1 H Remaja Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri 2024 - M. Rizqi Izza Abadan',
    'peraih' => 'M. Rizqi Izza Abadan',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-10-20 14:06:18',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri',
    'prestasi' => 'Juara 1 H Remaja',
    'year' => '2024',
  ),
  43 => 
  array (
    'title' => 'Juara 1 Ganda Tangan Kosong Pra Remaja Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri 2024 - Lisa Lusiana Saputri',
    'peraih' => 'Lisa Lusiana Saputri',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-10-20 14:19:47',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri',
    'prestasi' => 'Juara 1 Ganda Tangan Kosong Pra Remaja',
    'year' => '2024',
  ),
  44 => 
  array (
    'title' => 'Juara 2 F Pra Remaja Putra Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri 2024 - M. Sandy Saputra Pratama',
    'peraih' => 'M. Sandy Saputra Pratama',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-10-20 14:32:16',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri',
    'prestasi' => 'Juara 2 F Pra Remaja Putra',
    'year' => '2024',
  ),
  45 => 
  array (
    'title' => 'Juara 3 G Pra Remaja Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri 2024 - Naufa Ahza Athallah',
    'peraih' => 'Naufa Ahza Athallah',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-10-20 14:45:45',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri',
    'prestasi' => 'Juara 3 G Pra Remaja',
    'year' => '2024',
  ),
  46 => 
  array (
    'title' => 'Juara 2 C Pra Remaja Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri 2024 - M. Angga Syaputra',
    'peraih' => 'M. Angga Syaputra',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-10-20 14:58:14',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri',
    'prestasi' => 'Juara 2 C Pra Remaja',
    'year' => '2024',
  ),
  47 => 
  array (
    'title' => 'Juara 3 C Pra Remaja Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri 2024 - M. Fairuz Zaha Ardi Priyanto',
    'peraih' => 'M. Fairuz Zaha Ardi Priyanto',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-10-20 14:11:43',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Kejuaraan Pencak Silat Ketua Anshor Purwosari Kediri',
    'prestasi' => 'Juara 3 C Pra Remaja',
    'year' => '2024',
  ),
  48 => 
  array (
    'title' => 'Juara 2 Olimpiade PAI Festival Seni Islam SMAN SRENGAT 2024 - Sahal Muamar',
    'peraih' => 'Sahal Muamar',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2024-10-20 14:24:12',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Festival Seni Islam SMAN SRENGAT',
    'prestasi' => 'Juara 2 Olimpiade PAI',
    'year' => '2024',
  ),
  49 => 
  array (
    'title' => 'Juara 3 Olimpiade PAI Festival Seni Islam SMAN SRENGAT 2024 - Nadia Adni Asyifa',
    'peraih' => 'Nadia Adni Asyifa',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2024-10-20 14:37:41',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Festival Seni Islam SMAN SRENGAT',
    'prestasi' => 'Juara 3 Olimpiade PAI',
    'year' => '2024',
  ),
  50 => 
  array (
    'title' => 'Juara 1 bidang MIPA Olimpiade yang diselenggarakan oleh MAN 1 BLITAR 2024 - Sa’iidatus Sakinah',
    'peraih' => 'Sa’iidatus Sakinah',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-11-10 08:50:10',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Olimpiade yang diselenggarakan oleh MAN 1 BLITAR',
    'prestasi' => 'Juara 1 bidang MIPA',
    'year' => '2024',
  ),
  51 => 
  array (
    'title' => 'Juara Harapan 3 bidang MIPA Olimpiade yang diselenggarakan oleh MAN 1 BLITAR 2024 - Nabila Anindya Meydina',
    'peraih' => 'Nabila Anindya Meydina',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-11-10 08:03:39',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Olimpiade yang diselenggarakan oleh MAN 1 BLITAR',
    'prestasi' => 'Juara Harapan 3 bidang MIPA',
    'year' => '2024',
  ),
  52 => 
  array (
    'title' => 'Juara 1 bidang MIPS Olimpiade yang diselenggarakan oleh MAN 1 BLITAR 2024 - Addin Amalia',
    'peraih' => 'Addin Amalia',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-11-10 08:16:08',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Olimpiade yang diselenggarakan oleh MAN 1 BLITAR',
    'prestasi' => 'Juara 1 bidang MIPS',
    'year' => '2024',
  ),
  53 => 
  array (
    'title' => 'Juara Harapan 3 bidang MIPS Olimpiade yang diselenggarakan oleh MAN 1 BLITAR 2024 - Ainun Nissa Ramadhani',
    'peraih' => 'Ainun Nissa Ramadhani',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-11-10 08:29:37',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Olimpiade yang diselenggarakan oleh MAN 1 BLITAR',
    'prestasi' => 'Juara Harapan 3 bidang MIPS',
    'year' => '2024',
  ),
  54 => 
  array (
    'title' => 'Juara 3 bidang PAI Olimpiade yang diselenggarakan oleh MAN 1 BLITAR 2024 - Lenita Mashofi',
    'peraih' => 'Lenita Mashofi',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-11-10 09:42:06',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Olimpiade yang diselenggarakan oleh MAN 1 BLITAR',
    'prestasi' => 'Juara 3 bidang PAI',
    'year' => '2024',
  ),
  55 => 
  array (
    'title' => 'Juara Harapan 1 bidang PAI Olimpiade yang diselenggarakan oleh MAN 1 BLITAR 2024 - Ahmad Fikri',
    'peraih' => 'Ahmad Fikri',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-11-10 09:55:35',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Olimpiade yang diselenggarakan oleh MAN 1 BLITAR',
    'prestasi' => 'Juara Harapan 1 bidang PAI',
    'year' => '2024',
  ),
  56 => 
  array (
    'title' => 'Juara Harapan 2 bidang PAI Olimpiade yang diselenggarakan oleh MAN 1 BLITAR 2024 - Achmad Fajar Asshodicky',
    'peraih' => 'Achmad Fajar Asshodicky',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-11-10 09:08:04',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Olimpiade yang diselenggarakan oleh MAN 1 BLITAR',
    'prestasi' => 'Juara Harapan 2 bidang PAI',
    'year' => '2024',
  ),
  57 => 
  array (
    'title' => 'Special Award For Techonology And Innovation For Safety And Education Kompetisi Riset dan Inovasi Siswa Indonesia (KREASI) 2024 - Elfara Diah Erenata',
    'peraih' => 'Elfara Diah Erenata',
    'tingkat' => 'Nasional',
    'category' => 'riset',
    'date' => '2024-11-10 09:21:33',
    'ig_url' => 'https://www.instagram.com/p/DB8DR-DxXFU/',
    'ajang' => 'Kompetisi Riset dan Inovasi Siswa Indonesia (KREASI)',
    'prestasi' => 'Special Award For Techonology And Innovation For Safety And Education',
    'year' => '2024',
  ),
  58 => 
  array (
    'title' => 'Special Award Suistanable Agriculture and Enviromental Solution Kompetisi Riset dan Inovasi Siswa Indonesia (KREASI) 2024 - 1. Khansa Mazaya Kamilia',
    'peraih' => '1. Khansa Mazaya Kamilia',
    'tingkat' => 'Nasional',
    'category' => 'riset',
    'date' => '2024-11-10 10:34:02',
    'ig_url' => 'https://www.instagram.com/p/DB8DR-DxXFU/',
    'ajang' => 'Kompetisi Riset dan Inovasi Siswa Indonesia (KREASI)',
    'prestasi' => 'Special Award Suistanable Agriculture and Enviromental Solution',
    'year' => '2024',
  ),
  59 => 
  array (
    'title' => 'Juara 1 Sumo 1Kg Kejuaraan Robotik IRTC 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 10:47:31',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 1 Sumo 1Kg',
    'year' => '2024',
  ),
  60 => 
  array (
    'title' => 'Juara 1 LF Micro Kejuaraan Robotik IRTC 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 10:00:00',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 1 LF Micro',
    'year' => '2024',
  ),
  61 => 
  array (
    'title' => 'Juara 2 Game Scracth Kejuaraan Robotik IRTC 2024 - Ahmad Amir Ali Husaini',
    'peraih' => 'Ahmad Amir Ali Husaini',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 10:13:29',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 2 Game Scracth',
    'year' => '2024',
  ),
  62 => 
  array (
    'title' => 'Juara 2 LF Micro Kejuaraan Robotik IRTC 2024 - Keynan Gibran Nediva',
    'peraih' => 'Keynan Gibran Nediva',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 11:26:58',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 2 LF Micro',
    'year' => '2024',
  ),
  63 => 
  array (
    'title' => 'Juara 3 LF Micro Kejuaraan Robotik IRTC 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 11:39:27',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 3 LF Micro',
    'year' => '2024',
  ),
  64 => 
  array (
    'title' => 'Juara 2 Soccer 500gr Kejuaraan Robotik IRTC 2024 - 1. Hilwan Arkana M. Ghaly',
    'peraih' => '1. Hilwan Arkana M. Ghaly',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 11:52:56',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 2 Soccer 500gr',
    'year' => '2024',
  ),
  65 => 
  array (
    'title' => 'Juara 2 Sumo 200gr Kejuaraan Robotik IRTC 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 11:05:25',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 2 Sumo 200gr',
    'year' => '2024',
  ),
  66 => 
  array (
    'title' => 'Juara 2 Sumo 500gr Kejuaraan Robotik IRTC 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 12:18:54',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 2 Sumo 500gr',
    'year' => '2024',
  ),
  67 => 
  array (
    'title' => 'Juara 2 Sumo 5Kg Kejuaraan Robotik IRTC 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 12:31:23',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 2 Sumo 5Kg',
    'year' => '2024',
  ),
  68 => 
  array (
    'title' => 'Juara 3 Sumo 500Kg Kejuaraan Robotik IRTC 2024 - 1.',
    'peraih' => '1.',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 12:44:52',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 3 Sumo 500Kg',
    'year' => '2024',
  ),
  69 => 
  array (
    'title' => 'Juara 3 Soccer 500gr Kejuaraan Robotik IRTC 2024 - 1. Laist Ahmad Zamia',
    'peraih' => '1. Laist Ahmad Zamia',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 12:57:21',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara 3 Soccer 500gr',
    'year' => '2024',
  ),
  70 => 
  array (
    'title' => 'Juara Best Category Soccer 1kg Kejuaraan Robotik IRTC 2024 - 1. Akbar Antero Dhiyaulhaq',
    'peraih' => '1. Akbar Antero Dhiyaulhaq',
    'tingkat' => 'Nasional',
    'category' => 'robotik',
    'date' => '2024-11-10 13:10:50',
    'ig_url' => 'https://www.instagram.com/p/DBY47zFzlkN/',
    'ajang' => 'Kejuaraan Robotik IRTC',
    'prestasi' => 'Juara Best Category Soccer 1kg',
    'year' => '2024',
  ),
  71 => 
  array (
    'title' => 'Juara 3 Kelas C Putri Lomba Pencak Silat Dalam Rangka Milad MA ALMA CUP KE IV 2024 - Dinda Tri Yuana Dewi',
    'peraih' => 'Dinda Tri Yuana Dewi',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-11-10 13:23:19',
    'ig_url' => 'https://www.instagram.com/p/DBh4gvlTjCh/',
    'ajang' => 'Lomba Pencak Silat Dalam Rangka Milad MA ALMA CUP KE IV',
    'prestasi' => 'Juara 3 Kelas C Putri',
    'year' => '2024',
  ),
  72 => 
  array (
    'title' => 'Juara 3 Kelas G Putri Lomba Pencak Silat Dalam Rangka Milad MA ALMA CUP KE IV 2024 - Naufa Ahza Athallah',
    'peraih' => 'Naufa Ahza Athallah',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-11-10 13:36:48',
    'ig_url' => 'https://www.instagram.com/p/DBh4gvlTjCh/',
    'ajang' => 'Lomba Pencak Silat Dalam Rangka Milad MA ALMA CUP KE IV',
    'prestasi' => 'Juara 3 Kelas G Putri',
    'year' => '2024',
  ),
  73 => 
  array (
    'title' => 'Juara 3 Kelas H Putri Lomba Pencak Silat Dalam Rangka Milad MA ALMA CUP KE IV 2024 - Saskia Dwi Feranika',
    'peraih' => 'Saskia Dwi Feranika',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-11-10 13:49:17',
    'ig_url' => 'https://www.instagram.com/p/DBh4gvlTjCh/',
    'ajang' => 'Lomba Pencak Silat Dalam Rangka Milad MA ALMA CUP KE IV',
    'prestasi' => 'Juara 3 Kelas H Putri',
    'year' => '2024',
  ),
  74 => 
  array (
    'title' => 'Juara 2 Kelas F Putri Lomba Pencak Silat Dalam Rangka Milad MA ALMA CUP KE IV 2024 - Iffatul Mumtazah Affaf Izati',
    'peraih' => 'Iffatul Mumtazah Affaf Izati',
    'tingkat' => 'Provinsi',
    'category' => 'wushu_silat',
    'date' => '2024-11-10 14:02:46',
    'ig_url' => 'https://www.instagram.com/p/DBh4gvlTjCh/',
    'ajang' => 'Lomba Pencak Silat Dalam Rangka Milad MA ALMA CUP KE IV',
    'prestasi' => 'Juara 2 Kelas F Putri',
    'year' => '2024',
  ),
  75 => 
  array (
    'title' => 'Juara 1 MTQ PI MTQ PI di SMA 3 KOTA KEDIRI 2024 - Serly Celsiana Anggraini',
    'peraih' => 'Serly Celsiana Anggraini',
    'tingkat' => 'Provinsi',
    'category' => 'mtq_pidato',
    'date' => '2024-11-10 14:15:15',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'MTQ PI di SMA 3 KOTA KEDIRI',
    'prestasi' => 'Juara 1 MTQ PI',
    'year' => '2024',
  ),
  76 => 
  array (
    'title' => 'Juara Harapan 1 MTQ PI MTQ PI di SMA 3 KOTA KEDIRI 2024 - Salma Azimatul Khusna',
    'peraih' => 'Salma Azimatul Khusna',
    'tingkat' => 'Provinsi',
    'category' => 'mtq_pidato',
    'date' => '2024-11-10 14:28:44',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'MTQ PI di SMA 3 KOTA KEDIRI',
    'prestasi' => 'Juara Harapan 1 MTQ PI',
    'year' => '2024',
  ),
  77 => 
  array (
    'title' => 'Juara Harapan 2 Pidato Bahasa Indonesia Expo Literasi Madrasah (X LIMA) 2024 2024 - M. Mirzam Hafidzulloh',
    'peraih' => 'M. Mirzam Hafidzulloh',
    'tingkat' => 'Provinsi',
    'category' => 'mtq_pidato',
    'date' => '2024-11-10 14:41:13',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Expo Literasi Madrasah (X LIMA) 2024',
    'prestasi' => 'Juara Harapan 2 Pidato Bahasa Indonesia',
    'year' => '2024',
  ),
  78 => 
  array (
    'title' => 'Emas ke-1 Bidang Informatika Kompetisi Matmatika dan Ilmu Pengetahuan Nasional (KOMPAS) 2024 2024 - Muhammad Fadhlan Azhzhahir',
    'peraih' => 'Muhammad Fadhlan Azhzhahir',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2024-12-10 14:54:42',
    'ig_url' => 'https://www.instagram.com/p/DCO__4LTpn-/',
    'ajang' => 'Kompetisi Matmatika dan Ilmu Pengetahuan Nasional (KOMPAS) 2024',
    'prestasi' => 'Emas ke-1 Bidang Informatika',
    'year' => '2024',
  ),
  79 => 
  array (
    'title' => 'Emas ke-7 Bidang Informatika Kompetisi Matmatika dan Ilmu Pengetahuan Nasional (KOMPAS) 2024 2024 - Ahmat Nur Hidayat',
    'peraih' => 'Ahmat Nur Hidayat',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2024-12-10 15:07:11',
    'ig_url' => 'https://www.instagram.com/p/DCO__4LTpn-/',
    'ajang' => 'Kompetisi Matmatika dan Ilmu Pengetahuan Nasional (KOMPAS) 2024',
    'prestasi' => 'Emas ke-7 Bidang Informatika',
    'year' => '2024',
  ),
  80 => 
  array (
    'title' => 'Juara 1 Olimpiade IPA AKSIOMAS XXIII jenjang MTs/SMP yang diselenggarakan di SMAN 1 TALUN 2024 - Sa\'iidatus Sakinah',
    'peraih' => 'Sa\'iidatus Sakinah',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-12-10 15:20:40',
    'ig_url' => 'https://www.instagram.com/p/DCTBgEMTrcq/',
    'ajang' => 'AKSIOMAS XXIII jenjang MTs/SMP yang diselenggarakan di SMAN 1 TALUN',
    'prestasi' => 'Juara 1 Olimpiade IPA',
    'year' => '2024',
  ),
  81 => 
  array (
    'title' => 'Juara Harapan 2 Olimpiade IPA Sains (AKSI) Tk. SMP/MTs/Sederajat Milad ke-29 MAN 2 Blitar 2024 - Ines Felisa Putri',
    'peraih' => 'Ines Felisa Putri',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-12-10 15:33:09',
    'ig_url' => 'https://www.instagram.com/p/DCVLGFyTBBA/',
    'ajang' => 'Sains (AKSI) Tk. SMP/MTs/Sederajat Milad ke-29 MAN 2 Blitar',
    'prestasi' => 'Juara Harapan 2 Olimpiade IPA',
    'year' => '2024',
  ),
  82 => 
  array (
    'title' => 'Juara Harapan 3 Olimpiade IPA IPA Sains (AKSI) Tk. SMP/MTs/Sederajat Milad ke-29 MAN 2 Blitar 2024 - Fuji Ulfa Fariana',
    'peraih' => 'Fuji Ulfa Fariana',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2024-12-10 15:46:38',
    'ig_url' => 'https://www.instagram.com/p/DCVLGFyTBBA/',
    'ajang' => 'IPA Sains (AKSI) Tk. SMP/MTs/Sederajat Milad ke-29 MAN 2 Blitar',
    'prestasi' => 'Juara Harapan 3 Olimpiade IPA',
    'year' => '2024',
  ),
  83 => 
  array (
    'title' => 'Meraih Medali Emas Bidang Informatika ONSB (Olimpiade Nasional Sains dan Bahasa) 2025 2025 - Muhammad Fadhlan Azzahir',
    'peraih' => 'Muhammad Fadhlan Azzahir',
    'tingkat' => 'Nasional',
    'category' => 'mtq_pidato',
    'date' => '2025-01-28 16:59:07',
    'ig_url' => 'https://www.instagram.com/p/DDZqcrdTM0E/',
    'ajang' => 'ONSB (Olimpiade Nasional Sains dan Bahasa) 2025',
    'prestasi' => 'Meraih Medali Emas Bidang Informatika',
    'year' => '2025',
  ),
  84 => 
  array (
    'title' => 'Meraih Medali Emas Bidang Informatika PESONA (Pekan Sains dan Olimpiade Nasional) 2025 2025 - Ahmat Nur Hidayat',
    'peraih' => 'Ahmat Nur Hidayat',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-01-28 16:12:36',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'PESONA (Pekan Sains dan Olimpiade Nasional) 2025',
    'prestasi' => 'Meraih Medali Emas Bidang Informatika',
    'year' => '2025',
  ),
  85 => 
  array (
    'title' => 'Juara 1 MTQ Putri Festival Pelajar Muslim (FPM) ke-X 2025 - Serly Celsiana Anggraini',
    'peraih' => 'Serly Celsiana Anggraini',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-01-28 16:25:05',
    'ig_url' => 'https://www.instagram.com/p/DDXLSL4TPal/',
    'ajang' => 'Festival Pelajar Muslim (FPM) ke-X',
    'prestasi' => 'Juara 1 MTQ Putri',
    'year' => '2025',
  ),
  86 => 
  array (
    'title' => 'Juara 3 MTQ Putra Festival Pelajar Muslim (FPM) ke-X 2025 - M. Mirza Bahri Al Azzami',
    'peraih' => 'M. Mirza Bahri Al Azzami',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-01-28 16:38:34',
    'ig_url' => 'https://www.instagram.com/p/DDXLSL4TPal/',
    'ajang' => 'Festival Pelajar Muslim (FPM) ke-X',
    'prestasi' => 'Juara 3 MTQ Putra',
    'year' => '2025',
  ),
  87 => 
  array (
    'title' => 'Juara 1 Cabang Lomba IPA OLYMPIADE SCIENCE SOCIAL AND MATHEMATIC (OSSMA) dalam Rangka HUT ke-8 MAN 2 KOTA KEDIRI 2025 - Sa\'iidatus Sakinah',
    'peraih' => 'Sa\'iidatus Sakinah',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 17:51:03',
    'ig_url' => 'https://www.instagram.com/p/DDZqcrxTxVd/',
    'ajang' => 'OLYMPIADE SCIENCE SOCIAL AND MATHEMATIC (OSSMA) dalam Rangka HUT ke-8 MAN 2 KOTA KEDIRI',
    'prestasi' => 'Juara 1 Cabang Lomba IPA',
    'year' => '2025',
  ),
  88 => 
  array (
    'title' => 'Emas ke-2 Bidang Informatika PEKAN OLIMPIADE SAINS NASIONAL 2025 2025 - Ahmat Nur Hidayat',
    'peraih' => 'Ahmat Nur Hidayat',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 17:04:32',
    'ig_url' => 'https://www.instagram.com/p/DFufxDURGcF/',
    'ajang' => 'PEKAN OLIMPIADE SAINS NASIONAL 2025',
    'prestasi' => 'Emas ke-2 Bidang Informatika',
    'year' => '2025',
  ),
  89 => 
  array (
    'title' => 'Emas ke-5 Bidang Informatika PEKAN OLIMPIADE SAINS NASIONAL 2026 2025 - Muhammad Fadhlan Azzahir',
    'peraih' => 'Muhammad Fadhlan Azzahir',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 17:17:01',
    'ig_url' => 'https://www.instagram.com/p/DFufxDURGcF/',
    'ajang' => 'PEKAN OLIMPIADE SAINS NASIONAL 2026',
    'prestasi' => 'Emas ke-5 Bidang Informatika',
    'year' => '2025',
  ),
  90 => 
  array (
    'title' => 'Emas ke-6 Bidang Informatika PEKAN OLIMPIADE SAINS NASIONAL 2027 2025 - Azzaki Ganendra',
    'peraih' => 'Azzaki Ganendra',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 17:30:30',
    'ig_url' => 'https://www.instagram.com/p/DFufxDURGcF/',
    'ajang' => 'PEKAN OLIMPIADE SAINS NASIONAL 2027',
    'prestasi' => 'Emas ke-6 Bidang Informatika',
    'year' => '2025',
  ),
  91 => 
  array (
    'title' => 'Meraih Medali Perunggu Bidang Studi Bahasa Indonesia KOMPETISI PRESTASI PELAJAR NASIONAL 2025 - Hana Oktavia Anggraini',
    'peraih' => 'Hana Oktavia Anggraini',
    'tingkat' => 'Nasional',
    'category' => 'mtq_pidato',
    'date' => '2025-02-06 14:43:59',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'KOMPETISI PRESTASI PELAJAR NASIONAL',
    'prestasi' => 'Meraih Medali Perunggu Bidang Studi Bahasa Indonesia',
    'year' => '2025',
  ),
  92 => 
  array (
    'title' => 'Meraih Rank 2 Mapel IPA ISLAMIC SCIENCE OLYMPIAD 2025 - Muhammad Fadhlan Azzahir',
    'peraih' => 'Muhammad Fadhlan Azzahir',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 14:56:28',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ISLAMIC SCIENCE OLYMPIAD',
    'prestasi' => 'Meraih Rank 2 Mapel IPA',
    'year' => '2025',
  ),
  93 => 
  array (
    'title' => 'Meraih Rank 7 Mapel IPA ISLAMIC SCIENCE OLYMPIAD 2025 - Azzaki Ganendra',
    'peraih' => 'Azzaki Ganendra',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 14:09:57',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ISLAMIC SCIENCE OLYMPIAD',
    'prestasi' => 'Meraih Rank 7 Mapel IPA',
    'year' => '2025',
  ),
  94 => 
  array (
    'title' => 'Juara 1 Cabang Olimpiade IPA OMMK 2025 (Olimpiade MIPA, MIPS, KEAGAMAAN) 2025 - Sa\'iidatus Sakinah',
    'peraih' => 'Sa\'iidatus Sakinah',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 14:22:26',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'OMMK 2025 (Olimpiade MIPA, MIPS, KEAGAMAAN)',
    'prestasi' => 'Juara 1 Cabang Olimpiade IPA',
    'year' => '2025',
  ),
  95 => 
  array (
    'title' => 'Juara 3 Cabang Olimpiade MIPS OMMK 2025 (Olimpiade MIPA, MIPS, KEAGAMAAN) 2025 - Addin Amalia',
    'peraih' => 'Addin Amalia',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 14:35:55',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'OMMK 2025 (Olimpiade MIPA, MIPS, KEAGAMAAN)',
    'prestasi' => 'Juara 3 Cabang Olimpiade MIPS',
    'year' => '2025',
  ),
  96 => 
  array (
    'title' => 'Juara 3 Cabang Olimpiade Agama OMMK 2025 (Olimpiade MIPA, MIPS, KEAGAMAAN) 2025 - Lenita Mashofi',
    'peraih' => 'Lenita Mashofi',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 14:48:24',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'OMMK 2025 (Olimpiade MIPA, MIPS, KEAGAMAAN)',
    'prestasi' => 'Juara 3 Cabang Olimpiade Agama',
    'year' => '2025',
  ),
  97 => 
  array (
    'title' => 'Juara Harapan 1 Cabang Olimpiade Agama OMMK 2025 (Olimpiade MIPA, MIPS, KEAGAMAAN) 2025 - Ilma Sabrinatuz Z.',
    'peraih' => 'Ilma Sabrinatuz Z.',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 14:01:53',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'OMMK 2025 (Olimpiade MIPA, MIPS, KEAGAMAAN)',
    'prestasi' => 'Juara Harapan 1 Cabang Olimpiade Agama',
    'year' => '2025',
  ),
  98 => 
  array (
    'title' => 'Juara Harapan 1 Tahfid Putra PORSENI Tingkat MTs 2025 2025 - M. Irsad Junianto',
    'peraih' => 'M. Irsad Junianto',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 14:14:22',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 1 Tahfid Putra',
    'year' => '2025',
  ),
  99 => 
  array (
    'title' => 'Juara Harapan 1 Pidato Bahasa Indonesia Putri PORSENI Tingkat MTs 2025 2025 - Nashwa Haziq Satia Putri',
    'peraih' => 'Nashwa Haziq Satia Putri',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-02-06 08:27:51',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 1 Pidato Bahasa Indonesia Putri',
    'year' => '2025',
  ),
  100 => 
  array (
    'title' => 'Juara Harapan 2 Pidato Bahasa Inggris Putra PORSENI Tingkat MTs 2025 2025 - Moch. Justine Putra Permana',
    'peraih' => 'Moch. Justine Putra Permana',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-02-06 08:40:20',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 2 Pidato Bahasa Inggris Putra',
    'year' => '2025',
  ),
  101 => 
  array (
    'title' => 'Juara Harapan 1 VLOG Putri PORSENI Tingkat MTs 2025 2025 - Talitha Rifia Nariswari',
    'peraih' => 'Talitha Rifia Nariswari',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 08:53:49',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 1 VLOG Putri',
    'year' => '2025',
  ),
  102 => 
  array (
    'title' => 'Juara Harapan 2 VLOG Putra PORSENI Tingkat MTs 2025 2025 - Faza Fikaunin Ahmad',
    'peraih' => 'Faza Fikaunin Ahmad',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 08:06:18',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 2 VLOG Putra',
    'year' => '2025',
  ),
  103 => 
  array (
    'title' => 'Juara Harapan 2 Lompat Jauh Putra PORSENI Tingkat MTs 2025 2025 - Yoga Andaru Nurhuda',
    'peraih' => 'Yoga Andaru Nurhuda',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 09:19:47',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 2 Lompat Jauh Putra',
    'year' => '2025',
  ),
  104 => 
  array (
    'title' => 'Juara Harapan 3 Pidato Bahasa Arab Putra PORSENI Tingkat MTs 2025 2025 - Azzaki Ganendra',
    'peraih' => 'Azzaki Ganendra',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-02-06 09:32:16',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 3 Pidato Bahasa Arab Putra',
    'year' => '2025',
  ),
  105 => 
  array (
    'title' => 'Juara Harapan 2 MTQ Putra PORSENI Tingkat MTs 2025 2025 - M. Mirza Bahri Al Azzami',
    'peraih' => 'M. Mirza Bahri Al Azzami',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-02-06 09:45:45',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 2 MTQ Putra',
    'year' => '2025',
  ),
  106 => 
  array (
    'title' => 'Juara Harapan 1 Cabang Lomba Voli PORSENI Tingkat MTs 2025 2025 - Tim Bola Voli Putra',
    'peraih' => 'Tim Bola Voli Putra',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 09:58:14',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara Harapan 1 Cabang Lomba Voli',
    'year' => '2025',
  ),
  107 => 
  array (
    'title' => 'Juara 3 Pidato Bahasa Indonesia Putra PORSENI Tingkat MTs 2025 2025 - Mohammad Luqmanul Hakim',
    'peraih' => 'Mohammad Luqmanul Hakim',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-02-06 10:11:43',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 3 Pidato Bahasa Indonesia Putra',
    'year' => '2025',
  ),
  108 => 
  array (
    'title' => 'Juara 3 Singer Putri PORSENI Tingkat MTs 2025 2025 - Aqila Syahdan Al Halwa',
    'peraih' => 'Aqila Syahdan Al Halwa',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 10:24:12',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 3 Singer Putri',
    'year' => '2025',
  ),
  109 => 
  array (
    'title' => 'Juara 3 Singer Putra PORSENI Tingkat MTs 2025 2025 - Gilang Mahir Juniawan',
    'peraih' => 'Gilang Mahir Juniawan',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 10:37:41',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 3 Singer Putra',
    'year' => '2025',
  ),
  110 => 
  array (
    'title' => 'Juara 3 Kaligrafi Kontemporer Putra PORSENI Tingkat MTs 2025 2025 - Muhammad Hardika Samnanda',
    'peraih' => 'Muhammad Hardika Samnanda',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 10:50:10',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 3 Kaligrafi Kontemporer Putra',
    'year' => '2025',
  ),
  111 => 
  array (
    'title' => 'Juara 3 Lompat Jauh Putra PORSENI Tingkat MTs 2025 2025 - M. Rizqi Izza Abadan',
    'peraih' => 'M. Rizqi Izza Abadan',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 11:03:39',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 3 Lompat Jauh Putra',
    'year' => '2025',
  ),
  112 => 
  array (
    'title' => 'Juara 3 Lari 100 M Putri PORSENI Tingkat MTs 2025 2025 - Mia Shellyanti',
    'peraih' => 'Mia Shellyanti',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 11:16:08',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 3 Lari 100 M Putri',
    'year' => '2025',
  ),
  113 => 
  array (
    'title' => 'Juara 3 Seni Pencak Silat Ganda Putra PORSENI Tingkat MTs 2025 2025 - M. Fairuz Zaha Ardi Priyanto',
    'peraih' => 'M. Fairuz Zaha Ardi Priyanto',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'wushu_silat',
    'date' => '2025-02-06 11:29:37',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 3 Seni Pencak Silat Ganda Putra',
    'year' => '2025',
  ),
  114 => 
  array (
    'title' => 'Juara 3 Seni Pencak Silat Ganda Putra PORSENI Tingkat MTs 2025 2025 - Muhammad Angga Syaputra',
    'peraih' => 'Muhammad Angga Syaputra',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'wushu_silat',
    'date' => '2025-02-06 11:42:06',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 3 Seni Pencak Silat Ganda Putra',
    'year' => '2025',
  ),
  115 => 
  array (
    'title' => 'Juara 2 Bulitangkis Ganda Putri PORSENI Tingkat MTs 2025 2025 - Zidna Zawaida Az Zahra',
    'peraih' => 'Zidna Zawaida Az Zahra',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 11:55:35',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 2 Bulitangkis Ganda Putri',
    'year' => '2025',
  ),
  116 => 
  array (
    'title' => 'Juara 2 Bulitangkis Ganda Putri PORSENI Tingkat MTs 2025 2025 - Alisa Libia LS',
    'peraih' => 'Alisa Libia LS',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 12:08:04',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 2 Bulitangkis Ganda Putri',
    'year' => '2025',
  ),
  117 => 
  array (
    'title' => 'Juara 2 Pidato Bahasa Arab Putra PORSENI Tingkat MTs 2025 2025 - M. Dafa Maulana Muftofa',
    'peraih' => 'M. Dafa Maulana Muftofa',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-02-06 12:21:33',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 2 Pidato Bahasa Arab Putra',
    'year' => '2025',
  ),
  118 => 
  array (
    'title' => 'Juara 2 Tenis Meja Ganda Putra PORSENI Tingkat MTs 2025 2025 - Javier Faizul Ibaadurrachman',
    'peraih' => 'Javier Faizul Ibaadurrachman',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 12:34:02',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 2 Tenis Meja Ganda Putra',
    'year' => '2025',
  ),
  119 => 
  array (
    'title' => 'Juara 2 Tenis Meja Ganda Putra PORSENI Tingkat MTs 2025 2025 - Muhammad Azril Nizam',
    'peraih' => 'Muhammad Azril Nizam',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 12:47:31',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 2 Tenis Meja Ganda Putra',
    'year' => '2025',
  ),
  120 => 
  array (
    'title' => 'Juara 2 Lari 100 M Putra PORSENI Tingkat MTs 2025 2025 - Muhammad Nur Jordan',
    'peraih' => 'Muhammad Nur Jordan',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 13:00:00',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 2 Lari 100 M Putra',
    'year' => '2025',
  ),
  121 => 
  array (
    'title' => 'Juara 2 Seni Pencak Silat Ganda Putri PORSENI Tingkat MTs 2025 2025 - Maha\' Biyadil Ngafafi Asunul Jismi',
    'peraih' => 'Maha\' Biyadil Ngafafi Asunul Jismi',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'wushu_silat',
    'date' => '2025-02-06 13:13:29',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 2 Seni Pencak Silat Ganda Putri',
    'year' => '2025',
  ),
  122 => 
  array (
    'title' => 'Juara 2 Seni Pencak Silat Ganda Putri PORSENI Tingkat MTs 2025 2025 - Elisa Lisa Lusiana Saputri',
    'peraih' => 'Elisa Lisa Lusiana Saputri',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'wushu_silat',
    'date' => '2025-02-06 13:26:58',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 2 Seni Pencak Silat Ganda Putri',
    'year' => '2025',
  ),
  123 => 
  array (
    'title' => 'Juara 1 Bulutangkis Tunggal Putra PORSENI Tingkat MTs 2025 2025 - Abhista Azhar Zaiddan',
    'peraih' => 'Abhista Azhar Zaiddan',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'bulutangkis',
    'date' => '2025-02-06 13:39:27',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 1 Bulutangkis Tunggal Putra',
    'year' => '2025',
  ),
  124 => 
  array (
    'title' => 'Juara 1 Catur Putra PORSENI Tingkat MTs 2025 2025 - M. Fadhil Zulkarnain',
    'peraih' => 'M. Fadhil Zulkarnain',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'catur',
    'date' => '2025-02-06 14:52:56',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 1 Catur Putra',
    'year' => '2025',
  ),
  125 => 
  array (
    'title' => 'Juara 1 MTQ Putri PORSENI Tingkat MTs 2025 2025 - Serly Celsiana Anggraini',
    'peraih' => 'Serly Celsiana Anggraini',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'mtq_pidato',
    'date' => '2025-02-06 14:05:25',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 1 MTQ Putri',
    'year' => '2025',
  ),
  126 => 
  array (
    'title' => 'Juara 1 Tahfid Putri PORSENI Tingkat MTs 2025 2025 - Alfina Syahira Safa Anwar',
    'peraih' => 'Alfina Syahira Safa Anwar',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 14:18:54',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 1 Tahfid Putri',
    'year' => '2025',
  ),
  127 => 
  array (
    'title' => 'Juara 1 Bulutangkis Ganda Putra PORSENI Tingkat MTs 2025 2025 - Muhammad Azka Al Khadafi',
    'peraih' => 'Muhammad Azka Al Khadafi',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'bulutangkis',
    'date' => '2025-02-06 14:31:23',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 1 Bulutangkis Ganda Putra',
    'year' => '2025',
  ),
  128 => 
  array (
    'title' => 'Juara 1 Bulutangkis Ganda Putra PORSENI Tingkat MTs 2025 2025 - Adhi Wijaya Budiana',
    'peraih' => 'Adhi Wijaya Budiana',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'bulutangkis',
    'date' => '2025-02-06 15:44:52',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 1 Bulutangkis Ganda Putra',
    'year' => '2025',
  ),
  129 => 
  array (
    'title' => 'Juara 1 Cabang Lomba Futsal PORSENI Tingkat MTs 2025 2025 - Tim Futsal MTsN 1 Blitar',
    'peraih' => 'Tim Futsal MTsN 1 Blitar',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 15:57:21',
    'ig_url' => 'https://www.instagram.com/p/DFuuQ97z6Qa/',
    'ajang' => 'PORSENI Tingkat MTs 2025',
    'prestasi' => 'Juara 1 Cabang Lomba Futsal',
    'year' => '2025',
  ),
  130 => 
  array (
    'title' => 'Juara 3 Perintis Lomba Keterampilan Baris Ber-baris Laskar 8 2025 - Tim Paskibraka MTsN 1 Blitar',
    'peraih' => 'Tim Paskibraka MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 15:10:50',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'Lomba Keterampilan Baris Ber-baris Laskar 8',
    'prestasi' => 'Juara 3 Perintis',
    'year' => '2025',
  ),
  131 => 
  array (
    'title' => 'Juara UMUM 2 Regu Putra ARYA PRAGA VI 2025 2025 - ARPRAMA MTsN 1 Blitar',
    'peraih' => 'ARPRAMA MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 15:23:19',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ARYA PRAGA VI 2025',
    'prestasi' => 'Juara UMUM 2 Regu Putra',
    'year' => '2025',
  ),
  132 => 
  array (
    'title' => 'Juara 2 Rangking 1 Putra ARYA PRAGA VI 2026 2025 - Sahal Muamar',
    'peraih' => 'Sahal Muamar',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 16:36:48',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ARYA PRAGA VI 2026',
    'prestasi' => 'Juara 2 Rangking 1 Putra',
    'year' => '2025',
  ),
  133 => 
  array (
    'title' => 'Juara 2 PPGD Putra ARYA PRAGA VI 2027 2025 - ARPRAMA MTsN 1 Blitar',
    'peraih' => 'ARPRAMA MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 16:49:17',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ARYA PRAGA VI 2027',
    'prestasi' => 'Juara 2 PPGD Putra',
    'year' => '2025',
  ),
  134 => 
  array (
    'title' => 'Juara 3 Videografi Putra ARYA PRAGA VI 2028 2025 - ARPRAMA MTsN 1 Blitar',
    'peraih' => 'ARPRAMA MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 16:02:46',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ARYA PRAGA VI 2028',
    'prestasi' => 'Juara 3 Videografi Putra',
    'year' => '2025',
  ),
  135 => 
  array (
    'title' => 'Juara 3 Sandi Morse Semaphore Putri ARYA PRAGA VI 2029 2025 - ARPRAMA MTsN 1 Blitar',
    'peraih' => 'ARPRAMA MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-02-06 16:15:15',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ARYA PRAGA VI 2029',
    'prestasi' => 'Juara 3 Sandi Morse Semaphore Putri',
    'year' => '2025',
  ),
  136 => 
  array (
    'title' => 'Juara 1 MICO SCIENTIFIC RESEARCH COMPETITION (MICO) 2025 MAN 2 KOTA KEDIRI 2025 - 1. Adziqni Khalawata Dzikri',
    'peraih' => '1. Adziqni Khalawata Dzikri',
    'tingkat' => 'Provinsi',
    'category' => 'riset',
    'date' => '2025-02-06 17:28:44',
    'ig_url' => 'https://www.instagram.com/p/C4GEVClpEMO/',
    'ajang' => 'SCIENTIFIC RESEARCH COMPETITION (MICO) 2025 MAN 2 KOTA KEDIRI',
    'prestasi' => 'Juara 1 MICO',
    'year' => '2025',
  ),
  137 => 
  array (
    'title' => 'Best Expo MICO SCIENTIFIC RESEARCH COMPETITION (MICO) 2025 MAN 2 KOTA KEDIRI 2025 - Tim Robotik MTsN 1 Blitar',
    'peraih' => 'Tim Robotik MTsN 1 Blitar',
    'tingkat' => 'Provinsi',
    'category' => 'riset',
    'date' => '2025-02-06 17:41:13',
    'ig_url' => 'https://www.instagram.com/p/C4GEVClpEMO/',
    'ajang' => 'SCIENTIFIC RESEARCH COMPETITION (MICO) 2025 MAN 2 KOTA KEDIRI',
    'prestasi' => 'Best Expo MICO',
    'year' => '2025',
  ),
  138 => 
  array (
    'title' => 'Meraih Medali Emas Bidang Saintek KOMPAS NASIONAL 2025 2025 - 1. Kenzi Rizqulloh Putra W.',
    'peraih' => '1. Kenzi Rizqulloh Putra W.',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-03-04 17:54:42',
    'ig_url' => 'https://www.instagram.com/p/DCO__4LTpn-/',
    'ajang' => 'KOMPAS NASIONAL 2025',
    'prestasi' => 'Meraih Medali Emas Bidang Saintek',
    'year' => '2025',
  ),
  139 => 
  array (
    'title' => 'Meraih Medali Perak Bidang Agama KOMPAS NASIONAL 2025 2025 - 1. Aqila Khanza Rahmani',
    'peraih' => '1. Aqila Khanza Rahmani',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-03-04 17:07:11',
    'ig_url' => 'https://www.instagram.com/p/DCO__4LTpn-/',
    'ajang' => 'KOMPAS NASIONAL 2025',
    'prestasi' => 'Meraih Medali Perak Bidang Agama',
    'year' => '2025',
  ),
  140 => 
  array (
    'title' => 'Meraih Medali Perunggu Bidang Soshum KOMPAS NASIONAL 2025 2025 - 1. Milatul Fitria',
    'peraih' => '1. Milatul Fitria',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-03-04 14:20:40',
    'ig_url' => 'https://www.instagram.com/p/DCO__4LTpn-/',
    'ajang' => 'KOMPAS NASIONAL 2025',
    'prestasi' => 'Meraih Medali Perunggu Bidang Soshum',
    'year' => '2025',
  ),
  141 => 
  array (
    'title' => 'Meraih Medali Perunggu Bidang Saintek KOMPAS NASIONAL 2025 2025 - 1. Faza Fikaunin Ahmad',
    'peraih' => '1. Faza Fikaunin Ahmad',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-03-04 14:33:09',
    'ig_url' => 'https://www.instagram.com/p/DCO__4LTpn-/',
    'ajang' => 'KOMPAS NASIONAL 2025',
    'prestasi' => 'Meraih Medali Perunggu Bidang Saintek',
    'year' => '2025',
  ),
  142 => 
  array (
    'title' => 'Meraih Penghargaan Khusus Ramah Disabilitas KOMPAS NASIONAL 2025 2025 - 1. Ahmat Nur Hidayat',
    'peraih' => '1. Ahmat Nur Hidayat',
    'tingkat' => 'Nasional',
    'category' => 'sains_mipa',
    'date' => '2025-03-04 14:46:38',
    'ig_url' => 'https://www.instagram.com/p/DCO__4LTpn-/',
    'ajang' => 'KOMPAS NASIONAL 2025',
    'prestasi' => 'Meraih Penghargaan Khusus Ramah Disabilitas',
    'year' => '2025',
  ),
  143 => 
  array (
    'title' => 'Juara 1 MTQ APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Serly Celsiana Anggraini',
    'peraih' => 'Serly Celsiana Anggraini',
    'tingkat' => 'Provinsi',
    'category' => 'mtq_pidato',
    'date' => '2025-04-18 14:59:07',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara 1 MTQ',
    'year' => '2025',
  ),
  144 => 
  array (
    'title' => 'Juara Harapan 3 MTQ APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Annisa Qurrota A.',
    'peraih' => 'Annisa Qurrota A.',
    'tingkat' => 'Provinsi',
    'category' => 'mtq_pidato',
    'date' => '2025-04-18 14:12:36',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara Harapan 3 MTQ',
    'year' => '2025',
  ),
  145 => 
  array (
    'title' => 'Juara 1 Mapel PAI APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Lenita Mashofi',
    'peraih' => 'Lenita Mashofi',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 14:25:05',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara 1 Mapel PAI',
    'year' => '2025',
  ),
  146 => 
  array (
    'title' => 'Juara 1 Mapel IPS APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Addin Amalia',
    'peraih' => 'Addin Amalia',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 14:38:34',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara 1 Mapel IPS',
    'year' => '2025',
  ),
  147 => 
  array (
    'title' => 'Juara 2 Mapel IPA APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - M. Fadhlan Azzahir',
    'peraih' => 'M. Fadhlan Azzahir',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 14:51:03',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara 2 Mapel IPA',
    'year' => '2025',
  ),
  148 => 
  array (
    'title' => 'Juara 3 Mapel PAI APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Ilma Sabrinatuz Z.',
    'peraih' => 'Ilma Sabrinatuz Z.',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 08:04:32',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara 3 Mapel PAI',
    'year' => '2025',
  ),
  149 => 
  array (
    'title' => 'Juara Harapan 1 Mapel PAI APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Fatyah Fuadatun N.',
    'peraih' => 'Fatyah Fuadatun N.',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 08:17:01',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara Harapan 1 Mapel PAI',
    'year' => '2025',
  ),
  150 => 
  array (
    'title' => 'Juara 2 Lomba Puisi APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Wildan Khubi Nur A.',
    'peraih' => 'Wildan Khubi Nur A.',
    'tingkat' => 'Provinsi',
    'category' => 'mtq_pidato',
    'date' => '2025-04-18 08:30:30',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara 2 Lomba Puisi',
    'year' => '2025',
  ),
  151 => 
  array (
    'title' => 'Juara Harapan 1 Mapel IPS APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Ahmat Nur Hidayat',
    'peraih' => 'Ahmat Nur Hidayat',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 08:43:59',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara Harapan 1 Mapel IPS',
    'year' => '2025',
  ),
  152 => 
  array (
    'title' => 'Juara Harapan 3 Mapel Matematika APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - M. Faris Hafish Abdullah',
    'peraih' => 'M. Faris Hafish Abdullah',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 08:56:28',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara Harapan 3 Mapel Matematika',
    'year' => '2025',
  ),
  153 => 
  array (
    'title' => 'Juara Harapan 3 Mapel PAI APRESIASI 7 Harlah ke-55 MAN Kota Blitar 2025 - Sahal Muamar',
    'peraih' => 'Sahal Muamar',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 09:09:57',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'APRESIASI 7 Harlah ke-55 MAN Kota Blitar',
    'prestasi' => 'Juara Harapan 3 Mapel PAI',
    'year' => '2025',
  ),
  154 => 
  array (
    'title' => 'Juara 2 Esai Bidang Ilmu Sains dan Teknologi ESAI MORPH 2025 DI MAN 2 KOTA MALANG 2025 - 1. Ahmat Nur Hidayat',
    'peraih' => '1. Ahmat Nur Hidayat',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 09:22:26',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ESAI MORPH 2025 DI MAN 2 KOTA MALANG',
    'prestasi' => 'Juara 2 Esai Bidang Ilmu Sains dan Teknologi',
    'year' => '2025',
  ),
  155 => 
  array (
    'title' => 'Juara Harapan 2 Esai Bidang Ilmu Sains dan Teknologi ESAI MORPH 2025 DI MAN 2 KOTA MALANG 2025 - 1. Khansa Mazaya Kamilia',
    'peraih' => '1. Khansa Mazaya Kamilia',
    'tingkat' => 'Provinsi',
    'category' => 'sains_mipa',
    'date' => '2025-04-18 09:35:55',
    'ig_url' => 'https://www.instagram.com/mtsn1blitar69/',
    'ajang' => 'ESAI MORPH 2025 DI MAN 2 KOTA MALANG',
    'prestasi' => 'Juara Harapan 2 Esai Bidang Ilmu Sains dan Teknologi',
    'year' => '2025',
  ),
  156 => 
  array (
    'title' => 'Juara 3 Agribisnis & Energi (Alat Pemilah Sampah IoT) Lomba Kreativitas dan Inovasi Teknologi (KRENOTEK) Kab. Blitar 2025 2025 - Sa\'iidatus Sakinah, Khansa Naflah, Elsa Ghina',
    'peraih' => 'Sa\'iidatus Sakinah, Khansa Naflah, Elsa Ghina',
    'tingkat' => 'Kabupaten/Kota',
    'category' => 'krenotek',
    'date' => '2025-07-29 09:48:24',
    'ig_url' => 'https://www.instagram.com/p/DLru60nxwGV/',
    'ajang' => 'Lomba Kreativitas dan Inovasi Teknologi (KRENOTEK) Kab. Blitar 2025',
    'prestasi' => 'Juara 3 Agribisnis & Energi (Alat Pemilah Sampah IoT)',
    'year' => '2025',
  ),
);

    foreach ( $items as $item ) {
        $title = $item['title'];
        $existing_post = get_posts( array(
            'post_type'      => 'prestasi',
            'title'          => $title,
            'posts_per_page' => 1,
            'post_status'    => 'any',
        ) );

        if ( ! empty( $existing_post ) ) {
            continue;
        }

        $cleanNama     = $item['peraih'];
        $cleanPrestasi = $item['prestasi'];
        $cleanAjang    = $item['ajang'];
        $tingkat       = $item['tingkat'];
        $year          = $item['year'];
        $postDate      = $item['date'];
        $igUrl         = $item['ig_url'];
        $cat           = $item['category'];

        $thumbId = isset( $attachment_ids[$cat] ) ? $attachment_ids[$cat] : ( isset( $attachment_ids['sains_mipa'] ) ? $attachment_ids['sains_mipa'] : 0 );

        $content  = "<p>Alhamdulillah, segenap keluarga besar MTsN 1 Blitar mengucapkan selamat dan sukses atas torehan prestasi membanggakan yang diraih oleh <strong>" . esc_html( $cleanNama ) . "</strong> sebagai <strong>" . esc_html( $cleanPrestasi ) . "</strong> dalam ajang <strong>" . esc_html( $cleanAjang ) . "</strong> Tingkat " . esc_html( $tingkat ) . " (" . esc_html( $year ) . ").</p>\n";
        $content .= "<p>Semoga prestasi ini membawa keberkahan dan terus menjadi inspirasi serta motivasi bagi seluruh siswa-siswi MTsN 1 Blitar untuk senantiasa berprestasi, berakhlak mulia, dan menjunjung tinggi nama baik madrasah di tingkat regional, nasional, maupun internasional.</p>";

        $post_id = wp_insert_post( array(
            'post_title'    => $title,
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_type'     => 'prestasi',
            'post_author'   => 1,
            'post_date'     => $postDate,
            'post_date_gmt' => get_gmt_from_date( $postDate ),
            'meta_input'    => array(
                '_peraih'          => $cleanNama,
                '_nama_siswa'      => $cleanNama,
                '_tingkat'         => $tingkat,
                'tingkat_prestasi' => $tingkat,
                '_kategori_prestasi' => $cat,
                '_instagram_url'   => $igUrl,
                '_thumbnail_id'    => $thumbId,
            ),
        ) );

        if ( $post_id && ! is_wp_error( $post_id ) && $thumbId ) {
            set_post_thumbnail( $post_id, $thumbId );
        }
    }

    update_option( 'mtsn1_prestasi_sync_version_2024_2025', $sync_version );
}
