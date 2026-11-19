<?php
/**
 * Template Name: Statistik Publikasi
 * Description: Dashboard Rekapitulasi & Statistik Publikasi Berita serta Capaian Prestasi MTsN 1 Blitar
 */

get_header();

global $wpdb;

// 1. Data Ringkasan Utama
$total_berita   = (int) wp_count_posts( 'post' )->publish;
$total_prestasi = (int) wp_count_posts( 'prestasi' )->publish;

// 2. Data Rincian Tingkat Prestasi
$tingkat_raw = $wpdb->get_results(
    "SELECT LOWER(TRIM(pm.meta_value)) as tingkat, COUNT(DISTINCT p.ID) as total
     FROM {$wpdb->postmeta} pm
     JOIN {$wpdb->posts} p ON p.ID = pm.post_id
     WHERE p.post_type = 'prestasi' AND p.post_status = 'publish' 
     AND pm.meta_key IN ('_tingkat', 'tingkat_prestasi')
     GROUP BY LOWER(TRIM(pm.meta_value))"
);

$tingkat_data = array(
    'internasional'  => 0,
    'nasional'       => 0,
    'provinsi'       => 0,
    'kabupaten/kota' => 0,
    'kecamatan'      => 0,
);

foreach ( $tingkat_raw as $tr ) {
    $k = strtolower( trim( $tr->tingkat ) );
    if ( strpos( $k, 'internasional' ) !== false ) {
        $tingkat_data['internasional'] += (int) $tr->total;
    } elseif ( strpos( $k, 'nasional' ) !== false ) {
        $tingkat_data['nasional'] += (int) $tr->total;
    } elseif ( strpos( $k, 'provinsi' ) !== false ) {
        $tingkat_data['provinsi'] += (int) $tr->total;
    } elseif ( strpos( $k, 'kab' ) !== false || strpos( $k, 'kota' ) !== false ) {
        $tingkat_data['kabupaten/kota'] += (int) $tr->total;
    } elseif ( strpos( $k, 'kecamatan' ) !== false ) {
        $tingkat_data['kecamatan'] += (int) $tr->total;
    }
}

$tingkat_unggulan = $tingkat_data['internasional'] + $tingkat_data['nasional'];
$tingkat_regional = $tingkat_data['provinsi'] + $tingkat_data['kabupaten/kota'] + $tingkat_data['kecamatan'];

// 3. Ambil Semua Tahun yang Tersedia untuk Filter
$available_years = $wpdb->get_col(
    "SELECT DISTINCT YEAR(post_date) as th 
     FROM {$wpdb->posts} 
     WHERE post_type IN ('post', 'prestasi') AND post_status = 'publish' 
     ORDER BY th DESC"
);
$default_year = ! empty( $available_years ) ? (int) $available_years[0] : (int) date( 'Y' );

// 4. Hitung Data Grafik Bulanan untuk Semua Tahun
$nama_bulan = array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des' );
$chart_all_years = array();

foreach ( $available_years as $yr ) {
    $yr = (int) $yr;
    $b_rows = $wpdb->get_results( $wpdb->prepare( "SELECT MONTH(post_date) as bln, count(*) as c FROM {$wpdb->posts} WHERE post_type = 'post' and post_status = 'publish' and YEAR(post_date) = %d GROUP BY MONTH(post_date)", $yr ) );
    $p_rows = $wpdb->get_results( $wpdb->prepare( "SELECT MONTH(post_date) as bln, count(*) as c FROM {$wpdb->posts} WHERE post_type = 'prestasi' and post_status = 'publish' and YEAR(post_date) = %d GROUP BY MONTH(post_date)", $yr ) );

    $m_map = array();
    $max_v = 1;
    for ( $m = 1; $m <= 12; $m++ ) {
        $bc = 0; $pc = 0;
        foreach ( $b_rows as $r ) { if ( (int) $r->bln === $m ) { $bc = (int) $r->c; break; } }
        foreach ( $p_rows as $r ) { if ( (int) $r->bln === $m ) { $pc = (int) $r->c; break; } }
        if ( $bc > $max_v ) $max_v = $bc;
        if ( $pc > $max_v ) $max_v = $pc;
        $m_map[$m] = array( 'nama' => $nama_bulan[$m], 'berita' => $bc, 'prestasi' => $pc, 'total' => $bc + $pc );
    }
    $chart_all_years[$yr] = array(
        'max'            => $max_v,
        'months'         => $m_map,
        'total_berita'   => array_sum( array_column( $m_map, 'berita' ) ),
        'total_prestasi' => array_sum( array_column( $m_map, 'prestasi' ) ),
        'total_semua'    => array_sum( array_column( $m_map, 'total' ) ),
    );
}

// Tambahkan data agregat bulanan untuk opsi "Semua Tahun"
$b_all = $wpdb->get_results( "SELECT MONTH(post_date) as bln, count(*) as c FROM {$wpdb->posts} WHERE post_type = 'post' and post_status = 'publish' GROUP BY MONTH(post_date)" );
$p_all = $wpdb->get_results( "SELECT MONTH(post_date) as bln, count(*) as c FROM {$wpdb->posts} WHERE post_type = 'prestasi' and post_status = 'publish' GROUP BY MONTH(post_date)" );

$m_all_map = array();
$max_all_v = 1;
for ( $m = 1; $m <= 12; $m++ ) {
    $bc = 0; $pc = 0;
    foreach ( $b_all as $r ) { if ( (int) $r->bln === $m ) { $bc = (int) $r->c; break; } }
    foreach ( $p_all as $r ) { if ( (int) $r->bln === $m ) { $pc = (int) $r->c; break; } }
    if ( $bc > $max_all_v ) $max_all_v = $bc;
    if ( $pc > $max_all_v ) $max_all_v = $pc;
    $m_all_map[$m] = array( 'nama' => $nama_bulan[$m], 'berita' => $bc, 'prestasi' => $pc, 'total' => $bc + $pc );
}
$chart_all_years[''] = array(
    'max'            => $max_all_v,
    'months'         => $m_all_map,
    'total_berita'   => array_sum( array_column( $m_all_map, 'berita' ) ),
    'total_prestasi' => array_sum( array_column( $m_all_map, 'prestasi' ) ),
    'total_semua'    => array_sum( array_column( $m_all_map, 'total' ) ),
);
$chart_all_years['all'] = $chart_all_years[''];

// 5. Query Seluruh Prestasi
$all_prestasi = get_posts( array(
    'post_type'      => 'prestasi',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

// Hitung rincian tingkat prestasi akurat dari seluruh data prestasi
$tingkat_data = array(
    'internasional'  => 0,
    'nasional'       => 0,
    'provinsi'       => 0,
    'kabupaten/kota' => 0,
    'kecamatan'      => 0,
);

if ( ! empty( $all_prestasi ) ) {
    foreach ( $all_prestasi as $p_item ) {
        $t = function_exists( 'mtsn1_get_tingkat_prestasi' ) ? mtsn1_get_tingkat_prestasi( $p_item->ID ) : get_post_meta( $p_item->ID, '_tingkat', true );
        $lower = strtolower( trim( (string) $t ) );
        if ( strpos( $lower, 'internasional' ) !== false || strpos( $lower, 'international' ) !== false || strpos( $lower, 'world' ) !== false ) {
            $tingkat_data['internasional']++;
        } elseif ( strpos( $lower, 'nasional' ) !== false || strpos( $lower, 'national' ) !== false ) {
            $tingkat_data['nasional']++;
        } elseif ( strpos( $lower, 'provinsi' ) !== false || strpos( $lower, 'jatim' ) !== false || strpos( $lower, 'regional' ) !== false ) {
            $tingkat_data['provinsi']++;
        } elseif ( strpos( $lower, 'kab' ) !== false || strpos( $lower, 'kota' ) !== false || strpos( $lower, 'blitar' ) !== false ) {
            $tingkat_data['kabupaten/kota']++;
        } elseif ( strpos( $lower, 'kecamatan' ) !== false || strpos( $lower, 'wonodadi' ) !== false ) {
            $tingkat_data['kecamatan']++;
        } else {
            $tingkat_data['kabupaten/kota']++;
        }
    }
}

$tingkat_unggulan = $tingkat_data['internasional'] + $tingkat_data['nasional'];
$tingkat_regional = $tingkat_data['provinsi'] + $tingkat_data['kabupaten/kota'] + $tingkat_data['kecamatan'];

// 6. Query Seluruh Berita
$all_berita = get_posts( array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
?>

<style>
/* CSS Mandiri Dashboard & Bar Chart */
.stat-layout-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}
@media (min-width: 1024px) {
  .stat-layout-grid {
    grid-template-columns: 360px 1fr;
  }
}
.stat-chart-wrapper {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 6px;
  height: 220px;
  padding-top: 28px;
  padding-bottom: 6px;
  width: 100%;
}
@media (max-width: 640px) {
  .stat-chart-wrapper {
    overflow-x: auto;
    min-width: 540px;
  }
}
.stat-chart-col {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-end;
  height: 100%;
  position: relative;
  min-width: 32px;
}
.stat-bars-box {
  width: 100%;
  height: 160px;
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 4px;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  gap: 4px;
  position: relative;
  transition: all 0.2s ease;
}
.stat-bars-box:hover {
  background-color: #f1f5f9;
  border-color: #cbd5e1;
  box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
.stat-bar {
  flex: 1;
  border-radius: 4px 4px 0 0;
  min-height: 4px;
  transition: height 0.4s ease, opacity 0.2s ease;
  position: relative;
}
.stat-bar-b {
  background: linear-gradient(180deg, #10b981 0%, #059669 100%);
}
.stat-bar-p {
  background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
}
.stat-bar:hover {
  opacity: 0.9;
}
.stat-col-label {
  font-size: 11px;
  font-weight: 700;
  color: #64748b;
  margin-top: 8px;
  text-align: center;
}
.stat-col-label.current-m {
  color: #047857;
  font-weight: 900;
}
.stat-tooltip {
  position: absolute;
  top: -38px;
  left: 50%;
  transform: translateX(-50%);
  background: #0f172a;
  color: #ffffff;
  padding: 4px 8px;
  border-radius: 8px;
  font-size: 10px;
  font-weight: 700;
  white-space: nowrap;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.2s ease;
  z-index: 40;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
.stat-chart-col:hover .stat-tooltip {
  opacity: 1;
}

/* Custom Select Dropdowns */
.stat-select {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2364748b'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.65rem center;
  background-size: 1rem 1rem;
  padding-right: 2rem;
}

/* Sticky Table Header */
.stat-table-scroll {
  max-height: 650px;
  overflow-y: auto;
}
.stat-table-scroll thead th {
  position: sticky;
  top: 0;
  background-color: #ffffff;
  z-index: 20;
  box-shadow: 0 1px 0 #e2e8f0;
}
</style>

<div class="py-8 sm:py-12 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
      <span>/</span>
      <span class="text-slate-600">Program Madrasah</span>
      <span>/</span>
      <span class="text-emerald-700 font-bold">Bidang Humas</span>
    </nav>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden mb-8 border border-emerald-800/40">
      <div class="absolute -right-12 -bottom-12 w-72 h-72 rounded-full bg-emerald-500/10 pointer-events-none blur-3xl"></div>
      <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-10 text-8xl hidden lg:block pointer-events-none text-emerald-300">
        <i class="fa-solid fa-chart-line"></i>
      </div>

      <div class="relative z-10 max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-slate-950/80 text-amber-300 border border-amber-400/30 mb-3 shadow-sm backdrop-blur-md">
          <i class="fa-solid fa-chart-pie text-amber-400"></i> Dashboard Transparansi & Kinerja Humas
        </div>
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white drop-shadow-sm">
          Statistik Publikasi & Prestasi
        </h1>
        <p class="text-emerald-100 text-xs sm:text-sm sm:leading-relaxed mt-2.5">
          Pusat data publikasi resmi dan kinerja kehumasan MTsN 1 Blitar. Menyajikan rekapitulasi artikel berita madrasah serta dokumentasi capaian prestasi siswa dari tingkat daerah hingga internasional secara berkala dan transparan.
        </p>

        <div class="flex flex-wrap items-center gap-3 sm:gap-5 mt-5 pt-3 border-t border-emerald-800/60 text-xs text-emerald-200 font-medium">
          <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-check-double text-emerald-400"></i> Data Terupdate Otomatis</span>
          <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-calendar-check text-amber-400"></i> Terbitan s.d. Tahun <?php echo esc_html( $default_year ); ?></span>
          <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-school text-sky-400"></i> MTsN 1 Blitar</span>
        </div>
      </div>
    </div>

    <!-- Ringkasan Angka Utama (Publikasi & Prestasi) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-6">
      
      <!-- Kartu 1: Total Berita -->
      <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-200 hover:shadow-xl hover:border-emerald-500 transition-all flex items-center justify-between">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <span class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shadow-inner">
              <i class="fa-solid fa-newspaper"></i>
            </span>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">Kabar Berita</span>
          </div>
          <div id="card-count-total-berita" class="text-3xl sm:text-4xl font-black text-slate-900 mt-1">
            <?php echo number_format_i18n( $total_berita ); ?>
          </div>
          <h3 id="card-label-total-berita" class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Total Berita Terpublikasi</h3>
          <p class="text-[11px] text-slate-400 mt-0.5">Kegiatan, liputan resmi, & artikel madrasah</p>
        </div>
        <div class="hidden sm:flex w-14 h-14 rounded-3xl bg-emerald-50 items-center justify-center text-emerald-600/30 text-2xl">
          <i class="fa-solid fa-newspaper"></i>
        </div>
      </div>

      <!-- Kartu 2: Total Prestasi -->
      <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-200 hover:shadow-xl hover:border-amber-500 transition-all flex items-center justify-between">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <span class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shadow-inner">
              <i class="fa-solid fa-trophy"></i>
            </span>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800">Semua Prestasi</span>
          </div>
          <div id="card-count-total-prestasi" class="text-3xl sm:text-4xl font-black text-slate-900 mt-1">
            <?php echo number_format_i18n( $total_prestasi ); ?>
          </div>
          <h3 id="card-label-total-prestasi" class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Total Capaian Prestasi</h3>
          <p class="text-[11px] text-slate-400 mt-0.5">Medali & penghargaan siswa terverifikasi</p>
        </div>
        <div class="hidden sm:flex w-14 h-14 rounded-3xl bg-amber-50 items-center justify-center text-amber-600/30 text-2xl">
          <i class="fa-solid fa-trophy"></i>
        </div>
      </div>

    </div>

    <!-- Ringkasan Prestasi Berdasarkan Tingkat (Terpisah 5 Card) -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-3.5 px-1">
        <div class="flex items-center gap-2">
          <span class="w-2 h-4 rounded-full bg-amber-500"></span>
          <h2 class="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-wider">Capaian Prestasi Berdasarkan Tingkat Kejuaraan</h2>
          <span id="badge-tingkat-year" class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 transition-colors">Semua Tahun</span>
        </div>
        <span class="text-[11px] font-bold text-slate-400 hidden sm:inline"><i class="fa-solid fa-hand-pointer text-amber-500 mr-1"></i> Klik kartu untuk filter tabel</span>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
        
        <!-- Kartu 1: Tingkat Internasional -->
        <div onclick="quickFilterTingkat('internasional')" class="group cursor-pointer bg-white rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200 hover:shadow-xl hover:border-purple-500 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-3">
              <span class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white flex items-center justify-center text-base shadow-inner transition-colors duration-300">
                <i class="fa-solid fa-earth-asia"></i>
              </span>
              <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-purple-100 text-purple-800">Dunia</span>
            </div>
            <div id="card-count-internasional" class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-purple-600 transition">
              <?php echo number_format_i18n( $tingkat_data['internasional'] ); ?>
            </div>
            <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider mt-1">Internasional</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Kompetisi Global</p>
          </div>
          <div class="text-[10px] font-bold text-slate-400 group-hover:text-purple-600 flex items-center gap-1 mt-3 pt-2 border-t border-slate-100 transition">
            <span>Filter Tabel</span>
            <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>

        <!-- Kartu 2: Tingkat Nasional -->
        <div onclick="quickFilterTingkat('nasional')" class="group cursor-pointer bg-white rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200 hover:shadow-xl hover:border-rose-500 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-3">
              <span class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white flex items-center justify-center text-base shadow-inner transition-colors duration-300">
                <i class="fa-solid fa-flag"></i>
              </span>
              <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-800">Nasional</span>
            </div>
            <div id="card-count-nasional" class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-rose-600 transition">
              <?php echo number_format_i18n( $tingkat_data['nasional'] ); ?>
            </div>
            <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider mt-1">Nasional</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Ajang Se-Indonesia</p>
          </div>
          <div class="text-[10px] font-bold text-slate-400 group-hover:text-rose-600 flex items-center gap-1 mt-3 pt-2 border-t border-slate-100 transition">
            <span>Filter Tabel</span>
            <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>

        <!-- Kartu 3: Tingkat Provinsi -->
        <div onclick="quickFilterTingkat('provinsi')" class="group cursor-pointer bg-white rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200 hover:shadow-xl hover:border-blue-500 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-3">
              <span class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center text-base shadow-inner transition-colors duration-300">
                <i class="fa-solid fa-award"></i>
              </span>
              <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-blue-100 text-blue-800">Provinsi</span>
            </div>
            <div id="card-count-provinsi" class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-blue-600 transition">
              <?php echo number_format_i18n( $tingkat_data['provinsi'] ); ?>
            </div>
            <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider mt-1">Provinsi</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Jawa Timur & Regional</p>
          </div>
          <div class="text-[10px] font-bold text-slate-400 group-hover:text-blue-600 flex items-center gap-1 mt-3 pt-2 border-t border-slate-100 transition">
            <span>Filter Tabel</span>
            <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>

        <!-- Kartu 4: Tingkat Kabupaten / Kota -->
        <div onclick="quickFilterTingkat('kabupaten')" class="group cursor-pointer bg-white rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200 hover:shadow-xl hover:border-amber-500 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-3">
              <span class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center text-base shadow-inner transition-colors duration-300">
                <i class="fa-solid fa-city"></i>
              </span>
              <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-100 text-amber-800">Kab / Kota</span>
            </div>
            <div id="card-count-kabupaten" class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-amber-600 transition">
              <?php echo number_format_i18n( $tingkat_data['kabupaten/kota'] ); ?>
            </div>
            <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider mt-1">Kab / Kota</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Blitar Raya & Sekitar</p>
          </div>
          <div class="text-[10px] font-bold text-slate-400 group-hover:text-amber-600 flex items-center gap-1 mt-3 pt-2 border-t border-slate-100 transition">
            <span>Filter Tabel</span>
            <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>

        <!-- Kartu 5: Tingkat Kecamatan -->
        <div onclick="quickFilterTingkat('kecamatan')" class="group cursor-pointer bg-white rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200 hover:shadow-xl hover:border-emerald-500 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between col-span-2 sm:col-span-1">
          <div>
            <div class="flex items-center justify-between mb-3">
              <span class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center text-base shadow-inner transition-colors duration-300">
                <i class="fa-solid fa-location-dot"></i>
              </span>
              <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">Kecamatan</span>
            </div>
            <div id="card-count-kecamatan" class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-emerald-600 transition">
              <?php echo number_format_i18n( $tingkat_data['kecamatan'] ); ?>
            </div>
            <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider mt-1">Kecamatan</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Kec. Wonodadi & Sub-Rayon</p>
          </div>
          <div class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-600 flex items-center gap-1 mt-3 pt-2 border-t border-slate-100 transition">
            <span>Filter Tabel</span>
            <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-1 transition-transform"></i>
          </div>
        </div>

      </div>
    </div>

    <!-- Rincian Tingkat Prestasi & Grafik Publikasi Bulanan -->
    <div class="stat-layout-grid mb-8">

      <!-- Kolom Kiri: Rincian Tingkat Prestasi -->
      <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
            <div>
              <h3 class="text-base font-black text-slate-900">Rincian Prestasi per Tingkat</h3>
              <p class="text-xs text-slate-500">Distribusi jenjang kompetisi siswa <span id="sidebar-year-label" class="font-bold text-amber-700"></span></p>
            </div>
            <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shadow-sm">
              <i class="fa-solid fa-medal"></i>
            </span>
          </div>

          <div class="space-y-4">
            <?php
            $levels = array(
                array( 'label' => 'Internasional', 'count' => $tingkat_data['internasional'], 'bar' => '#9333ea', 'slug' => 'internasional' ),
                array( 'label' => 'Nasional', 'count' => $tingkat_data['nasional'], 'bar' => '#f43f5e', 'slug' => 'nasional' ),
                array( 'label' => 'Provinsi', 'count' => $tingkat_data['provinsi'], 'bar' => '#2563eb', 'slug' => 'provinsi' ),
                array( 'label' => 'Kabupaten / Kota', 'count' => $tingkat_data['kabupaten/kota'], 'bar' => '#f59e0b', 'slug' => 'kabupaten' ),
                array( 'label' => 'Kecamatan / Sub-Rayon', 'count' => $tingkat_data['kecamatan'], 'bar' => '#059669', 'slug' => 'kecamatan' ),
            );

            foreach ( $levels as $lvl ) :
                $pct = $total_prestasi > 0 ? round( ( $lvl['count'] / $total_prestasi ) * 100, 1 ) : 0;
            ?>
              <div class="cursor-pointer hover:bg-slate-50 p-1.5 -mx-1.5 rounded-xl transition" onclick="quickFilterTingkat('<?php echo esc_js( $lvl['slug'] ); ?>')">
                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                  <span class="text-slate-700 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: <?php echo esc_attr( $lvl['bar'] ); ?>"></span>
                    <?php echo esc_html( $lvl['label'] ); ?>
                  </span>
                  <span class="text-slate-900 font-black">
                    <span id="sidebar-count-<?php echo esc_attr( $lvl['slug'] ); ?>"><?php echo esc_html( $lvl['count'] ); ?></span> <span class="text-[10px] text-slate-400 font-semibold">(<span id="sidebar-pct-<?php echo esc_attr( $lvl['slug'] ); ?>"><?php echo esc_html( $pct ); ?></span>%)</span>
                  </span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                  <div id="sidebar-bar-<?php echo esc_attr( $lvl['slug'] ); ?>" class="h-2.5 rounded-full transition-all duration-500" style="width: <?php echo esc_attr( max( 4, $pct ) ); ?>%; background-color: <?php echo esc_attr( $lvl['bar'] ); ?>;"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="mt-6 pt-5 border-t border-slate-100">
          <a href="<?php echo esc_url( home_url( '/prestasi' ) ); ?>" class="w-full py-2.5 px-4 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-extrabold text-xs flex items-center justify-center gap-2 transition border border-amber-200 shadow-sm">
            <span>Buka Galeri Prestasi Siswa</span>
            <i class="fa-solid fa-arrow-right text-[11px]"></i>
          </a>
        </div>
      </div>

      <!-- Kolom Kanan: Grafik Keaktifan Bulanan -->
      <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col justify-between">
        <div>
          <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
            <div>
              <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <span>Aktivitas Rilis Publikasi</span>
                <!-- Selector Tahun untuk Grafik -->
                <select id="chart-year-select" onchange="switchChartYear(this.value)" class="stat-select bg-emerald-50 border border-emerald-300 rounded-xl px-2.5 py-1 text-xs font-black text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                  <option value="">📅 Semua Tahun</option>
                  <?php foreach ( $available_years as $yr ) : ?>
                    <option value="<?php echo esc_attr( $yr ); ?>"><?php echo esc_html( $yr ); ?></option>
                  <?php endforeach; ?>
                </select>
              </h3>
              <p class="text-xs text-slate-500">Jumlah terbitan berita dan capaian prestasi per bulan</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-bold">
              <span class="flex items-center gap-1.5 text-slate-700"><span class="w-3 h-3 rounded" style="background-color: #059669;"></span> Berita</span>
              <span class="flex items-center gap-1.5 text-slate-700"><span class="w-3 h-3 rounded" style="background-color: #d97706;"></span> Prestasi</span>
            </div>
          </div>

          <!-- Bar Chart Mandiri (Tampil Rapi di Semua Device) -->
          <div class="overflow-x-auto pb-2">
            <div class="stat-chart-wrapper" id="stat-chart-container">
              <!-- Bar chart diinjeksi secara dinamis via JavaScript sesuai pilihan tahun -->
            </div>
          </div>
        </div>

        <div class="mt-4 pt-3.5 border-t border-slate-100 flex flex-wrap items-center justify-between text-xs text-slate-500">
          <span>* Arahkan kursor atau sentuh diagram untuk melihat rincian terbitan</span>
          <span class="font-bold text-slate-800" id="chart-summary-text">
            Total Rilis Tahun <?php echo esc_html( $default_year ); ?>: <strong class="text-emerald-700"><?php echo esc_html( $chart_all_years[$default_year]['total_semua'] ?? 0 ); ?> Publikasi</strong>
          </span>
        </div>
      </div>

    </div>

    <!-- TAB & FILTER SECTION: REKAPITULASI BERITA & PRESTASI -->
    <div class="bg-white rounded-3xl p-5 sm:p-8 shadow-sm border border-slate-200">
      
      <!-- Baris 1: Tombol Tab & Filter Dropdown (Tahun, Bulan, Tingkat) -->
      <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-4 border-b border-slate-100">
        
        <!-- Tab Selector: Prestasi vs Berita -->
        <div class="flex items-center gap-2">
          <button type="button" onclick="switchStatTab('prestasi')" id="btn-tab-prestasi" class="px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl font-black text-xs sm:text-sm transition-all bg-amber-500 text-slate-950 shadow-md cursor-pointer flex items-center gap-2">
            <i class="fa-solid fa-trophy"></i>
            <span>Rekap Prestasi (<span id="total-count-prestasi"><?php echo count( $all_prestasi ); ?></span>)</span>
          </button>
          <button type="button" onclick="switchStatTab('berita')" id="btn-tab-berita" class="px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl font-bold text-xs sm:text-sm transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 cursor-pointer flex items-center gap-2">
            <i class="fa-solid fa-newspaper"></i>
            <span>Rekap Berita (<span id="total-count-berita"><?php echo count( $all_berita ); ?></span>)</span>
          </button>
        </div>

        <!-- Opsi Filter Dropdowns (Sesuai 3 Kotak Merah Pengguna) -->
        <div class="flex flex-wrap items-center gap-2">
          
          <!-- Kotak 1: Filter Tahun -->
          <div class="relative">
            <select id="filter-year" onchange="onFilterChange()" class="stat-select bg-white border border-slate-200 hover:border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 shadow-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer" title="Filter per Tahun">
              <option value="">📅 Semua Tahun</option>
              <?php foreach ( $available_years as $yr ) : ?>
                <option value="<?php echo esc_attr( $yr ); ?>"><?php echo esc_html( $yr ); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Kotak 2: Filter Bulan -->
          <div class="relative">
            <select id="filter-month" onchange="onFilterChange()" class="stat-select bg-white border border-slate-200 hover:border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-700 shadow-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer" title="Filter per Bulan">
              <option value="">🗓️ Semua Bulan</option>
              <option value="1">Januari</option>
              <option value="2">Februari</option>
              <option value="3">Maret</option>
              <option value="4">April</option>
              <option value="5">Mei</option>
              <option value="6">Juni</option>
              <option value="7">Juli</option>
              <option value="8">Agustus</option>
              <option value="9">September</option>
              <option value="10">Oktober</option>
              <option value="11">November</option>
              <option value="12">Desember</option>
            </select>
          </div>

          <!-- Kotak 3: Filter Tingkat Kejuaraan (Aktif saat Tab Prestasi dipilih) -->
          <div id="wrap-filter-tingkat" class="relative">
            <select id="filter-tingkat" onchange="onFilterChange()" class="stat-select bg-amber-50 border border-amber-200 hover:border-amber-300 rounded-xl px-3 py-2 text-xs font-bold text-amber-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer" title="Filter Tingkat Kejuaraan">
              <option value="">🏅 Semua Tingkat</option>
              <option value="internasional">Internasional</option>
              <option value="nasional">Nasional</option>
              <option value="provinsi">Provinsi</option>
              <option value="kabupaten">Kabupaten / Kota</option>
              <option value="kecamatan">Kecamatan</option>
            </select>
          </div>

          <!-- Tombol Reset Filter -->
          <button type="button" onclick="resetFilters()" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-xs" title="Reset Semua Filter">
            <i class="fa-solid fa-rotate-left text-[11px]"></i>
            <span class="hidden sm:inline">Reset</span>
          </button>

        </div>

      </div>

      <!-- Baris 2: Pencarian Cepat & Keterangan Hasil Filter -->
      <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div class="relative w-full sm:w-80">
          <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" id="stat-search" onkeyup="onFilterChange()" placeholder="Ketik kata kunci judul, peraih juara, dll..." class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
        </div>

        <!-- Indikator Jumlah Data yang Ditampilkan -->
        <div id="stat-counter-badge" class="text-xs font-bold text-slate-500">
          Menampilkan <span id="count-visible" class="text-emerald-700 font-black"><?php echo count( $all_prestasi ); ?></span> data
        </div>
      </div>

      <!-- Tab Content: Tabel Prestasi -->
      <div id="tab-content-prestasi">
        <div class="stat-table-scroll border border-slate-100 rounded-2xl">
          <table class="w-full text-left text-xs" id="table-prestasi">
            <thead>
              <tr class="border-b border-slate-200 text-slate-400 uppercase tracking-wider font-extrabold text-[11px]">
                <th class="py-3 px-3 w-12">No</th>
                <th class="py-3 px-3">Nama Kejuaraan / Prestasi</th>
                <th class="py-3 px-3">Peraih Juara</th>
                <th class="py-3 px-3">Tingkat</th>
                <th class="py-3 px-3 whitespace-nowrap">Tanggal Terbit</th>
                <th class="py-3 px-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
              <?php
              $no = 1;
              foreach ( $all_prestasi as $prestasi ) :
                  $pid     = $prestasi->ID;
                  $peraih  = get_post_meta( $pid, '_peraih', true );
                  $tingkat = function_exists( 'mtsn1_get_tingkat_prestasi' ) ? mtsn1_get_tingkat_prestasi( $pid ) : get_post_meta( $pid, '_tingkat', true );
                  $year    = get_the_date( 'Y', $pid );
                  $month   = get_the_date( 'n', $pid );
                  $date    = get_the_date( 'd M Y', $pid );
              ?>
                <tr class="stat-row hover:bg-amber-50/40 transition"
                    data-year="<?php echo esc_attr( $year ); ?>"
                    data-month="<?php echo esc_attr( $month ); ?>"
                    data-tingkat="<?php echo esc_attr( strtolower( $tingkat ) ); ?>"
                    data-search="<?php echo esc_attr( strtolower( $prestasi->post_title . ' ' . $peraih . ' ' . $tingkat . ' ' . $year ) ); ?>">
                  <td class="py-3 px-3 font-bold text-slate-400 row-number"><?php echo esc_html( $no++ ); ?></td>
                  <td class="py-3 px-3">
                    <a href="<?php echo esc_url( get_permalink( $pid ) ); ?>" class="font-bold text-slate-900 hover:text-amber-600 transition line-clamp-1">
                      <?php echo esc_html( $prestasi->post_title ); ?>
                    </a>
                  </td>
                  <td class="py-3 px-3 text-slate-600">
                    <?php echo $peraih ? esc_html( $peraih ) : '<span class="text-slate-400 italic">-</span>'; ?>
                  </td>
                  <td class="py-3 px-3">
                    <?php if ( function_exists( 'mtsn1_render_tingkat_badge' ) && $tingkat ) : ?>
                      <?php echo mtsn1_render_tingkat_badge( $tingkat ); ?>
                    <?php else : ?>
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700"><?php echo esc_html( $tingkat ?: 'Madrasah' ); ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="py-3 px-3 text-slate-500 whitespace-nowrap">
                    <?php echo esc_html( $date ); ?>
                  </td>
                  <td class="py-3 px-3 text-right">
                    <a href="<?php echo esc_url( get_permalink( $pid ) ); ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-black text-[11px] transition shadow-xs">
                      <span>Flyer</span>
                      <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- Pesan Kosong Jika Filter Tidak Ditemukan -->
        <div id="stat-empty-prestasi" class="hidden p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 mt-3">
          <i class="fa-solid fa-trophy text-slate-300 text-3xl mb-2"></i>
          <p class="text-xs font-bold text-slate-600">Tidak ada data prestasi yang cocok dengan pilihan tahun, bulan, atau tingkat kejuaraan.</p>
          <button type="button" onclick="resetFilters()" class="mt-3 px-3 py-1.5 rounded-xl bg-amber-500 text-slate-950 font-black text-xs">Reset Filter</button>
        </div>
      </div>

      <!-- Tab Content: Tabel Berita (Hidden by default) -->
      <div id="tab-content-berita" class="hidden">
        <div class="stat-table-scroll border border-slate-100 rounded-2xl">
          <table class="w-full text-left text-xs" id="table-berita">
            <thead>
              <tr class="border-b border-slate-200 text-slate-400 uppercase tracking-wider font-extrabold text-[11px]">
                <th class="py-3 px-3 w-12">No</th>
                <th class="py-3 px-3">Judul Berita</th>
                <th class="py-3 px-3">Kategori</th>
                <th class="py-3 px-3">Penulis / Redaksi</th>
                <th class="py-3 px-3 whitespace-nowrap">Tanggal Terbit</th>
                <th class="py-3 px-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
              <?php
              $no_b = 1;
              foreach ( $all_berita as $berita ) :
                  $bid      = $berita->ID;
                  $cats     = get_the_category( $bid );
                  $cat_name = ! empty( $cats ) ? $cats[0]->name : 'Berita';
                  $author   = get_the_author_meta( 'display_name', $berita->post_author );
                  $year_b   = get_the_date( 'Y', $bid );
                  $month_b  = get_the_date( 'n', $bid );
                  $date_b   = get_the_date( 'd M Y', $bid );
              ?>
                <tr class="stat-row hover:bg-emerald-50/40 transition"
                    data-year="<?php echo esc_attr( $year_b ); ?>"
                    data-month="<?php echo esc_attr( $month_b ); ?>"
                    data-tingkat=""
                    data-search="<?php echo esc_attr( strtolower( $berita->post_title . ' ' . $cat_name . ' ' . $author . ' ' . $year_b ) ); ?>">
                  <td class="py-3 px-3 font-bold text-slate-400 row-number"><?php echo esc_html( $no_b++ ); ?></td>
                  <td class="py-3 px-3">
                    <a href="<?php echo esc_url( get_permalink( $bid ) ); ?>" class="font-bold text-slate-900 hover:text-emerald-700 transition line-clamp-1">
                      <?php echo esc_html( $berita->post_title ); ?>
                    </a>
                  </td>
                  <td class="py-3 px-3">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                      <?php echo esc_html( $cat_name ); ?>
                    </span>
                  </td>
                  <td class="py-3 px-3 text-slate-600">
                    <i class="fa-regular fa-user mr-1 text-slate-400"></i> <?php echo esc_html( $author ); ?>
                  </td>
                  <td class="py-3 px-3 text-slate-500 whitespace-nowrap">
                    <?php echo esc_html( $date_b ); ?>
                  </td>
                  <td class="py-3 px-3 text-right">
                    <a href="<?php echo esc_url( get_permalink( $bid ) ); ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-black text-[11px] transition shadow-xs">
                      <span>Baca</span>
                      <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- Pesan Kosong Jika Filter Berita Tidak Ditemukan -->
        <div id="stat-empty-berita" class="hidden p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 mt-3">
          <i class="fa-solid fa-newspaper text-slate-300 text-3xl mb-2"></i>
          <p class="text-xs font-bold text-slate-600">Tidak ada berita yang cocok dengan filter tahun atau bulan yang dipilih.</p>
          <button type="button" onclick="resetFilters()" class="mt-3 px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-black text-xs">Reset Filter</button>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
// Data Grafik Semua Tahun dari PHP
const chartAllYears = <?php echo json_encode( $chart_all_years ); ?>;
let currentTab = 'prestasi';
const currentYearNow = <?php echo (int) date( 'Y' ); ?>;
const currentMonthNow = <?php echo (int) date( 'n' ); ?>;

// 1. Render Bar Chart untuk Tahun Tertentu
function renderChart(year) {
  const container = document.getElementById('stat-chart-container');
  const summaryText = document.getElementById('chart-summary-text');
  const chartSelect = document.getElementById('chart-year-select');
  if (chartSelect && chartSelect.value != year) {
    chartSelect.value = year;
  }

  const dataYear = chartAllYears[year] || chartAllYears[Object.keys(chartAllYears)[0]];
  if (!dataYear || !container) return;

  const maxVal = dataYear.max || 1;
  let html = '';

  for (let m = 1; m <= 12; m++) {
    const cd = dataYear.months[m] || { nama: '', berita: 0, prestasi: 0, total: 0 };
    const bPct = Math.round((cd.berita / maxVal) * 100);
    const pPct = Math.round((cd.prestasi / maxVal) * 100);
    const isThisMonth = (year == currentYearNow && m === currentMonthNow);
    const boxHighlight = isThisMonth ? 'border-color: #10b981; background-color: #ecfdf5;' : '';

    html += `
      <div class="stat-chart-col">
        <div class="stat-tooltip">
          ${cd.nama}: ${cd.berita} Berita, ${cd.prestasi} Prestasi
        </div>
        <div class="stat-bars-box" style="${boxHighlight}">
          <div class="stat-bar stat-bar-b" title="Berita: ${cd.berita}" style="height: ${Math.max(4, bPct)}%;">
            ${cd.berita > 0 ? `<span class="absolute -top-4 left-1/2 -translate-x-1/2 text-[9px] font-black text-emerald-800 pointer-events-none">${cd.berita}</span>` : ''}
          </div>
          <div class="stat-bar stat-bar-p" title="Prestasi: ${cd.prestasi}" style="height: ${Math.max(4, pPct)}%;">
            ${cd.prestasi > 0 ? `<span class="absolute -top-4 left-1/2 -translate-x-1/2 text-[9px] font-black text-amber-800 pointer-events-none">${cd.prestasi}</span>` : ''}
          </div>
        </div>
        <span class="stat-col-label ${isThisMonth ? 'current-m' : ''}">
          ${cd.nama}
        </span>
      </div>
    `;
  }

  container.innerHTML = html;
  if (summaryText) {
    const labelYear = year ? `Tahun ${year}` : 'Semua Tahun';
    summaryText.innerHTML = `Total Rilis ${labelYear}: <strong class="text-emerald-700">${dataYear.total_semua} Publikasi</strong> (${dataYear.total_berita} Berita, ${dataYear.total_prestasi} Prestasi)`;
  }
}

// 2. Ganti Tahun Grafik dari Dropdown di Header Grafik
function switchChartYear(year) {
  renderChart(year);
  // Sinkronkan juga filter tahun di tabel bawah
  const filterYear = document.getElementById('filter-year');
  if (filterYear) {
    filterYear.value = year;
    onFilterChange();
  }
}

// 3. Tab Switcher (Prestasi / Berita)
function switchStatTab(tab) {
  currentTab = tab;
  const btnPrestasi = document.getElementById('btn-tab-prestasi');
  const btnBerita = document.getElementById('btn-tab-berita');
  const tabPrestasi = document.getElementById('tab-content-prestasi');
  const tabBerita = document.getElementById('tab-content-berita');
  const wrapTingkat = document.getElementById('wrap-filter-tingkat');

  if (tab === 'prestasi') {
    btnPrestasi.className = 'px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl font-black text-xs sm:text-sm transition-all bg-amber-500 text-slate-950 shadow-md cursor-pointer flex items-center gap-2';
    btnBerita.className = 'px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl font-bold text-xs sm:text-sm transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 cursor-pointer flex items-center gap-2';
    tabPrestasi.classList.remove('hidden');
    tabBerita.classList.add('hidden');
    if (wrapTingkat) wrapTingkat.style.display = 'block';
  } else {
    btnBerita.className = 'px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl font-black text-xs sm:text-sm transition-all bg-emerald-600 text-white shadow-md cursor-pointer flex items-center gap-2';
    btnPrestasi.className = 'px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl font-bold text-xs sm:text-sm transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 cursor-pointer flex items-center gap-2';
    tabBerita.classList.remove('hidden');
    tabPrestasi.classList.add('hidden');
    if (wrapTingkat) wrapTingkat.style.display = 'none'; // Sembunyikan filter tingkat untuk tab berita
  }
  onFilterChange();
}

// 4. Klik Cepat Tingkat dari Kolom Kiri
function quickFilterTingkat(tingkatSlug) {
  if (currentTab !== 'prestasi') {
    switchStatTab('prestasi');
  }
  const selTingkat = document.getElementById('filter-tingkat');
  if (selTingkat) {
    selTingkat.value = tingkatSlug;
  }
  onFilterChange();
  // Scroll mulus ke tabel
  const tabSection = document.getElementById('tab-content-prestasi');
  if (tabSection) {
    tabSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

// 5. Update Metrik Kartu Sesuai Tahun yang Dipilih
function updateStatMetrics(selYear) {
  const tableP = document.getElementById('table-prestasi');
  const tableB = document.getElementById('table-berita');
  if (!tableP) return;

  const pRows = tableP.querySelectorAll('.stat-row');
  const bRows = tableB ? tableB.querySelectorAll('.stat-row') : [];

  let countInter = 0;
  let countNas = 0;
  let countProv = 0;
  let countKab = 0;
  let countKec = 0;
  let totalP = 0;
  let totalB = 0;

  pRows.forEach(function(row) {
    const rowYear = (row.getAttribute('data-year') || '').trim();
    if (!selYear || rowYear === selYear) {
      totalP++;
      const t = (row.getAttribute('data-tingkat') || '').toLowerCase().trim();
      if (t.indexOf('internasional') !== -1 || t.indexOf('international') !== -1 || t.indexOf('world') !== -1) {
        countInter++;
      } else if (t.indexOf('nasional') !== -1 || t.indexOf('national') !== -1) {
        countNas++;
      } else if (t.indexOf('provinsi') !== -1 || t.indexOf('jatim') !== -1 || t.indexOf('regional') !== -1) {
        countProv++;
      } else if (t.indexOf('kab') !== -1 || t.indexOf('kota') !== -1 || t.indexOf('blitar') !== -1) {
        countKab++;
      } else if (t.indexOf('kecamatan') !== -1 || t.indexOf('wonodadi') !== -1) {
        countKec++;
      } else {
        countKab++;
      }
    }
  });

  bRows.forEach(function(row) {
    const rowYear = (row.getAttribute('data-year') || '').trim();
    if (!selYear || rowYear === selYear) {
      totalB++;
    }
  });

  // 1. Update 5 Kartu Tingkat
  const elInter = document.getElementById('card-count-internasional');
  const elNas = document.getElementById('card-count-nasional');
  const elProv = document.getElementById('card-count-provinsi');
  const elKab = document.getElementById('card-count-kabupaten');
  const elKec = document.getElementById('card-count-kecamatan');

  if (elInter) elInter.textContent = countInter;
  if (elNas) elNas.textContent = countNas;
  if (elProv) elProv.textContent = countProv;
  if (elKab) elKab.textContent = countKab;
  if (elKec) elKec.textContent = countKec;

  // 2. Update Badge Tahun di Header Kartu Tingkat
  const badgeYear = document.getElementById('badge-tingkat-year');
  if (badgeYear) {
    if (selYear) {
      badgeYear.textContent = 'Tahun ' + selYear;
      badgeYear.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500 text-slate-950 shadow-xs transition-colors';
    } else {
      badgeYear.textContent = 'Semua Tahun';
      badgeYear.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 transition-colors';
    }
  }

  // 3. Update 2 Kartu Utama Atas (Total Berita & Total Prestasi)
  const elTotalB = document.getElementById('card-count-total-berita');
  const elTotalP = document.getElementById('card-count-total-prestasi');
  const labelTotalB = document.getElementById('card-label-total-berita');
  const labelTotalP = document.getElementById('card-label-total-prestasi');

  if (elTotalB) elTotalB.textContent = totalB;
  if (elTotalP) elTotalP.textContent = totalP;
  if (labelTotalB) labelTotalB.textContent = selYear ? 'Total Berita Tahun ' + selYear : 'Total Berita Terpublikasi';
  if (labelTotalP) labelTotalP.textContent = selYear ? 'Total Prestasi Tahun ' + selYear : 'Total Capaian Prestasi';

  // 4. Update Tab Counters
  const tabCountP = document.getElementById('total-count-prestasi');
  const tabCountB = document.getElementById('total-count-berita');
  if (tabCountP) tabCountP.textContent = totalP;
  if (tabCountB) tabCountB.textContent = totalB;

  // 5. Update Sidebar "Rincian Prestasi per Tingkat"
  const sideYearLabel = document.getElementById('sidebar-year-label');
  if (sideYearLabel) {
    sideYearLabel.textContent = selYear ? '(Tahun ' + selYear + ')' : '(Semua Tahun)';
  }

  const levels = [
    { slug: 'internasional', count: countInter },
    { slug: 'nasional', count: countNas },
    { slug: 'provinsi', count: countProv },
    { slug: 'kabupaten', count: countKab },
    { slug: 'kecamatan', count: countKec }
  ];

  levels.forEach(function(lvl) {
    const elC = document.getElementById('sidebar-count-' + lvl.slug);
    const elP = document.getElementById('sidebar-pct-' + lvl.slug);
    const elB = document.getElementById('sidebar-bar-' + lvl.slug);
    const pct = totalP > 0 ? ((lvl.count / totalP) * 100).toFixed(1) : 0;

    if (elC) elC.textContent = lvl.count;
    if (elP) elP.textContent = pct;
    if (elB) elB.style.width = Math.max(4, pct) + '%';
  });
}

// 6. Fungsi Utama Filter Tabel
function onFilterChange() {
  const selYear = (document.getElementById('filter-year').value || '').trim();
  const selMonth = (document.getElementById('filter-month').value || '').trim();
  const selTingkat = (document.getElementById('filter-tingkat').value || '').toLowerCase().trim();
  const query = (document.getElementById('stat-search').value || '').toLowerCase().trim();

  // Sinkronkan selector chart jika tahun diubah dari filter tabel
  const chartSelect = document.getElementById('chart-year-select');
  if (chartSelect && chartSelect.value !== selYear) {
    chartSelect.value = selYear;
    renderChart(selYear);
  }

  // Update metrik angka kartu (5 kartu tingkat, 2 kartu utama, dan sidebar) sesuai filter tahun
  updateStatMetrics(selYear);

  const table = document.getElementById('table-' + currentTab);
  if (!table) return;

  const rows = table.querySelectorAll('.stat-row');
  let visibleCount = 0;
  let rowNumber = 1;

  rows.forEach(function(row) {
    const rowYear = (row.getAttribute('data-year') || '').trim();
    const rowMonth = (row.getAttribute('data-month') || '').trim();
    const rowTingkat = (row.getAttribute('data-tingkat') || '').toLowerCase().trim();
    const rowSearch = (row.getAttribute('data-search') || '').toLowerCase().trim();

    const matchYear = (!selYear || rowYear === selYear);
    const matchMonth = (!selMonth || rowMonth === selMonth);
    let matchTingkat = (currentTab === 'berita' || !selTingkat);
    if (!matchTingkat) {
      if (selTingkat === 'kabupaten' || selTingkat === 'kab' || selTingkat === 'kab/kota') {
        matchTingkat = (rowTingkat.indexOf('kab') !== -1 || rowTingkat.indexOf('kota') !== -1 || rowTingkat.indexOf('blitar') !== -1);
      } else if (selTingkat === 'provinsi') {
        matchTingkat = (rowTingkat.indexOf('provinsi') !== -1 || rowTingkat.indexOf('jatim') !== -1 || rowTingkat.indexOf('regional') !== -1);
      } else if (selTingkat === 'kecamatan') {
        matchTingkat = (rowTingkat.indexOf('kecamatan') !== -1 || rowTingkat.indexOf('wonodadi') !== -1);
      } else {
        matchTingkat = (rowTingkat.indexOf(selTingkat) !== -1);
      }
    }
    const matchSearch = (!query || rowSearch.indexOf(query) !== -1);

    if (matchYear && matchMonth && matchTingkat && matchSearch) {
      row.style.display = '';
      visibleCount++;
      const numCell = row.querySelector('.row-number');
      if (numCell) numCell.textContent = rowNumber++;
    } else {
      row.style.display = 'none';
    }
  });

  // Update Counter Badge
  const countVisibleEl = document.getElementById('count-visible');
  const countBadgeEl = document.getElementById('stat-counter-badge');
  const labelKategori = currentTab === 'prestasi' ? 'Prestasi' : 'Berita';
  if (countVisibleEl && countBadgeEl) {
    countVisibleEl.textContent = visibleCount;
    countBadgeEl.innerHTML = `Menampilkan <strong class="${currentTab === 'prestasi' ? 'text-amber-600' : 'text-emerald-700'}">${visibleCount}</strong> dari ${rows.length} ${labelKategori}`;
  }

  // Tampilkan pesan kosong jika 0
  const emptyBox = document.getElementById('stat-empty-' + currentTab);
  if (emptyBox) {
    emptyBox.classList.toggle('hidden', visibleCount > 0);
  }
}

// 6. Reset Seluruh Filter
function resetFilters() {
  document.getElementById('filter-year').value = '';
  document.getElementById('filter-month').value = '';
  document.getElementById('filter-tingkat').value = '';
  document.getElementById('stat-search').value = '';
  onFilterChange();
}

// Inisialisasi saat Halaman Selesai Dimuat
document.addEventListener('DOMContentLoaded', function() {
  renderChart('');
  onFilterChange();
});
</script>

<?php
get_footer();
