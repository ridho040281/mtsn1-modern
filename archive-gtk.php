<?php
/**
 * The template for displaying GTK (Guru & Tenaga Kependidikan) archive / directory page
 */
get_header();

// Fetch all GTK for comprehensive directory
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$gtk_query = new WP_Query( array(
    'post_type'      => 'gtk',
    'posts_per_page' => 100, // Show all teachers or paginate
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
) );

$total_gtk = $gtk_query->found_posts;
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
      <span>/</span>
      <span class="text-slate-800 font-semibold">Guru & Tenaga Kependidikan</span>
    </nav>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden mb-10">
      <!-- Islamic Pattern BG Decorative -->
      <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none blur-2xl"></div>
      <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-10 text-9xl hidden lg:block pointer-events-none">
        <i class="fa-solid fa-chalkboard-user"></i>
      </div>

      <div class="relative z-10 max-w-2xl">
        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-gold-400 text-slate-900 inline-block mb-3">
          Civitas Akademika MTsN 1 Blitar
        </span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
          Direktori Guru & Tenaga Kependidikan
        </h1>
        <p class="text-slate-200 text-sm sm:text-base mt-3 leading-relaxed">
          Mengenal <?php echo esc_html( $total_gtk ); ?> pendidik profesional dan staf kependidikan berdedikasi tinggi yang membimbing putra-putri madrasah meraih prestasi dunia & akhirat.
        </p>

        <!-- Quick Search Bar -->
        <div class="mt-6">
          <div class="relative max-w-md">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
              <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" id="gtk-live-search" placeholder="Cari nama guru, NIP, atau mata pelajaran..." class="w-full pl-11 pr-4 py-3 bg-white text-slate-900 rounded-2xl text-xs sm:text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-gold-400 shadow-md">
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
      <div class="flex flex-wrap items-center gap-2" id="gtk-filter-tabs">
        <button type="button" data-filter="all" class="filter-btn active px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white shadow-sm transition">
          Semua (<?php echo esc_html( $total_gtk ); ?>)
        </button>
        <button type="button" data-filter="pns" class="filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 transition">
          PNS
        </button>
        <button type="button" data-filter="pppk" class="filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 transition">
          PPPK
        </button>
        <button type="button" data-filter="honorer" class="filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 transition">
          Non-PNS / Honorer
        </button>
        <button type="button" data-filter="guru" class="filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 transition">
          Dewan Guru
        </button>
        <button type="button" data-filter="tu" class="filter-btn px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 transition">
          Tata Usaha (TU)
        </button>
      </div>

      <div class="text-xs font-semibold text-slate-500">
        Menampilkan <span id="gtk-count" class="text-emerald-700 font-bold"><?php echo esc_html( $total_gtk ); ?></span> civitas
      </div>
    </div>

    <!-- GTK Grid Directory (5 Kolom per Baris di Desktop) -->
    <?php if ( $gtk_query->have_posts() ) : ?>
      <div id="gtk-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5 sm:gap-4 lg:gap-5">
        <?php while ( $gtk_query->have_posts() ) : $gtk_query->the_post();
            $id = get_the_ID();
            $nip   = get_post_meta( $id, '_nip', true );
            $nuptk = get_post_meta( $id, '_nuptk', true );
            $thumb = get_the_post_thumbnail_url( $id, 'medium' );

            $jab_terms   = get_the_terms( $id, 'jab' );
            $stts_terms  = get_the_terms( $id, 'stts' );
            $mapel_terms = get_the_terms( $id, 'mapel' );

            $jab_name  = ( ! empty( $jab_terms ) && ! is_wp_error( $jab_terms ) ) ? $jab_terms[0]->name : 'Tenaga Pendidik';
            $stts_name = ( ! empty( $stts_terms ) && ! is_wp_error( $stts_terms ) ) ? $stts_terms[0]->name : 'Aktif';
            $mapel_name= ( ! empty( $mapel_terms ) && ! is_wp_error( $mapel_terms ) ) ? $mapel_terms[0]->name : '';

            // Classification for JS filtering
            $stts_lower = strtolower( $stts_name );
            $jab_lower  = strtolower( $jab_name );
            $is_pns     = ( stripos( $stts_lower, 'pns' ) !== false && stripos( $stts_lower, 'non' ) === false );
            $is_pppk    = ( stripos( $stts_lower, 'pppk' ) !== false || stripos( $stts_lower, 'p3k' ) !== false );
            $is_honorer = ( stripos( $stts_lower, 'honorer' ) !== false || stripos( $stts_lower, 'non' ) !== false || stripos( $stts_lower, 'gtt' ) !== false || stripos( $stts_lower, 'ptt' ) !== false );
            $is_tu      = ( stripos( $jab_lower, 'tata usaha' ) !== false || stripos( $jab_lower, 'tu' ) !== false || stripos( $jab_lower, 'staf' ) !== false );
            $is_guru    = ! $is_tu;

            $classes = array();
            if ( $is_pns ) $classes[] = 'type-pns';
            if ( $is_pppk ) $classes[] = 'type-pppk';
            if ( $is_honorer ) $classes[] = 'type-honorer';
            if ( $is_guru ) $classes[] = 'type-guru';
            if ( $is_tu ) $classes[] = 'type-tu';

            // Status Badge Styling
            $badge_style = 'bg-slate-700 text-white';
            if ( $is_pns ) {
                $badge_style = 'bg-emerald-600 text-white';
            } elseif ( $is_pppk ) {
                $badge_style = 'bg-amber-400 text-slate-950 font-black';
            } elseif ( $is_honorer ) {
                $badge_style = 'bg-amber-600 text-white';
            }
        ?>
          <div class="gtk-card bg-white rounded-2xl sm:rounded-3xl p-3.5 sm:p-4 shadow-sm border border-slate-200 hover:shadow-xl hover:border-emerald-500 transition duration-300 flex flex-col justify-between group <?php echo esc_attr( implode( ' ', $classes ) ); ?>"
               data-name="<?php echo esc_attr( strtolower( get_the_title() ) ); ?>"
               data-nip="<?php echo esc_attr( $nip ); ?>"
               data-jab="<?php echo esc_attr( strtolower( $jab_name . ' ' . $mapel_name ) ); ?>">

            <div>
              <!-- Photo Container (Aspect 3:4 Portrait) -->
              <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-xl sm:rounded-2xl aspect-[3/4] bg-slate-100 mb-3 relative shadow-inner">
                <?php if ( $thumb ) : ?>
                  <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500">
                <?php else : ?>
                  <div class="w-full h-full bg-gradient-to-b from-slate-50 to-slate-200 flex flex-col items-center justify-center text-slate-400 group-hover:text-emerald-600 transition">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-white shadow-sm flex items-center justify-center text-2xl text-slate-400">
                      <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase mt-2 tracking-wider">MTsN 1 Blitar</span>
                  </div>
                <?php endif; ?>

                <!-- Status Badge -->
                <div class="absolute top-2 right-2">
                  <span class="px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-wider <?php echo esc_attr( $badge_style ); ?> shadow-sm">
                    <?php echo esc_html( $stts_name ); ?>
                  </span>
                </div>
              </a>

              <!-- Info -->
              <div>
                <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md inline-block mb-1 line-clamp-1" title="<?php echo esc_attr( $jab_name ); ?>">
                  <?php echo esc_html( $jab_name ); ?>
                </span>

                <h3 class="text-xs sm:text-sm font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug line-clamp-2 min-h-[2.5rem]">
                  <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                  </a>
                </h3>

                <?php if ( $nip ) : ?>
                  <p class="text-[10px] sm:text-[11px] font-mono font-semibold text-slate-500 mt-1 flex items-center gap-1 truncate">
                    <span class="text-slate-400 text-[9px]">NIP:</span> <?php echo esc_html( $nip ); ?>
                  </p>
                <?php elseif ( $nuptk ) : ?>
                  <p class="text-[10px] sm:text-[11px] font-mono font-semibold text-slate-500 mt-1 flex items-center gap-1 truncate">
                    <span class="text-slate-400 text-[9px]">NUPTK:</span> <?php echo esc_html( $nuptk ); ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>

            <!-- Footer Card Button -->
            <div class="mt-3 pt-2.5 border-t border-slate-100">
              <a href="<?php the_permalink(); ?>" class="w-full py-1.5 sm:py-2 rounded-xl bg-slate-100 group-hover:bg-emerald-600 group-hover:text-white text-slate-700 text-[11px] sm:text-xs font-bold transition flex items-center justify-center gap-1 shadow-sm">
                <span>Lihat Profil</span>
                <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition"></i>
              </a>
            </div>

          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>

      <!-- No Results Message (Hidden by default) -->
      <div id="gtk-empty" class="hidden text-center py-16 bg-white rounded-3xl border border-slate-200 mt-6">
        <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-3">
          <i class="fa-solid fa-user-slash"></i>
        </div>
        <h3 class="text-base font-bold text-slate-800">Tidak ada GTK yang cocok</h3>
        <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
      </div>

    <?php else : ?>
      <div class="text-center py-16 bg-white rounded-3xl border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800">Belum ada data Guru & Tenaga Kependidikan</h3>
        <p class="text-xs text-slate-500 mt-1">Silakan tambahkan data GTK melalui menu Admin WordPress.</p>
      </div>
    <?php endif; ?>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('gtk-live-search');
  const cards = Array.from(document.querySelectorAll('.gtk-card'));
  const countEl = document.getElementById('gtk-count');
  const emptyEl = document.getElementById('gtk-empty');
  const filterBtns = document.querySelectorAll('.filter-btn');

  let activeFilter = 'all';

  function filterCards() {
    const q = (searchInput ? searchInput.value : '').toLowerCase().trim();
    let visibleCount = 0;

    cards.forEach(card => {
      const name = card.dataset.name || '';
      const nip = card.dataset.nip || '';
      const jab = card.dataset.jab || '';

      const matchesSearch = !q || name.includes(q) || nip.includes(q) || jab.includes(q);
      let matchesTab = true;

      if (activeFilter === 'pns') {
        matchesTab = card.classList.contains('type-pns');
      } else if (activeFilter === 'pppk') {
        matchesTab = card.classList.contains('type-pppk');
      } else if (activeFilter === 'honorer') {
        matchesTab = card.classList.contains('type-honorer');
      } else if (activeFilter === 'guru') {
        matchesTab = card.classList.contains('type-guru');
      } else if (activeFilter === 'tu') {
        matchesTab = card.classList.contains('type-tu');
      }

      if (matchesSearch && matchesTab) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    if (countEl) countEl.textContent = visibleCount;
    if (emptyEl) {
      if (visibleCount === 0) {
        emptyEl.classList.remove('hidden');
      } else {
        emptyEl.classList.add('hidden');
      }
    }
  }

  if (searchInput) {
    searchInput.addEventListener('input', filterCards);
  }

  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => {
        b.classList.remove('active', 'bg-emerald-600', 'text-white');
        b.classList.add('bg-white', 'text-slate-700');
      });
      this.classList.add('active', 'bg-emerald-600', 'text-white');
      this.classList.remove('bg-white', 'text-slate-700');

      activeFilter = this.dataset.filter;
      filterCards();
    });
  });
});
</script>

<?php get_footer(); ?>
