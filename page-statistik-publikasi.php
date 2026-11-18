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

// 3. Data Publikasi per Bulan (Tahun Berjalan)
$current_year = date( 'Y' );
$berita_monthly_raw = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT MONTH(post_date) as bulan, COUNT(*) as total 
         FROM {$wpdb->posts} 
         WHERE post_type = 'post' AND post_status = 'publish' AND YEAR(post_date) = %d 
         GROUP BY MONTH(post_date)",
        $current_year
    )
);

$prestasi_monthly_raw = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT MONTH(post_date) as bulan, COUNT(*) as total 
         FROM {$wpdb->posts} 
         WHERE post_type = 'prestasi' AND post_status = 'publish' AND YEAR(post_date) = %d 
         GROUP BY MONTH(post_date)",
        $current_year
    )
);

$nama_bulan = array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des' );
$chart_data = array();
$max_monthly = 1;

for ( $m = 1; $m <= 12; $m++ ) {
    $b_count = 0;
    $p_count = 0;
    foreach ( $berita_monthly_raw as $row ) {
        if ( (int) $row->bulan === $m ) {
            $b_count = (int) $row->total;
            break;
        }
    }
    foreach ( $prestasi_monthly_raw as $row ) {
        if ( (int) $row->bulan === $m ) {
            $p_count = (int) $row->total;
            break;
        }
    }
    $total_m = $b_count + $p_count;
    if ( $total_m > $max_monthly ) {
        $max_monthly = $total_m;
    }
    $chart_data[$m] = array(
        'nama'     => $nama_bulan[$m],
        'berita'   => $b_count,
        'prestasi' => $p_count,
        'total'    => $total_m,
    );
}

// 4. Query Daftar Rekap Prestasi Terkini (Limit 20)
$recent_prestasi = get_posts( array(
    'post_type'      => 'prestasi',
    'posts_per_page' => 20,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

// 5. Query Daftar Rekap Berita Terkini (Limit 20)
$recent_berita = get_posts( array(
    'post_type'      => 'post',
    'posts_per_page' => 20,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
      <span>/</span>
      <span class="text-slate-600">Info Publikasi</span>
      <span>/</span>
      <span class="text-emerald-700 font-bold">Statistik Publikasi</span>
    </nav>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden mb-10 border border-emerald-800/40">
      <div class="absolute -right-12 -bottom-12 w-80 h-80 rounded-full bg-emerald-500/10 pointer-events-none blur-3xl"></div>
      <div class="absolute right-12 top-1/2 -translate-y-1/2 opacity-10 text-9xl hidden lg:block pointer-events-none text-emerald-300">
        <i class="fa-solid fa-chart-line"></i>
      </div>

      <div class="relative z-10 max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-slate-950/80 text-gold-400 border border-gold-400/30 mb-4 shadow-sm backdrop-blur-md">
          <i class="fa-solid fa-chart-pie text-amber-400"></i> Dashboard Transparansi & Kinerja Publikasi
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white drop-shadow-sm">
          Statistik Publikasi & Prestasi
        </h1>
        <p class="text-emerald-100 text-sm sm:text-base mt-3 leading-relaxed">
          Pusat data publikasi resmi MTsN 1 Blitar. Menyajikan rekapitulasi artikel berita madrasah serta dokumentasi capaian prestasi siswa dari tingkat daerah hingga internasional secara berkala dan transparan.
        </p>

        <div class="flex flex-wrap items-center gap-4 mt-6 pt-4 border-t border-emerald-800/60 text-xs text-emerald-200">
          <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-check-double text-emerald-400"></i> Data Terupdate Otomatis</span>
          <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-calendar-check text-amber-400"></i> Periode Tahun <?php echo esc_html( $current_year ); ?></span>
          <span class="inline-flex items-center gap-1.5"><i class="fa-solid fa-school text-sky-400"></i> MTsN 1 Blitar</span>
        </div>
      </div>
    </div>

    <!-- Ringkasan Angka Utama (4 Kartu Metric) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
      
      <!-- Kartu 1: Total Berita -->
      <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-xl hover:border-emerald-500 transition-all group">
        <div class="flex items-center justify-between mb-4">
          <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-newspaper"></i>
          </span>
          <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">Aktif</span>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 group-hover:text-emerald-700 transition">
          <?php echo number_format_i18n( $total_berita ); ?>
        </div>
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Total Berita Terpublikasi</h3>
        <p class="text-[11px] text-slate-400 mt-2">Kabar kegiatan, edukasi, & liputan madrasah</p>
      </div>

      <!-- Kartu 2: Total Prestasi -->
      <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-xl hover:border-amber-500 transition-all group">
        <div class="flex items-center justify-between mb-4">
          <span class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-trophy"></i>
          </span>
          <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800">Juara</span>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 group-hover:text-amber-600 transition">
          <?php echo number_format_i18n( $total_prestasi ); ?>
        </div>
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Total Capaian Prestasi</h3>
        <p class="text-[11px] text-slate-400 mt-2">Medali & penghargaan siswa terverifikasi</p>
      </div>

      <!-- Kartu 3: Prestasi Nasional & Internasional -->
      <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-xl hover:border-purple-500 transition-all group">
        <div class="flex items-center justify-between mb-4">
          <span class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-earth-asia"></i>
          </span>
          <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800">Top Level</span>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 group-hover:text-purple-600 transition">
          <?php echo number_format_i18n( $tingkat_unggulan ); ?>
        </div>
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Nasional & Internasional</h3>
        <p class="text-[11px] text-slate-400 mt-2"><?php echo esc_html( $tingkat_data['internasional'] ); ?> Internasional, <?php echo esc_html( $tingkat_data['nasional'] ); ?> Nasional</p>
      </div>

      <!-- Kartu 4: Prestasi Tingkat Provinsi & Daerah -->
      <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-xl hover:border-blue-500 transition-all group">
        <div class="flex items-center justify-between mb-4">
          <span class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-award"></i>
          </span>
          <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-800">Regional</span>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 group-hover:text-blue-600 transition">
          <?php echo number_format_i18n( $tingkat_regional ); ?>
        </div>
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Provinsi & Kab/Kota</h3>
        <p class="text-[11px] text-slate-400 mt-2"><?php echo esc_html( $tingkat_data['provinsi'] ); ?> Provinsi, <?php echo esc_html( $tingkat_data['kabupaten/kota'] ); ?> Kab/Kota</p>
      </div>

    </div>

    <!-- Rincian Tingkat Prestasi & Grafik Publikasi Bulanan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

      <!-- Kolom Kiri (1/3): Rincian Tingkat Prestasi -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
          <div>
            <h3 class="text-base font-black text-slate-900">Rincian Prestasi per Tingkat</h3>
            <p class="text-xs text-slate-500">Distribusi jenjang kompetisi siswa</p>
          </div>
          <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shadow-sm">
            <i class="fa-solid fa-medal"></i>
          </span>
        </div>

        <div class="space-y-4">
          <?php
          $levels = array(
              array( 'label' => 'Internasional', 'count' => $tingkat_data['internasional'], 'color' => 'bg-purple-600', 'badge' => 'bg-purple-100 text-purple-800' ),
              array( 'label' => 'Nasional', 'count' => $tingkat_data['nasional'], 'color' => 'bg-rose-500', 'badge' => 'bg-rose-100 text-rose-800' ),
              array( 'label' => 'Provinsi', 'count' => $tingkat_data['provinsi'], 'color' => 'bg-blue-600', 'badge' => 'bg-blue-100 text-blue-800' ),
              array( 'label' => 'Kabupaten / Kota', 'count' => $tingkat_data['kabupaten/kota'], 'color' => 'bg-amber-500', 'badge' => 'bg-amber-100 text-amber-800' ),
              array( 'label' => 'Kecamatan / Sub-Rayon', 'count' => $tingkat_data['kecamatan'], 'color' => 'bg-emerald-600', 'badge' => 'bg-emerald-100 text-emerald-800' ),
          );

          foreach ( $levels as $lvl ) :
              $pct = $total_prestasi > 0 ? round( ( $lvl['count'] / $total_prestasi ) * 100, 1 ) : 0;
          ?>
            <div>
              <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                <span class="text-slate-700 flex items-center gap-1.5">
                  <span class="w-2.5 h-2.5 rounded-full <?php echo esc_attr( $lvl['color'] ); ?>"></span>
                  <?php echo esc_html( $lvl['label'] ); ?>
                </span>
                <span class="text-slate-900 font-black">
                  <?php echo esc_html( $lvl['count'] ); ?> <span class="text-[10px] text-slate-400 font-semibold">(<?php echo esc_html( $pct ); ?>%)</span>
                </span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                <div class="<?php echo esc_attr( $lvl['color'] ); ?> h-2.5 rounded-full transition-all duration-500" style="width: <?php echo esc_attr( max( 4, $pct ) ); ?>%"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100">
          <a href="<?php echo esc_url( home_url( '/prestasi' ) ); ?>" class="w-full py-3 px-4 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-extrabold text-xs flex items-center justify-center gap-2 transition border border-amber-200 shadow-sm">
            <span>Buka Galeri Prestasi Siswa</span>
            <i class="fa-solid fa-arrow-right text-[11px]"></i>
          </a>
        </div>
      </div>

      <!-- Kolom Kanan (2/3): Grafik Keaktifan Bulanan -->
      <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200 flex flex-col justify-between">
        <div>
          <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
            <div>
              <h3 class="text-base font-black text-slate-900">Aktivitas Rilis Publikasi Tahun <?php echo esc_html( $current_year ); ?></h3>
              <p class="text-xs text-slate-500">Jumlah terbitan berita dan prestasi tiap bulan</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-bold">
              <span class="flex items-center gap-1.5 text-slate-600"><span class="w-3 h-3 rounded bg-emerald-600"></span> Berita</span>
              <span class="flex items-center gap-1.5 text-slate-600"><span class="w-3 h-3 rounded bg-amber-500"></span> Prestasi</span>
            </div>
          </div>

          <!-- Bar Chart Sederhana Responsif -->
          <div class="grid grid-cols-6 sm:grid-cols-12 gap-2 sm:gap-3 items-end h-56 pt-8 pb-2 px-1">
            <?php foreach ( $chart_data as $m => $cd ) :
                $b_height = $max_monthly > 0 ? ( $cd['berita'] / $max_monthly ) * 100 : 0;
                $p_height = $max_monthly > 0 ? ( $cd['prestasi'] / $max_monthly ) * 100 : 0;
                $is_current_month = ( (int) date( 'n' ) === $m );
            ?>
              <div class="flex flex-col items-center gap-1.5 h-full justify-end group/bar relative">
                <!-- Tooltip Hover -->
                <div class="opacity-0 group-hover/bar:opacity-100 pointer-events-none absolute -top-12 z-20 bg-slate-900 text-white text-[10px] font-bold rounded-lg py-1 px-2 whitespace-nowrap shadow-xl transition-all">
                  <?php echo esc_html( $cd['nama'] ); ?>: <?php echo esc_html( $cd['berita'] ); ?> Berita, <?php echo esc_html( $cd['prestasi'] ); ?> Prestasi
                </div>

                <!-- Combined Bars Container -->
                <div class="w-full flex items-end justify-center gap-0.5 sm:gap-1 h-44 bg-slate-50 rounded-xl p-1 border <?php echo $is_current_month ? 'border-emerald-300 ring-2 ring-emerald-100' : 'border-slate-100'; ?>">
                  <!-- Berita Bar -->
                  <div class="w-1/2 bg-emerald-600 rounded-t-md hover:bg-emerald-500 transition-all" style="height: <?php echo esc_attr( max( 4, $b_height ) ); ?>%"></div>
                  <!-- Prestasi Bar -->
                  <div class="w-1/2 bg-amber-500 rounded-t-md hover:bg-amber-400 transition-all" style="height: <?php echo esc_attr( max( 4, $p_height ) ); ?>%"></div>
                </div>

                <!-- Month Label -->
                <span class="text-[11px] font-bold <?php echo $is_current_month ? 'text-emerald-700 font-black' : 'text-slate-500'; ?>">
                  <?php echo esc_html( $cd['nama'] ); ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between text-xs text-slate-500">
          <span>* Rangkuman frekuensi publikasi humas & kesiswaan MTsN 1 Blitar</span>
          <span class="font-bold text-slate-700">Total Periode Ini: <?php echo esc_html( array_sum( array_column( $chart_data, 'total' ) ) ); ?> Publikasi</span>
        </div>
      </div>

    </div>

    <!-- Tab Section: Daftar Rekapitulasi Berita & Prestasi Terbaru -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200">
      
      <!-- Tab Header Buttons -->
      <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-4 border-b border-slate-100">
        <div class="flex items-center gap-2">
          <button type="button" onclick="switchStatTab('prestasi')" id="btn-tab-prestasi" class="px-4 py-2.5 rounded-2xl font-black text-xs sm:text-sm transition-all bg-amber-500 text-slate-950 shadow-md cursor-pointer flex items-center gap-2">
            <i class="fa-solid fa-trophy"></i>
            <span>Rekap Prestasi Terbaru (20)</span>
          </button>
          <button type="button" onclick="switchStatTab('berita')" id="btn-tab-berita" class="px-4 py-2.5 rounded-2xl font-bold text-xs sm:text-sm transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 cursor-pointer flex items-center gap-2">
            <i class="fa-solid fa-newspaper"></i>
            <span>Rekap Berita Terbaru (20)</span>
          </button>
        </div>

        <!-- Live Search Filter -->
        <div class="relative w-full sm:w-72">
          <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" id="stat-search" onkeyup="filterStatRows()" placeholder="Cari dalam tabel rekap..." class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
        </div>
      </div>

      <!-- Tab Content: Prestasi -->
      <div id="tab-content-prestasi">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs" id="table-prestasi">
            <thead>
              <tr class="border-b border-slate-200 text-slate-400 uppercase tracking-wider font-extrabold text-[11px]">
                <th class="py-3 px-3">No</th>
                <th class="py-3 px-3">Nama Kejuaraan / Prestasi</th>
                <th class="py-3 px-3">Peraih Juara</th>
                <th class="py-3 px-3">Tingkat</th>
                <th class="py-3 px-3">Tanggal Terbit</th>
                <th class="py-3 px-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
              <?php
              $no = 1;
              foreach ( $recent_prestasi as $prestasi ) :
                  $pid     = $prestasi->ID;
                  $peraih  = get_post_meta( $pid, '_peraih', true );
                  $tingkat = function_exists( 'mtsn1_get_tingkat_prestasi' ) ? mtsn1_get_tingkat_prestasi( $pid ) : get_post_meta( $pid, '_tingkat', true );
                  $date    = get_the_date( 'd M Y', $pid );
              ?>
                <tr class="stat-row hover:bg-slate-50/80 transition" data-search="<?php echo esc_attr( strtolower( $prestasi->post_title . ' ' . $peraih . ' ' . $tingkat ) ); ?>">
                  <td class="py-3.5 px-3 font-bold text-slate-400"><?php echo esc_html( $no++ ); ?></td>
                  <td class="py-3.5 px-3">
                    <a href="<?php echo esc_url( get_permalink( $pid ) ); ?>" class="font-bold text-slate-900 hover:text-amber-600 transition line-clamp-1">
                      <?php echo esc_html( $prestasi->post_title ); ?>
                    </a>
                  </td>
                  <td class="py-3.5 px-3 text-slate-600">
                    <?php echo $peraih ? esc_html( $peraih ) : '<span class="text-slate-400 italic">-</span>'; ?>
                  </td>
                  <td class="py-3.5 px-3">
                    <?php if ( function_exists( 'mtsn1_render_tingkat_badge' ) && $tingkat ) : ?>
                      <?php echo mtsn1_render_tingkat_badge( $tingkat ); ?>
                    <?php else : ?>
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700"><?php echo esc_html( $tingkat ?: 'Madrasah' ); ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="py-3.5 px-3 text-slate-500 whitespace-nowrap">
                    <?php echo esc_html( $date ); ?>
                  </td>
                  <td class="py-3.5 px-3 text-right">
                    <a href="<?php echo esc_url( get_permalink( $pid ) ); ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-black text-[11px] transition shadow-xs">
                      <span>Flyer</span>
                      <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tab Content: Berita (Hidden by default) -->
      <div id="tab-content-berita" class="hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs" id="table-berita">
            <thead>
              <tr class="border-b border-slate-200 text-slate-400 uppercase tracking-wider font-extrabold text-[11px]">
                <th class="py-3 px-3">No</th>
                <th class="py-3 px-3">Judul Berita</th>
                <th class="py-3 px-3">Kategori</th>
                <th class="py-3 px-3">Penulis / Redaksi</th>
                <th class="py-3 px-3">Tanggal Terbit</th>
                <th class="py-3 px-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
              <?php
              $no_b = 1;
              foreach ( $recent_berita as $berita ) :
                  $bid   = $berita->ID;
                  $cats  = get_the_category( $bid );
                  $cat_name = ! empty( $cats ) ? $cats[0]->name : 'Berita';
                  $author   = get_the_author_meta( 'display_name', $berita->post_author );
                  $date_b   = get_the_date( 'd M Y', $bid );
              ?>
                <tr class="stat-row hover:bg-slate-50/80 transition" data-search="<?php echo esc_attr( strtolower( $berita->post_title . ' ' . $cat_name . ' ' . $author ) ); ?>">
                  <td class="py-3.5 px-3 font-bold text-slate-400"><?php echo esc_html( $no_b++ ); ?></td>
                  <td class="py-3.5 px-3">
                    <a href="<?php echo esc_url( get_permalink( $bid ) ); ?>" class="font-bold text-slate-900 hover:text-emerald-700 transition line-clamp-1">
                      <?php echo esc_html( $berita->post_title ); ?>
                    </a>
                  </td>
                  <td class="py-3.5 px-3">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                      <?php echo esc_html( $cat_name ); ?>
                    </span>
                  </td>
                  <td class="py-3.5 px-3 text-slate-600">
                    <i class="fa-regular fa-user mr-1 text-slate-400"></i> <?php echo esc_html( $author ); ?>
                  </td>
                  <td class="py-3.5 px-3 text-slate-500 whitespace-nowrap">
                    <?php echo esc_html( $date_b ); ?>
                  </td>
                  <td class="py-3.5 px-3 text-right">
                    <a href="<?php echo esc_url( get_permalink( $bid ) ); ?>" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-black text-[11px] transition shadow-xs">
                      <span>Baca</span>
                      <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
function switchStatTab(tab) {
  const btnPrestasi = document.getElementById('btn-tab-prestasi');
  const btnBerita = document.getElementById('btn-tab-berita');
  const tabPrestasi = document.getElementById('tab-content-prestasi');
  const tabBerita = document.getElementById('tab-content-berita');
  const searchInput = document.getElementById('stat-search');
  if (searchInput) searchInput.value = '';

  if (tab === 'prestasi') {
    btnPrestasi.className = 'px-4 py-2.5 rounded-2xl font-black text-xs sm:text-sm transition-all bg-amber-500 text-slate-950 shadow-md cursor-pointer flex items-center gap-2';
    btnBerita.className = 'px-4 py-2.5 rounded-2xl font-bold text-xs sm:text-sm transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 cursor-pointer flex items-center gap-2';
    tabPrestasi.classList.remove('hidden');
    tabBerita.classList.add('hidden');
  } else {
    btnBerita.className = 'px-4 py-2.5 rounded-2xl font-black text-xs sm:text-sm transition-all bg-emerald-600 text-white shadow-md cursor-pointer flex items-center gap-2';
    btnPrestasi.className = 'px-4 py-2.5 rounded-2xl font-bold text-xs sm:text-sm transition-all bg-slate-100 text-slate-700 hover:bg-slate-200 cursor-pointer flex items-center gap-2';
    tabBerita.classList.remove('hidden');
    tabPrestasi.classList.add('hidden');
  }
  filterStatRows();
}

function filterStatRows() {
  const query = (document.getElementById('stat-search').value || '').toLowerCase().trim();
  const rows = document.querySelectorAll('.stat-row');
  rows.forEach(function(row) {
    const text = row.getAttribute('data-search') || '';
    if (!query || text.indexOf(query) !== -1) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}
</script>

<?php
get_footer();
