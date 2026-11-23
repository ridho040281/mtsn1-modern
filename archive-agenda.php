<?php
/**
 * The template for displaying Agenda & Kalender Akademik archive
 * Theme: mtsn1-modern
 */
get_header();

// Fetch all agendas ordered by event date ASC
$agenda_query = new WP_Query( array(
    'post_type'      => 'agenda',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_key'       => '_tevent',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
) );

$total_agenda = $agenda_query->found_posts;
$today_str    = date( 'Y-m-d' );

// Count semester items
$count_gasal = 0;
$count_genap = 0;
$count_libur = 0;

$all_events = array();
if ( $agenda_query->have_posts() ) {
    while ( $agenda_query->have_posts() ) {
        $agenda_query->the_post();
        $pid      = get_the_ID();
        $sem      = get_post_meta( $pid, '_semester', true );
        $kat      = get_post_meta( get_the_ID(), '_kategori', true );
        $tevent   = get_post_meta( $pid, '_tevent', true );
        $tgl_teks = get_post_meta( $pid, '_tgl_teks', true );
        $jam      = get_post_meta( $pid, '_jam', true );
        $lokasi   = get_post_meta( $pid, '_lokasi', true );
        $icon     = get_post_meta( $pid, '_icon', true );
        $badge    = get_post_meta( $pid, '_badge', true );

        if ( $sem === 'Semester Gasal' ) $count_gasal++;
        if ( $sem === 'Semester Genap' ) $count_genap++;
        if ( stripos( $kat, 'Libur' ) !== false ) $count_libur++;

        $all_events[] = array(
            'id'       => $pid,
            'title'    => get_the_title(),
            'link'     => get_permalink(),
            'excerpt'  => get_the_excerpt(),
            'content'  => get_the_content(),
            'tevent'   => $tevent,
            'tgl_teks' => $tgl_teks ? $tgl_teks : ( $tevent ? date_i18n( 'd F Y', strtotime( $tevent ) ) : '' ),
            'jam'      => ( $jam && $jam !== '00:00' ) ? $jam . ' WIB' : 'Sesuai Jadwal',
            'lokasi'   => $lokasi ? $lokasi : 'Madrasah',
            'semester' => $sem ? $sem : 'Umum',
            'kategori' => $kat ? $kat : 'Kegiatan',
            'icon'     => $icon ? $icon : 'fa-calendar-check',
            'badge'    => $badge ? $badge : 'bg-emerald-100 text-emerald-800',
            'is_past'  => $tevent && ( $tevent < $today_str ),
        );
    }
    wp_reset_postdata();
}
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
      <span>/</span>
      <span class="text-slate-800 font-semibold">Agenda & Kalender Akademik</span>
    </nav>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-950 via-teal-900 to-emerald-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden mb-10 border border-emerald-800/40">
      <!-- Decorative BG elements -->
      <div class="absolute -right-12 -bottom-12 w-72 h-72 rounded-full bg-emerald-500/10 pointer-events-none blur-3xl"></div>
      <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-10 text-9xl hidden lg:block pointer-events-none text-emerald-300">
        <i class="fa-solid fa-calendar-days"></i>
      </div>

      <div class="relative z-10 max-w-3xl">
        <div class="flex items-center gap-2 mb-3">
          <span class="px-3.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-gold-400 text-slate-950 inline-flex items-center gap-1.5 shadow-md">
            <i class="fa-solid fa-calendar-check text-slate-950"></i> Kalender Akademik
          </span>
          <span class="text-xs text-emerald-200 font-semibold hidden sm:inline">• Tahun Ajaran 2026/2027</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white drop-shadow-sm">
          Agenda & Kalender Madrasah
        </h1>
        <p class="text-emerald-100/90 text-sm sm:text-base mt-3 leading-relaxed">
          Jadwal resmi kegiatan madrasah, asesmen akhir semester (ASAS), ujian madrasah, pembagian rapor, peringatan hari besar keagamaan & nasional, serta libur madrasah MTsN 1 Blitar.
        </p>

        <!-- Quick Filters & Search -->
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
          <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
              <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" id="agenda-search" placeholder="Cari agenda, kegiatan, atau ujian..." class="w-full pl-11 pr-4 py-3 bg-white text-slate-900 rounded-2xl text-xs sm:text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-gold-400 shadow-md">
          </div>
          <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl text-xs font-bold transition flex items-center gap-2 border border-white/15 backdrop-blur-sm">
              <i class="fa-solid fa-print"></i> Cetak Jadwal
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Tabs (Interactive) -->
    <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto pb-4 mb-8 no-scrollbar">
      <button type="button" data-filter="all" class="agenda-tab-btn active px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 bg-emerald-700 text-white shadow-md">
        <span>Semua Agenda</span>
        <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/20"><?php echo esc_html( count( $all_events ) ); ?></span>
      </button>

      <button type="button" data-filter="gasal" class="agenda-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 bg-white text-slate-700 border border-slate-200 hover:border-emerald-500">
        <i class="fa-solid fa-leaf text-emerald-600"></i>
        <span>Semester Gasal</span>
        <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600"><?php echo esc_html( $count_gasal ); ?></span>
      </button>

      <button type="button" data-filter="genap" class="agenda-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 bg-white text-slate-700 border border-slate-200 hover:border-emerald-500">
        <i class="fa-solid fa-seedling text-teal-600"></i>
        <span>Semester Genap</span>
        <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600"><?php echo esc_html( $count_genap ); ?></span>
      </button>

      <button type="button" data-filter="libur" class="agenda-tab-btn px-4 py-2.5 rounded-2xl text-xs font-bold transition-all flex items-center gap-2 bg-white text-slate-700 border border-slate-200 hover:border-rose-400">
        <i class="fa-solid fa-umbrella-beach text-rose-500"></i>
        <span>Libur & Cuti</span>
        <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600"><?php echo esc_html( $count_libur ); ?></span>
      </button>
    </div>

    <!-- Agenda Grid / List -->
    <div id="agenda-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php 
      $id_months = array(
          1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
          5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
          9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
      );
      foreach ( $all_events as $item ) : 
          $tevent_ts = $item['tevent'] ? strtotime( $item['tevent'] ) : false;
          $m_num     = $tevent_ts ? (int) date( 'n', $tevent_ts ) : (int) date( 'n' );
          $month_str = $id_months[ $m_num ] ?? 'Nov';
          $day_str   = $tevent_ts ? date( 'd', $tevent_ts ) : '01';
          $year_str  = $tevent_ts ? date( 'Y', $tevent_ts ) : '2026';

          $filter_classes = array();
          if ( $item['semester'] === 'Semester Gasal' ) $filter_classes[] = 'type-gasal';
          if ( $item['semester'] === 'Semester Genap' ) $filter_classes[] = 'type-genap';
          if ( stripos( $item['kategori'], 'Libur' ) !== false ) $filter_classes[] = 'type-libur';
      ?>
        <div class="agenda-card bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:border-emerald-500 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group <?php echo esc_attr( implode( ' ', $filter_classes ) ); ?>"
             data-title="<?php echo esc_attr( strtolower( $item['title'] ) ); ?>"
             data-kategori="<?php echo esc_attr( strtolower( $item['kategori'] ) ); ?>"
             data-semester="<?php echo esc_attr( strtolower( $item['semester'] ) ); ?>">
          
          <div>
            <!-- Top Card Header: Date Badge & Semester Pill -->
            <div class="flex items-start justify-between gap-3 mb-4">
              <!-- Big Date Badge -->
              <div class="w-16 min-w-[64px] h-16 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-800 text-white flex flex-col items-center justify-center shrink-0 shadow-md group-hover:scale-105 transition-transform duration-300">
                <span class="text-[10px] uppercase font-bold tracking-wider leading-none text-emerald-100"><?php echo esc_html( $month_str ); ?></span>
                <span class="text-xl font-black leading-none mt-1"><?php echo esc_html( $day_str ); ?></span>
                <span class="text-[9px] font-semibold text-emerald-200 leading-none mt-0.5"><?php echo esc_html( $year_str ); ?></span>
              </div>

              <!-- Tags / Badges -->
              <div class="flex flex-col items-end gap-1.5">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider <?php echo esc_attr( $item['badge'] ); ?> shadow-xs">
                  <i class="fa-solid <?php echo esc_attr( $item['icon'] ); ?> mr-1"></i><?php echo esc_html( $item['kategori'] ); ?>
                </span>
                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">
                  <?php echo esc_html( $item['semester'] ); ?>
                </span>
              </div>
            </div>

            <!-- Full Date String if Range or Special -->
            <div class="mb-3 px-3 py-1.5 rounded-xl bg-amber-50/80 border border-amber-200/60 text-amber-900 text-xs font-bold flex items-center gap-2">
              <i class="fa-regular fa-calendar-days text-amber-600 flex-shrink-0"></i>
              <span class="truncate"><?php echo esc_html( $item['tgl_teks'] ); ?></span>
            </div>

            <!-- Event Title -->
            <h3 class="text-base font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug mb-2 line-clamp-2">
              <a href="<?php echo esc_url( $item['link'] ); ?>">
                <?php echo esc_html( $item['title'] ); ?>
              </a>
            </h3>

            <!-- Event Description Excerpt -->
            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
              <?php echo esc_html( wp_strip_all_tags( $item['excerpt'] ? $item['excerpt'] : $item['content'] ) ); ?>
            </p>
          </div>

          <!-- Bottom Card Meta: Location & Time -->
          <div class="pt-4 border-t border-slate-100 mt-2">
            <div class="space-y-1.5 text-xs text-slate-600 mb-4">
              <div class="flex items-center gap-2">
                <span class="w-5 text-emerald-600 text-center"><i class="fa-solid fa-location-dot"></i></span>
                <span class="font-medium truncate"><?php echo esc_html( $item['lokasi'] ); ?></span>
              </div>
              <div class="flex items-center gap-2">
                <span class="w-5 text-emerald-600 text-center"><i class="fa-regular fa-clock"></i></span>
                <span class="font-medium"><?php echo esc_html( $item['jam'] ); ?></span>
              </div>
            </div>

            <div class="flex items-center justify-between">
              <a href="<?php echo esc_url( $item['link'] ); ?>" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group/btn">
                <span>Rincian Agenda</span>
                <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
              </a>
              <span class="text-[11px] text-slate-400 font-medium">
                MTsN 1 Blitar
              </span>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <div id="agenda-empty" class="hidden text-center py-16 bg-white rounded-3xl border border-slate-200 mt-6">
      <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-4">
        <i class="fa-regular fa-calendar-xmark"></i>
      </div>
      <h3 class="text-lg font-bold text-slate-800">Tidak ada agenda yang cocok</h3>
      <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci pencarian lain atau pilih tab filter di atas.</p>
    </div>

    <!-- Info Box: Kalender Pendidikan Resmi Kemenag -->
    <div class="mt-14 p-6 sm:p-8 rounded-3xl bg-emerald-50 border border-emerald-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
      <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-md">
          <i class="fa-solid fa-circle-info"></i>
        </div>
        <div>
          <h4 class="text-base font-black text-emerald-950">Informasi Kalender Pendidikan</h4>
          <p class="text-xs sm:text-sm text-emerald-800/90 mt-1 leading-relaxed">
            Jadwal kegiatan akademik mengacu pada Surat Keputusan Kepala Kantor Wilayah Kementerian Agama dan Keputusan Madrasah mengenai Kalender Pendidikan Tahun Pelajaran 2026/2027. Perubahan jadwal sewaktu-waktu akan diumumkan melalui portal madrasah ini.
          </p>
        </div>
      </div>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="px-5 py-2.5 rounded-xl bg-white hover:bg-emerald-600 hover:text-white text-emerald-900 border border-emerald-300 font-bold text-xs transition shadow-sm flex-shrink-0">
        Kembali ke Beranda
      </a>
    </div>

  </div>
</div>

<!-- Interactive Tab & Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const tabs = document.querySelectorAll('.agenda-tab-btn');
  const cards = document.querySelectorAll('.agenda-card');
  const searchInput = document.getElementById('agenda-search');
  const emptyState = document.getElementById('agenda-empty');

  let activeFilter = 'all';
  let searchQuery = '';

  function applyFilters() {
    let visibleCount = 0;

    cards.forEach(card => {
      let matchesFilter = true;
      if (activeFilter === 'gasal') {
        matchesFilter = card.classList.contains('type-gasal');
      } else if (activeFilter === 'genap') {
        matchesFilter = card.classList.contains('type-genap');
      } else if (activeFilter === 'libur') {
        matchesFilter = card.classList.contains('type-libur');
      }

      let matchesSearch = true;
      if (searchQuery.trim() !== '') {
        const title = card.getAttribute('data-title') || '';
        const kat = card.getAttribute('data-kategori') || '';
        const sem = card.getAttribute('data-semester') || '';
        const text = title + ' ' + kat + ' ' + sem;
        matchesSearch = text.includes(searchQuery.toLowerCase().trim());
      }

      if (matchesFilter && matchesSearch) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    if (visibleCount === 0) {
      emptyState.classList.remove('hidden');
    } else {
      emptyState.classList.add('hidden');
    }
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      tabs.forEach(t => {
        t.classList.remove('bg-emerald-700', 'text-white', 'shadow-md');
        t.classList.add('bg-white', 'text-slate-700', 'border', 'border-slate-200');
      });

      this.classList.remove('bg-white', 'text-slate-700', 'border', 'border-slate-200');
      this.classList.add('bg-emerald-700', 'text-white', 'shadow-md');

      activeFilter = this.getAttribute('data-filter');
      applyFilters();
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', function() {
      searchQuery = this.value;
      applyFilters();
    });
  }
});
</script>

<?php get_footer(); ?>
