<?php
/**
 * The template for displaying Prestasi (Student Achievements / Hall of Fame)
 * Features Instagram Flyer aspect ratio (4:5 portrait)
 */
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$prestasi_query = new WP_Query( array(
    'post_type'      => 'prestasi',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

$total_prestasi = $prestasi_query->found_posts;
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
      <span>/</span>
      <span class="text-slate-800 font-semibold">Prestasi & Kejuaraan</span>
    </nav>

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden mb-10">
      <!-- Decorative BG elements -->
      <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/10 pointer-events-none blur-2xl"></div>
      <div class="absolute right-10 top-1/2 -translate-y-1/2 opacity-15 text-9xl hidden lg:block pointer-events-none">
        <i class="fa-solid fa-trophy"></i>
      </div>

      <div class="relative z-10 max-w-2xl">
        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-slate-950 text-gold-400 inline-flex items-center gap-1.5 mb-3 shadow-md">
          <i class="fa-solid fa-medal text-amber-400"></i> Hall of Fame MTsN 1 Blitar
        </span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white drop-shadow-sm">
          Galeri Prestasi & Kejuaraan
        </h1>
        <p class="text-amber-100 text-sm sm:text-base mt-3 leading-relaxed">
          Dokumentasi dan apresiasi atas dedikasi serta torehan medali juara putra-putri MTsN 1 Blitar di berbagai bidang akademik, keagamaan, olahraga, seni, dan riset robotik.
        </p>

        <!-- Quick Search Bar & Tingkat Filter -->
        <div class="mt-6 flex flex-col gap-3">
          <div class="relative max-w-md">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
              <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" id="prestasi-search" placeholder="Cari nama peraih juara atau kejuaraan..." class="w-full pl-11 pr-4 py-3 bg-white text-slate-900 rounded-2xl text-xs sm:text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900 shadow-md">
          </div>

          <!-- Filter Tingkat Kejuaraan -->
          <div class="flex flex-wrap gap-2 items-center pt-1" id="prestasi-filters">
            <button type="button" class="prestasi-filter-pill px-3.5 py-1.5 rounded-full text-xs font-black transition-all bg-amber-400 text-slate-950 shadow-md cursor-pointer" data-filter="all">Semua</button>
            <button type="button" class="prestasi-filter-pill px-3 py-1.5 rounded-full text-xs font-bold transition-all bg-white/20 text-white hover:bg-white/30 backdrop-blur-md cursor-pointer" data-filter="internasional"><i class="fa-solid fa-globe mr-1 text-purple-300"></i> Internasional</button>
            <button type="button" class="prestasi-filter-pill px-3 py-1.5 rounded-full text-xs font-bold transition-all bg-white/20 text-white hover:bg-white/30 backdrop-blur-md cursor-pointer" data-filter="nasional"><i class="fa-solid fa-flag mr-1 text-rose-300"></i> Nasional</button>
            <button type="button" class="prestasi-filter-pill px-3 py-1.5 rounded-full text-xs font-bold transition-all bg-white/20 text-white hover:bg-white/30 backdrop-blur-md cursor-pointer" data-filter="provinsi"><i class="fa-solid fa-award mr-1 text-blue-300"></i> Provinsi</button>
            <button type="button" class="prestasi-filter-pill px-3 py-1.5 rounded-full text-xs font-bold transition-all bg-white/20 text-white hover:bg-white/30 backdrop-blur-md cursor-pointer" data-filter="kab/kota"><i class="fa-solid fa-medal mr-1 text-amber-300"></i> Kab/Kota</button>
            <button type="button" class="prestasi-filter-pill px-3 py-1.5 rounded-full text-xs font-bold transition-all bg-white/20 text-white hover:bg-white/30 backdrop-blur-md cursor-pointer" data-filter="kecamatan"><i class="fa-solid fa-location-dot mr-1 text-teal-300"></i> Kecamatan</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Info counter -->
    <div class="flex items-center justify-between mb-8">
      <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">
        Menampilkan <span id="prestasi-count" class="text-amber-600 font-black"><?php echo esc_html( $total_prestasi ); ?></span> Flyer Prestasi Siswa
      </div>
      <span class="text-xs text-slate-400 hidden sm:inline"><i class="fa-brands fa-instagram text-pink-600 mr-1"></i> Format Flyer Instagram (4:5)</span>
    </div>

    <!-- Prestasi Grid (Instagram Flyer Ratio 4:5) -->
    <?php if ( $prestasi_query->have_posts() ) : ?>
      <div id="prestasi-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php while ( $prestasi_query->have_posts() ) : $prestasi_query->the_post();
            $id = get_the_ID();
            $peraih = get_post_meta( $id, '_peraih', true );
            $tingkat = mtsn1_get_tingkat_prestasi( $id );
            $thumb = get_the_post_thumbnail_url( $id, 'full' );
            $ig_url = get_post_meta( $id, '_instagram_url', true );
        ?>
          <div class="prestasi-card bg-white rounded-3xl p-5 shadow-sm border border-slate-200 hover:shadow-2xl hover:border-amber-400 transition-all duration-300 flex flex-col justify-between group"
               data-title="<?php echo esc_attr( strtolower( get_the_title() ) ); ?>"
               data-peraih="<?php echo esc_attr( strtolower( $peraih ) ); ?>"
               data-tingkat="<?php echo esc_attr( strtolower( $tingkat ) ); ?>">

            <div>
              <!-- Header Badges Bar di Atas Flyer (Sama Persis dengan Single Flyer, Bebas Tutupan Gambar) -->
              <div class="mb-3 flex items-center justify-between gap-2 px-0.5">
                <span class="px-2.5 py-1 rounded-full text-[11px] sm:text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 shadow-sm flex items-center gap-1.5 shrink-0">
                  <i class="fa-solid fa-trophy text-amber-600 text-xs"></i>
                  <span>Prestasi Juara</span>
                </span>
                <div class="shrink-0">
                  <?php echo mtsn1_render_tingkat_badge( $tingkat ); ?>
                </div>
              </div>

              <!-- Instagram Flyer Image Container (Aspect Ratio 4:5 with Zero Crop & Zero Overlay) -->
              <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-2xl aspect-[4/5] bg-slate-950 mb-4 relative shadow-md group-hover:shadow-lg transition flex items-center justify-center">
                <?php if ( $thumb ) : ?>
                  <!-- Ambient blurred background fills any letterbox with matching flyer colors -->
                  <img src="<?php echo esc_url( $thumb ); ?>" class="absolute inset-0 w-full h-full object-cover blur-xl opacity-40 scale-110 pointer-events-none" aria-hidden="true">
                  <!-- Main Flyer image: 100% contained, zero cropping -->
                  <img src="<?php echo esc_url( $thumb ); ?>"
                       alt="<?php the_title(); ?>"
                       class="relative z-10 w-full h-full object-contain p-0.5 group-hover:scale-102 transition-transform duration-500">
                <?php else : ?>
                  <div class="w-full h-full bg-gradient-to-b from-amber-600 to-amber-900 flex flex-col items-center justify-center text-white p-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-white/10 flex items-center justify-center text-4xl mb-3">
                      <i class="fa-solid fa-trophy text-amber-300"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-200">Prestasi Madrasah</span>
                    <h4 class="text-sm font-black mt-2 leading-snug line-clamp-2"><?php the_title(); ?></h4>
                  </div>
                <?php endif; ?>
              </a>

              <!-- Peraih Prestasi (Student Name) -->
              <?php if ( $peraih ) : ?>
                <div class="mb-2 p-2.5 rounded-xl bg-amber-50 border border-amber-200/80 flex items-start gap-2">
                  <div class="w-6 h-6 rounded-lg bg-amber-500 text-slate-950 flex items-center justify-center shrink-0 text-xs font-black mt-0.5">
                    <i class="fa-solid fa-user-graduate"></i>
                  </div>
                  <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-800 block">Peraih Prestasi:</span>
                    <p class="text-xs font-black text-slate-900 truncate"><?php echo esc_html( $peraih ); ?></p>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Meta: Date & Views -->
              <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                <span><i class="fa-regular fa-calendar text-amber-600 mr-1"></i> <?php echo get_the_date( 'd M Y' ); ?></span>
                <span>•</span>
                <span>MTsN 1 Blitar</span>
              </div>

              <!-- Title -->
              <h2 class="text-base font-black text-slate-900 group-hover:text-amber-700 transition leading-snug line-clamp-2">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>

              <!-- Excerpt -->
              <?php if ( get_the_excerpt() ) : ?>
                <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                  <?php echo wp_trim_words( get_the_excerpt(), 18, '...' ); ?>
                </p>
              <?php endif; ?>
            </div>

            <!-- Footer Action Button -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
              <a href="<?php the_permalink(); ?>" class="text-xs font-extrabold text-amber-700 hover:text-amber-800 flex items-center gap-1.5 group-hover:translate-x-0.5 transition">
                <span>Lihat Detail Flyer</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
              </a>
              <?php if ( $ig_url ) : ?>
                <a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" rel="noopener noreferrer" class="text-xs text-pink-600 hover:text-pink-700 font-bold flex items-center gap-1 hover:underline" title="Lihat postingan asli di Instagram">
                  <i class="fa-brands fa-instagram text-sm"></i>
                  <span class="text-[11px]">Instagram</span>
                </a>
              <?php else : ?>
                <span class="text-[11px] text-slate-400 font-medium">Prestasi Siswa</span>
              <?php endif; ?>
            </div>

          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>

      <!-- No Results Container -->
      <div id="prestasi-empty" class="hidden text-center py-16 bg-white rounded-3xl border border-slate-200 mt-6">
        <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-3">
          <i class="fa-solid fa-medal"></i>
        </div>
        <h3 class="text-base font-bold text-slate-800">Prestasi tidak ditemukan</h3>
        <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
      </div>

      <!-- Pagination -->
      <div class="mt-10">
        <?php mtsn1_pagination( $prestasi_query ); ?>
      </div>

    <?php else : ?>
      <div class="text-center py-16 bg-white rounded-3xl border border-slate-200">
        <div class="w-16 h-16 mx-auto rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-2xl mb-4">
          <i class="fa-solid fa-trophy"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Belum ada data prestasi yang dipublikasikan</h3>
        <p class="text-xs text-slate-500 mt-1">Silakan tambahkan data prestasi melalui admin WordPress.</p>
      </div>
    <?php endif; ?>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('prestasi-search');
  const cards = Array.from(document.querySelectorAll('.prestasi-card'));
  const countEl = document.getElementById('prestasi-count');
  const emptyEl = document.getElementById('prestasi-empty');
  const filterPills = Array.from(document.querySelectorAll('.prestasi-filter-pill'));

  let activeFilter = 'all';

  function filterCards() {
    const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
    let visibleCount = 0;

    cards.forEach(card => {
      const title = card.dataset.title || '';
      const peraih = card.dataset.peraih || '';
      const tingkat = (card.dataset.tingkat || '').toLowerCase();

      const matchSearch = !q || title.includes(q) || peraih.includes(q) || tingkat.includes(q);
      const matchFilter = (activeFilter === 'all') || (tingkat === activeFilter);

      if (matchSearch && matchFilter) {
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

  filterPills.forEach(pill => {
    pill.addEventListener('click', function () {
      activeFilter = (this.getAttribute('data-filter') || 'all').toLowerCase();

      filterPills.forEach(p => {
        p.classList.remove('bg-amber-400', 'text-slate-950', 'shadow-md');
        p.classList.add('bg-white/20', 'text-white');
      });

      this.classList.remove('bg-white/20', 'text-white');
      this.classList.add('bg-amber-400', 'text-slate-950', 'shadow-md');

      filterCards();
    });
  });
});
</script>

<?php get_footer(); ?>
