<?php
/**
 * The template for displaying the Grand Educational Portal Homepage (MTsN 1 Blitar)
 */
get_header();
?>

  <!-- GRAND HERO SECTION: BANNER SLIDER + SIDEBAR PENGUMUMAN & AGENDA -->
  <section class="py-8 bg-slate-900 text-white relative overflow-hidden">
    <!-- Decorative subtle pattern background -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Slider Banner Utama Madrasah (Full-Width Megah) -->
          <?php
          // Query slide gambar
          $slider_query = new WP_Query( array(
              'post_type'      => 'slider',
              'posts_per_page' => 5,
              'post_status'    => 'publish',
          ) );

          $slides_data = array();

          if ( $slider_query->have_posts() ) {
              while ( $slider_query->have_posts() ) {
                  $slider_query->the_post();
                  $thumb = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                  if ( ! $thumb ) {
                      $thumb = home_url( '/wp-content/uploads/2026/07/Slide-Website.webp' );
                  }
                  $slides_data[] = array(
                      'title' => get_the_title(),
                      'desc'  => wp_trim_words( get_the_excerpt() ? get_the_excerpt() : get_the_content(), 18, '...' ),
                      'img'   => $thumb,
                      'link'  => get_permalink(),
                  );
              }
              wp_reset_postdata();
          }

          // Fallback slides if none found
          if ( empty( $slides_data ) ) {
              $slides_data = array(
                  array(
                      'title' => 'Ekstrakurikuler Robotik & Sains Terpadu MTsN 1 Blitar',
                      'desc'  => 'Mempersiapkan generasi muslim berdaya saing global melalui penguasaan teknologi terbarukan, AI, dan sains terapan.',
                      'img'   => home_url( '/wp-content/uploads/2026/07/Slide-Website.webp' ),
                      'link'  => '#',
                  ),
                  array(
                      'title' => 'Program Unggulan Tahfidzul Qur\'an & Pendalaman Kitab Kuning',
                      'desc'  => 'Mencetak penghafal Al-Qur\'an mutqin yang beradab luhur dan berwawasan moderasi beragama.',
                      'img'   => home_url( '/wp-content/uploads/2026/07/Slide-Website-1.webp' ),
                      'link'  => '#',
                  ),
                  array(
                      'title' => 'Milad ke-57 MTsN 1 Blitar: Aryakasiga Penggalang Berprestasi',
                      'desc'  => 'Ajang kreasi, kompetisi keterampilan kepramukaan, dan unjuk bakat siswa madrasah se-Jawa Timur.',
                      'img'   => home_url( '/wp-content/uploads/2026/07/Slide-Website-2.webp' ),
                      'link'  => '#',
                  ),
              );
          }
          $first_slide = $slides_data[0];
          ?>

          <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-700/60 group bg-slate-950 aspect-[16/9] sm:aspect-[16/8]">
            <!-- Slide Image -->
            <img id="mainSliderImg" src="<?php echo esc_url( $first_slide['img'] ); ?>" alt="Banner Madrasah" class="w-full h-full object-cover transition-opacity duration-700" decoding="async">
            
            <!-- Overlay Gradient & Text Caption -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent flex flex-col justify-end p-6 sm:p-8">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-emerald-600 text-white text-xs font-bold w-fit mb-2 shadow-sm">
                <i class="fa-solid fa-star text-gold-400"></i> UNGGULAN MADRASAH
              </span>
              <h2 id="mainSliderTitle" class="text-xl sm:text-2xl lg:text-3xl font-black text-white leading-tight">
                <?php echo esc_html( $first_slide['title'] ); ?>
              </h2>
              <p id="mainSliderDesc" class="text-xs sm:text-sm text-slate-300 mt-2 line-clamp-2 max-w-2xl">
                <?php echo esc_html( $first_slide['desc'] ); ?>
              </p>
            </div>

            <!-- Slider Control Buttons -->
            <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-slate-900/70 hover:bg-emerald-600 text-white flex items-center justify-center backdrop-blur-sm transition border border-white/20">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-slate-900/70 hover:bg-emerald-600 text-white flex items-center justify-center backdrop-blur-sm transition border border-white/20">
              <i class="fa-solid fa-chevron-right"></i>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-3 right-6 flex items-center gap-1.5 z-20">
              <?php foreach ( $slides_data as $idx => $s ) : ?>
                <span id="dot<?php echo esc_attr( $idx ); ?>" class="<?php echo $idx === 0 ? 'w-7 h-2 rounded-full bg-emerald-500' : 'w-2 h-2 rounded-full bg-white/40'; ?> transition-all"></span>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Quick Stat Counters Bar -->
          <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3">
            <div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-2.5 sm:p-3.5 flex items-center gap-2.5 sm:gap-3 min-w-0">
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-base sm:text-lg shrink-0"><i class="fa-solid fa-award"></i></div>
              <div class="min-w-0">
                <p class="text-sm sm:text-lg font-black text-white leading-tight">Akreditasi A</p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Predikat Unggul</p>
              </div>
            </div>
            <div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-2.5 sm:p-3.5 flex items-center gap-2.5 sm:gap-3 min-w-0">
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gold-500/20 text-gold-400 flex items-center justify-center text-base sm:text-lg shrink-0"><i class="fa-solid fa-users"></i></div>
              <div class="min-w-0">
                <p class="text-sm sm:text-lg font-black text-white leading-tight">1.250+</p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Siswa Aktif</p>
              </div>
            </div>
            <div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-2.5 sm:p-3.5 flex items-center gap-2.5 sm:gap-3 min-w-0">
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-base sm:text-lg shrink-0"><i class="fa-solid fa-chalkboard-user"></i></div>
              <div class="min-w-0">
                <p class="text-sm sm:text-lg font-black text-white leading-tight">77 GTK</p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Guru & Tendik</p>
              </div>
            </div>
            <div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-2.5 sm:p-3.5 flex items-center gap-2.5 sm:gap-3 min-w-0">
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-base sm:text-lg shrink-0"><i class="fa-solid fa-trophy"></i></div>
              <div class="min-w-0">
                <p class="text-sm sm:text-lg font-black text-white leading-tight">180+</p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 truncate">Piala Prestasi</p>
              </div>
            </div>
          </div>
    </div>
  </section>

  <!-- DEDICATED SECTION: SAMBUTAN KEPALA MADRASAH (MODERN EDITORIAL STYLE) -->
  <?php
  // Query data Editorial Kepala Madrasah (Menu Editorial di WP-Admin)
  $editorial_query = new WP_Query( array(
      'post_type'      => 'editorial',
      'posts_per_page' => 1,
      'post_status'    => 'publish',
  ) );

  $editorial_title    = 'Sekapur Sirih Pimpinan';
  $editorial_link     = home_url( '/sambutan-kepala-madrasah' );
  $editorial_img      = '';
  $editorial_content  = '';
  $editorial_headline = 'Mendidik Generasi Beradab, Unggul dalam Prestasi, dan Cakap Digital';
  $kepala_nama        = get_theme_mod( 'kepala_sekolah', 'Drs. SIHABBUDIN' );

  if ( $editorial_query->have_posts() ) {
      while ( $editorial_query->have_posts() ) {
          $editorial_query->the_post();
          $editorial_title    = get_the_title();
          $editorial_link     = get_permalink();
          $editorial_content  = get_the_content();
          $custom_headline    = get_post_meta( get_the_ID(), '_editorial_headline', true );
          if ( ! empty( $custom_headline ) ) {
              $editorial_headline = $custom_headline;
          } elseif ( has_excerpt() ) {
              $editorial_headline = get_the_excerpt();
          }

          // Cek foto potret khusus beranda atau gambar utama
          $custom_foto_depan = get_post_meta( get_the_ID(), '_foto_kepala_depan', true );
          if ( ! empty( $custom_foto_depan ) ) {
              $editorial_img = $custom_foto_depan;
          } elseif ( has_post_thumbnail() ) {
              $editorial_img = get_the_post_thumbnail_url( get_the_ID(), 'full' );
          }
      }
      wp_reset_postdata();
  }

  // Fallback gambar jika postingan editorial belum diset gambarnya
  if ( empty( $editorial_img ) ) {
      if ( get_theme_mod( 'foto_kepala' ) ) {
          $editorial_img = get_theme_mod( 'foto_kepala' );
      } else {
          $editorial_img = get_template_directory_uri() . '/assets/img/kepala-madrasah.webp';
      }
  }

  // Auto-optimize: Ganti gambar PNG mentah (8.7MB) dengan WebP terkompresi tema (31KB)
  if ( strpos( $editorial_img, 'DSC05956' ) !== false || strpos( $editorial_img, 'sihab-web-copy' ) !== false ) {
      $editorial_img = get_template_directory_uri() . '/assets/img/kepala-madrasah.webp';
  }
  ?>
  <section class="py-16 sm:py-20 bg-white relative overflow-hidden border-b border-slate-200">
    <!-- Subtle Background Glow -->
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-emerald-50 rounded-full blur-3xl opacity-70 pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-amber-50 rounded-full blur-3xl opacity-70 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
        <!-- Left: 5 cols portrait photo (Tanpa Frame & Transparan) -->
        <div class="lg:col-span-5 flex justify-center items-center relative">
          <!-- Subtle ambient glow circle behind cutout -->
          <div class="absolute w-72 h-72 sm:w-80 sm:h-80 bg-gradient-to-br from-emerald-100/70 via-amber-100/40 to-emerald-50/20 rounded-full blur-2xl pointer-events-none -z-0"></div>

          <div class="relative z-10 w-full max-w-sm flex justify-center">
            <img src="<?php echo esc_url( $editorial_img ); ?>" 
                 alt="<?php echo esc_attr( $kepala_nama . ' - Kepala MTsN 1 Blitar' ); ?>" 
                 class="w-auto max-h-[420px] sm:max-h-[480px] lg:max-h-[520px] object-contain drop-shadow-[0_20px_35px_rgba(0,0,0,0.15)] hover:scale-105 transition-transform duration-500" 
                 width="400"
                 height="520"
                 loading="eager"
                 fetchpriority="high"
                 onerror="this.onerror=null; this.src='<?php echo esc_url( get_template_directory_uri() . '/assets/img/kepala-madrasah.webp' ); ?>';">
          </div>
        </div>

        <!-- Right: 7 cols editorial speech narrative -->
        <div class="lg:col-span-7">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200 mb-4 shadow-sm">
            <i class="fa-solid fa-quote-left text-emerald-600"></i> <?php echo esc_html( $editorial_title ); ?>
          </div>
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight mb-5">
            "<?php echo esc_html( trim( $editorial_headline, "\"'“”" ) ); ?>"
          </h2>
          <?php if ( ! empty( $editorial_content ) ) : ?>
            <div class="space-y-4 text-sm sm:text-base text-slate-600 leading-relaxed">
              <?php
              $paragraphs = explode( '</p>', $editorial_content );
              $clean_paragraphs = array();
              foreach ( $paragraphs as $p ) {
                  $p = trim( strip_tags( $p, '<strong><em><b><i>' ) );
                  if ( ! empty( $p ) ) {
                      $clean_paragraphs[] = '<p>' . $p . '</p>';
                  }
                  if ( count( $clean_paragraphs ) >= 3 ) {
                      break;
                  }
              }
              if ( ! empty( $clean_paragraphs ) ) {
                  echo implode( '', $clean_paragraphs );
              } else {
                  echo '<p>' . wp_trim_words( strip_tags( $editorial_content ), 70, '...' ) . '</p>';
              }
              ?>
            </div>
          <?php else : ?>
            <div class="space-y-4 text-sm sm:text-base text-slate-600 leading-relaxed">
              <p class="font-semibold text-slate-850">
                Assalamu’alaikum Warahmatullahi Wabarakatuh.
              </p>
              <p>
                Selamat datang di portal informasi resmi <strong>Madrasah Tsanawiyah Negeri 1 Blitar</strong>. Sebagai salah satu lembaga pendidikan Islam unggulan di Kabupaten Blitar, kami senantiasa berikhtiar mendidik para tunas bangsa agar tidak hanya cakap menguasai ilmu pengetahuan dan teknologi modern, namun juga kokoh memegang nilai-nilai tauhid dan berakhlakul karimah.
              </p>
              <p>
                Melalui integrasi kurikulum terpadu, program tahfidz, riset sains dan robotik, serta lingkungan madrasah adiwiyata yang asri, kami siap mengantarkan putra-putri panjenengan meraih prestasi tertinggi dan menjadi pribadi yang membanggakan bagi agama, nusa, dan bangsa.
              </p>
            </div>
          <?php endif; ?>
          <div class="mt-8 pt-6 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
              <div class="w-1.5 h-12 rounded-full bg-gradient-to-b from-emerald-600 via-amber-400 to-emerald-700"></div>
              <div>
                <h4 class="text-lg sm:text-xl font-black text-slate-900 leading-tight"><?php echo esc_html( $kepala_nama ); ?></h4>
                <p class="text-xs sm:text-sm text-emerald-700 font-semibold mt-0.5">Kepala MTsN 1 Blitar <span class="text-slate-400 font-normal">| Masa Khidmat 2022 - Sekarang</span></p>
              </div>
            </div>
            <a href="<?php echo esc_url( $editorial_link ); ?>" class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-slate-900 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md hover:shadow-xl hover:-translate-y-0.5 transition-all group">
              <span>Baca Sambutan Lengkap</span>
              <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform text-gold-400"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- KABAR & BERITA MADRASAH (JEJER 3 KARTU LEGA) -->
  <section id="berita" class="py-14 bg-slate-100 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Section Header -->
      <div class="flex flex-col sm:flex-row sm:items-end justify-between pb-6 border-b border-slate-300 gap-4 mb-8">
        <div>
          <span class="text-xs font-black uppercase text-emerald-700 tracking-wider bg-emerald-50 px-3 py-1 rounded-full">Warta Terkini</span>
          <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">Kabar & Berita Madrasah</h3>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">Liputan kegiatan, prestasi murid, dan warta seputar civitas akademika MTsN 1 Blitar.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <!-- Category Tabs -->
          <div class="flex items-center gap-1.5 p-1 bg-white rounded-xl border border-slate-200 text-xs font-bold shadow-sm">
            <a href="<?php echo esc_url( home_url( '/#berita' ) ); ?>" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 text-white shadow-sm">Semua</a>
            <a href="<?php echo esc_url( home_url( '/prestasi' ) ); ?>" class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-emerald-700 hover:bg-slate-100 transition">Prestasi</a>
            <a href="<?php echo esc_url( home_url( '/category/kesiswaan' ) ); ?>" class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-emerald-700 hover:bg-slate-100 transition">Kesiswaan</a>
            <a href="<?php echo esc_url( home_url( '/category/kurikulum' ) ); ?>" class="px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-emerald-700 hover:bg-slate-100 transition">Akademik</a>
          </div>
          <a href="<?php echo esc_url( home_url( '/berita' ) ); ?>" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-emerald-600 hover:text-white text-xs font-bold text-slate-700 transition flex items-center gap-1.5 shadow-sm">
            Semua Berita <i class="fa-solid fa-arrow-right text-[10px]"></i>
          </a>
        </div>
      </div>

      <!-- Grid Berita Jejer 3 Kartu -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $news_q = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
        ) );

        if ( $news_q->have_posts() ) :
            while ( $news_q->have_posts() ) : $news_q->the_post();
                $thumb = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                $cats = get_the_category();
                $cat_name = ! empty( $cats ) ? $cats[0]->name : 'Warta';
                ?>
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200 hover:shadow-2xl hover:border-emerald-500 transition-all duration-300 group flex flex-col justify-between">
                  <div>
                    <!-- Photo Container Aspect 16:10 -->
                    <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-2xl aspect-[16/10] bg-slate-100 mb-4 relative shadow-sm">
                      <?php if ( $thumb ) : ?>
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                      <?php else : ?>
                        <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/santri.jpg' ) ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                      <?php endif; ?>
                      <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-md">
                          <?php echo esc_html( $cat_name ); ?>
                        </span>
                      </div>
                    </a>

                    <!-- Meta -->
                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                      <span><i class="fa-regular fa-calendar text-emerald-600 mr-1"></i> <?php echo get_the_date( 'd M Y' ); ?></span>
                      <span>•</span>
                      <span><i class="fa-regular fa-clock text-slate-400 mr-1"></i> <?php echo esc_html( mtsn1_reading_time() ); ?></span>
                    </div>

                    <!-- Title -->
                    <h4 class="text-base font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug line-clamp-2">
                      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>

                    <!-- Excerpt -->
                    <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                      <?php echo wp_trim_words( get_the_excerpt(), 22, '...' ); ?>
                    </p>
                  </div>

                  <!-- Footer Card -->
                  <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <a href="<?php the_permalink(); ?>" class="font-extrabold text-emerald-700 group-hover:underline flex items-center gap-1">
                      <span>Baca Selengkapnya</span> &rarr;
                    </a>
                    <span class="text-slate-400 font-medium"><i class="fa-regular fa-user text-slate-400 mr-1"></i> <?php the_author(); ?></span>
                  </div>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
      </div>

    </div>
  </section>

  <!-- PORTAL LAYANAN APLIKASI DIGITAL MADRASAH -->
  <section class="py-10 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-2 mb-6">
        <div>
          <span class="text-xs font-black uppercase text-emerald-700 tracking-wider">Ekosistem Digital</span>
          <h3 class="text-2xl font-black text-slate-900">Portal Layanan Terpadu MTsN 1 Blitar</h3>
        </div>
        <p class="text-xs text-slate-500 max-w-md">Akses cepat seluruh platform pembelajaran mandiri, penilaian terpusat, dan administrasi online madrasah.</p>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <?php
        $layanan_items = function_exists( 'mtsn1_get_portal_layanan' ) ? mtsn1_get_portal_layanan() : [];
        $color_classes = [
            'emerald' => [
                'card'  => 'hover:border-emerald-500 hover:bg-emerald-50',
                'icon'  => 'bg-emerald-100 text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white',
                'title' => 'group-hover:text-emerald-800',
            ],
            'amber' => [
                'card'  => 'hover:border-amber-500 hover:bg-amber-50',
                'icon'  => 'bg-amber-100 text-amber-700 group-hover:bg-amber-500 group-hover:text-slate-950',
                'title' => 'group-hover:text-amber-800',
            ],
            'blue' => [
                'card'  => 'hover:border-blue-500 hover:bg-blue-50',
                'icon'  => 'bg-blue-100 text-blue-700 group-hover:bg-blue-600 group-hover:text-white',
                'title' => 'group-hover:text-blue-800',
            ],
            'purple' => [
                'card'  => 'hover:border-purple-500 hover:bg-purple-50',
                'icon'  => 'bg-purple-100 text-purple-700 group-hover:bg-purple-600 group-hover:text-white',
                'title' => 'group-hover:text-purple-800',
            ],
            'rose' => [
                'card'  => 'hover:border-rose-500 hover:bg-rose-50',
                'icon'  => 'bg-rose-100 text-rose-700 group-hover:bg-rose-600 group-hover:text-white',
                'title' => 'group-hover:text-rose-800',
            ],
            'teal' => [
                'card'  => 'hover:border-teal-500 hover:bg-teal-50',
                'icon'  => 'bg-teal-100 text-teal-700 group-hover:bg-teal-600 group-hover:text-white',
                'title' => 'group-hover:text-teal-800',
            ],
        ];

        foreach ( $layanan_items as $l_key => $item ) :
            if ( empty( $item['active'] ) ) continue;
            $color = $item['color'] ?? 'emerald';
            $st = $color_classes[ $color ] ?? $color_classes['emerald'];
            $url = ! empty( $item['url'] ) ? esc_url( $item['url'] ) : '#';
            $target = ( isset( $item['target'] ) && $item['target'] === '_blank' && $url !== '#' ) ? ' target="_blank" rel="noopener noreferrer"' : '';
            $icon = ! empty( $item['icon'] ) ? esc_attr( $item['icon'] ) : 'fa-solid fa-link';
        ?>
        <a href="<?php echo $url; ?>"<?php echo $target; ?> class="p-4 rounded-2xl bg-slate-50 border border-slate-200 <?php echo $st['card']; ?> hover:shadow-lg transition-all duration-300 text-center group">
          <div class="w-12 h-12 mx-auto rounded-2xl <?php echo $st['icon']; ?> flex items-center justify-center text-xl transition-colors shadow-sm">
            <i class="<?php echo $icon; ?>"></i>
          </div>
          <h4 class="mt-3 font-bold text-slate-900 text-xs <?php echo $st['title']; ?>"><?php echo esc_html( $item['title'] ); ?></h4>
          <p class="text-[10px] text-slate-500 mt-0.5"><?php echo esc_html( $item['desc'] ); ?></p>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- PAPAN INFORMASI RESMI & KALENDER AGENDA MADRASAH (NOTICE BOARD 50:50) -->
  <section id="informasi-madrasah" class="py-14 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Section Header -->
      <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 pb-4 border-b border-slate-100">
        <div>
          <span class="text-xs font-black uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">Pusat Informasi Kedinasan</span>
          <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">Papan Pengumuman & Kalender Agenda</h3>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">Surat edaran kedinasan resmi dan jadwal agenda akademik MTsN 1 Blitar.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Kolom Kiri (Col 6): Pengumuman Resmi & Surat Edaran -->
        <div id="pengumuman" class="lg:col-span-6 bg-slate-50 rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col justify-between h-full">
          <div>
            <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-200">
              <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-base font-bold shadow-sm">
                  <i class="fa-solid fa-thumbtack"></i>
                </div>
                <div>
                  <h4 class="text-base font-black text-slate-900">Pengumuman & Surat Edaran</h4>
                  <p class="text-[11px] text-slate-500">Pemberitahuan resmi madrasah</p>
                </div>
              </div>
              <a href="<?php echo esc_url( get_post_type_archive_link( 'pengumuman' ) ? get_post_type_archive_link( 'pengumuman' ) : home_url( '/pengumuman' ) ); ?>" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">
                <span>Semua</span> <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition"></i>
              </a>
            </div>

            <!-- List Pengumuman Items (4 items) -->
            <div class="space-y-3">
              <?php
              $papan_pengumuman_q = new WP_Query( array(
                  'post_type'      => 'pengumuman',
                  'posts_per_page' => 4,
                  'post_status'    => 'publish',
              ) );

              if ( $papan_pengumuman_q->have_posts() ) :
                  while ( $papan_pengumuman_q->have_posts() ) : $papan_pengumuman_q->the_post();
                      ?>
                      <a href="<?php the_permalink(); ?>" class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-500 hover:shadow-md transition group block">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                          <span class="px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800">
                            Edaran Resmi
                          </span>
                          <span class="text-[11px] text-slate-400 flex items-center gap-1 font-medium">
                            <i class="fa-regular fa-clock text-slate-400"></i> <?php echo get_the_date( 'd M Y' ); ?>
                          </span>
                        </div>
                        <h5 class="text-xs sm:text-sm font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug line-clamp-2">
                          <?php the_title(); ?>
                        </h5>
                      </a>
                      <?php
                  endwhile;
                  wp_reset_postdata();
              else :
                  ?>
                  <div class="p-4 rounded-2xl bg-white border border-slate-200">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Info Madrasah</span>
                    <h5 class="text-xs sm:text-sm font-bold text-slate-900 mt-1.5">Belum ada surat edaran terbaru yang diterbitkan.</h5>
                  </div>
                  <?php
              endif;
              ?>
            </div>
          </div>

          <div class="mt-6 pt-4 border-t border-slate-200">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'pengumuman' ) ? get_post_type_archive_link( 'pengumuman' ) : home_url( '/pengumuman' ) ); ?>" class="w-full py-2.5 rounded-xl bg-white hover:bg-emerald-600 hover:text-white border border-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2 shadow-sm">
              <i class="fa-solid fa-folder-open text-xs"></i> Lihat Semua Pengumuman & Surat Edaran &rarr;
            </a>
          </div>
        </div>

        <!-- Kolom Kanan (Col 6): Agenda Kegiatan & Kalender Akademik -->
        <div id="agenda" class="lg:col-span-6 bg-slate-50 rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col justify-between h-full">
          <div>
            <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-200">
              <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base font-bold shadow-sm">
                  <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                  <h4 class="text-base font-black text-slate-900">Agenda & Kalender Kegiatan</h4>
                  <p class="text-[11px] text-slate-500">Jadwal kegiatan akademik & siswa</p>
                </div>
              </div>
              <a href="<?php echo esc_url( get_post_type_archive_link( 'agenda' ) ? get_post_type_archive_link( 'agenda' ) : home_url( '/agenda' ) ); ?>" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group">
                <span>Semua</span> <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-0.5 transition"></i>
              </a>
            </div>

            <!-- List Agenda Items (4 items) -->
            <div class="space-y-3">
              <?php
              $today = date( 'Y-m-d' );
              $papan_agenda_q = new WP_Query( array(
                  'post_type'      => 'agenda',
                  'posts_per_page' => 4,
                  'post_status'    => 'publish',
                  'meta_key'       => '_tevent',
                  'orderby'        => 'meta_value',
                  'order'          => 'ASC',
                  'meta_query'     => array(
                      array(
                          'key'     => '_tevent',
                          'value'   => $today,
                          'compare' => '>=',
                          'type'    => 'DATE',
                      ),
                  ),
              ) );

              // Fallback if no upcoming events
              if ( ! $papan_agenda_q->have_posts() ) {
                  $papan_agenda_q = new WP_Query( array(
                      'post_type'      => 'agenda',
                      'posts_per_page' => 4,
                      'post_status'    => 'publish',
                      'meta_key'       => '_tevent',
                      'orderby'        => 'meta_value',
                      'order'          => 'ASC',
                  ) );
              }

              if ( $papan_agenda_q->have_posts() ) :
                  while ( $papan_agenda_q->have_posts() ) : $papan_agenda_q->the_post();
                      $tevent   = get_post_meta( get_the_ID(), '_tevent', true );
                      $tgl_teks = get_post_meta( get_the_ID(), '_tgl_teks', true );
                      $jam      = get_post_meta( get_the_ID(), '_jam', true );
                      $lokasi   = get_post_meta( get_the_ID(), '_lokasi', true );
                      $kategori = get_post_meta( get_the_ID(), '_kategori', true );

                      $month_str = $tevent ? date_i18n( 'M', strtotime( $tevent ) ) : 'Okt';
                      $day_str   = $tevent ? date_i18n( 'd', strtotime( $tevent ) ) : date( 'd' );
                      ?>
                      <a href="<?php the_permalink(); ?>" class="p-3 sm:p-3.5 rounded-2xl bg-white border border-slate-200 hover:border-emerald-500 hover:shadow-md transition group flex items-center gap-3.5 sm:gap-4">
                        <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-b from-emerald-600 to-teal-700 text-white flex flex-col items-center justify-center flex-shrink-0 shadow-md">
                          <span class="text-[10px] uppercase font-bold tracking-wider leading-none"><?php echo esc_html( $month_str ); ?></span>
                          <span class="text-base sm:text-lg font-black leading-none mt-1"><?php echo esc_html( $day_str ); ?></span>
                        </div>
                        <div class="min-w-0 flex-1">
                          <div class="flex items-center gap-2 mb-1">
                            <?php if ( $kategori ) : ?>
                              <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60"><?php echo esc_html( $kategori ); ?></span>
                            <?php endif; ?>
                            <?php if ( $tgl_teks && ( strpos( $tgl_teks, '–' ) !== false || strpos( $tgl_teks, '-' ) !== false || strpos( $tgl_teks, 'atau' ) !== false ) ) : ?>
                              <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200/60 line-clamp-1"><i class="fa-regular fa-calendar-days text-[9px] mr-1"></i><?php echo esc_html( $tgl_teks ); ?></span>
                            <?php endif; ?>
                          </div>
                          <h5 class="text-xs sm:text-sm font-black text-slate-900 group-hover:text-emerald-700 transition line-clamp-1 leading-snug">
                            <?php the_title(); ?>
                          </h5>
                          <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1.5 truncate">
                            <span class="text-emerald-600 font-semibold"><i class="fa-solid fa-location-dot"></i> <?php echo esc_html( $lokasi ? $lokasi : 'Madrasah' ); ?></span>
                            <span>•</span>
                            <span><i class="fa-regular fa-clock"></i> <?php echo esc_html( ( $jam && $jam !== '00:00' ) ? $jam . ' WIB' : 'Sesuai Jadwal' ); ?></span>
                          </p>
                        </div>
                      </a>
                      <?php
                  endwhile;
                  wp_reset_postdata();
              else :
                  ?>
                  <div class="p-4 rounded-2xl bg-white border border-slate-200">
                    <p class="text-xs text-slate-500">Belum ada agenda kegiatan mendatang.</p>
                  </div>
                  <?php
              endif;
              ?>
            </div>
          </div>

          <div class="mt-6 pt-4 border-t border-slate-200">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'agenda' ) ? get_post_type_archive_link( 'agenda' ) : home_url( '/agenda' ) ); ?>" class="w-full py-2.5 rounded-xl bg-white hover:bg-emerald-600 hover:text-white border border-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2 shadow-sm">
              <i class="fa-solid fa-calendar-days text-xs"></i> Buka Kalender Agenda Kegiatan &rarr;
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- DEWAN GURU & TENAGA KEPENDIDIKAN (GTK SHOWCASE) -->
  <section class="py-12 bg-white border-t border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
        <div>
          <span class="text-xs font-black uppercase text-emerald-700 tracking-wider">Pendidik Profesional</span>
          <h3 class="text-2xl font-black text-slate-900 mt-1">Direktori Guru & Tenaga Kependidikan</h3>
          <p class="text-xs text-slate-500 mt-1">Didukung oleh pendidik bersertifikasi dan tenaga kependidikan berdedikasi tinggi.</p>
        </div>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'gtk' ) ? get_post_type_archive_link( 'gtk' ) : home_url( '/gtk' ) ); ?>" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-xs font-bold text-slate-700 transition flex items-center gap-2 w-fit">
          Lihat Direktori GTK <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <?php
        $gtk_q = new WP_Query( array(
            'post_type'      => 'gtk',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        ) );

        if ( $gtk_q->have_posts() ) :
            while ( $gtk_q->have_posts() ) : $gtk_q->the_post();
                $thumb = get_the_post_thumbnail_url( get_the_ID(), 'mtsn1-avatar' );
                $nip   = get_post_meta( get_the_ID(), '_nip', true );
                ?>
                <a href="<?php the_permalink(); ?>" class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-200 hover:border-emerald-500 hover:shadow-md transition group block">
                  <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-slate-200 border-2 border-emerald-500 mb-3 shadow-inner group-hover:scale-105 transition">
                    <?php if ( $thumb ) : ?>
                      <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover">
                    <?php else : ?>
                      <div class="w-full h-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-user-tie"></i>
                      </div>
                    <?php endif; ?>
                  </div>
                  <h5 class="text-xs font-extrabold text-slate-900 group-hover:text-emerald-700 line-clamp-1">
                    <?php the_title(); ?>
                  </h5>
                  <p class="text-[10px] text-slate-500 font-medium mt-0.5 line-clamp-1"><?php echo esc_html( $nip ? 'NIP. ' . $nip : 'Tenaga Pendidik' ); ?></p>
                  <span class="mt-2 inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">Lihat Profil &rarr;</span>
                </a>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
      </div>
    </div>
  </section>

  <!-- INSPIRASI & OPINI MADRASAH (PORTAL AL-QALAM GTK) -->
  <section id="inspirasi-opini" class="py-14 bg-gradient-to-b from-slate-50 to-emerald-50/20 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Section Header -->
      <div class="pb-6 border-b border-slate-200 mb-8">
        <div class="flex items-center gap-2 mb-2">
          <span class="text-[11px] font-black uppercase text-emerald-800 tracking-wider bg-emerald-100/90 px-3.5 py-1 rounded-full inline-flex items-center gap-1.5 shadow-xs">
            <i class="fa-solid fa-pen-nib text-emerald-600"></i> AL QALAM • Pojok Literasi &amp; Pemikiran GTK
          </span>
          <span class="text-[10px] font-bold text-slate-500 bg-white px-2.5 py-1 rounded-full border border-slate-200 shadow-xs hidden sm:inline-block">
            Rubrik Resmi Pendidik
          </span>
        </div>
        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">Inspirasi &amp; Opini Madrasah</h3>
        <p class="text-xs sm:text-sm text-slate-600 mt-1">
          <strong class="font-black text-emerald-800">AL QALAM</strong> <span class="text-slate-400 font-semibold">(Aksi - Literasi - Qudwah - Amanah - Lintas - Aksara - Madrasah)</span> — Refleksi edukatif, esai pembelajaran, dan pemikiran berkeadaban Guru dan Tenaga Kependidikan (GTK) MTsN 1 Blitar.
        </p>
      </div>

      <?php
      $blog_q = new WP_Query( array(
          'post_type'      => 'blog',
          'posts_per_page' => 4,
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
      ) );

      if ( $blog_q->have_posts() ) :
          $posts_arr = $blog_q->posts;
          $featured_post = array_shift( $posts_arr );
          
          // Data Featured Post
          $feat_id = $featured_post->ID;
          $feat_terms = get_the_terms( $feat_id, 'blog-kat' );
          $feat_cat = ( $feat_terms && ! is_wp_error( $feat_terms ) ) ? $feat_terms[0]->name : 'Opini';
          $feat_words = str_word_count( strip_tags( get_the_content( null, false, $feat_id ) ) );
          $feat_reading = max( 1, ceil( $feat_words / 200 ) );
          $feat_author = function_exists( 'mtsn1_get_blog_author_data' ) ? mtsn1_get_blog_author_data( $feat_id ) : array( 'name' => get_the_author_meta( 'display_name', $featured_post->post_author ), 'role' => 'GTK MTsN 1 Blitar', 'photo' => '' );
          $feat_photo = ! empty( $feat_author['photo'] ) ? $feat_author['photo'] : home_url( '/wp-content/uploads/2025/08/WhatsApp-Image-2023-07-20-at-07.43.09-copy-768x1024.png' );
          ?>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 items-stretch">
            
            <!-- KOLOM KIRI (Col 7): Artikel Utama dengan Logo Al-Qalam di Bawah + Foto Kompak Berbingkai -->
            <div class="lg:col-span-7 bg-gradient-to-br from-white via-emerald-50/10 to-slate-50/40 rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
              
              <!-- Baris Atas Kartu: Logo Al-Qalam Digeser ke Sini + Kategori + Tanggal -->
              <div>
                <div class="flex flex-col md:flex-row md:items-center justify-between pb-4 mb-5 border-b border-slate-100 gap-3">
                  <div class="flex flex-col sm:flex-row sm:items-center gap-3.5">
                    <div class="p-2 px-3.5 bg-white rounded-2xl border border-slate-200/90 shadow-sm inline-flex shrink-0 w-fit">
                      <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/11/logo-al-qalam.webp' ) ); ?>" alt="Logo Al-Qalam" class="h-11 sm:h-12 w-auto object-contain">
                    </div>
                    <div>
                      <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs sm:text-sm font-black text-slate-900 tracking-tight">AL QALAM</span>
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 bg-emerald-100/90 px-2.5 py-0.5 rounded-lg inline-flex items-center gap-1">
                          <i class="fa-solid fa-feather-pointed text-emerald-600"></i> <?php echo esc_html( $feat_cat ); ?>
                        </span>
                      </div>
                      <p class="text-[10px] sm:text-[11px] font-bold text-emerald-800 tracking-tight">
                        Aksi - Literasi - Qudwah - Amanah - Lintas - Aksara - Madrasah
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 shrink-0">
                    <i class="fa-regular fa-calendar-days text-emerald-600"></i>
                    <span><?php echo get_the_date( 'd F Y', $feat_id ); ?></span>
                  </div>
                </div>

                <!-- Body Kartu: Foto Penulis Kompak di Samping Teks -->
                <div class="flex flex-col sm:flex-row gap-5 items-start">
                  
                  <!-- Foto Penulis Proporsional (~160px, berbingkai rapi) -->
                  <div class="w-full sm:w-40 shrink-0 flex flex-col items-center">
                    <div class="w-36 h-44 sm:w-40 sm:h-48 rounded-2xl overflow-hidden bg-slate-900 border-2 border-emerald-500/20 shadow-md relative group">
                      <img src="<?php echo esc_url( $feat_photo ); ?>" alt="<?php echo esc_attr( $feat_author['name'] ); ?>" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                      <div class="absolute bottom-2 inset-x-2 text-center bg-slate-950/80 backdrop-blur-sm rounded-lg py-1 px-1 text-white text-[9px] font-bold">
                        <i class="fa-regular fa-clock text-amber-400 mr-0.5"></i> <?php echo esc_html( $feat_reading ); ?> mnt baca
                      </div>
                    </div>
                    <div class="text-center mt-2.5 w-full">
                      <span class="text-xs font-black text-slate-900 block leading-tight truncate"><?php echo esc_html( $feat_author['name'] ); ?></span>
                      <span class="text-[10px] text-emerald-700 font-semibold block truncate"><?php echo esc_html( $feat_author['role'] ); ?></span>
                    </div>
                  </div>

                  <!-- Judul & Cuplikan Teks -->
                  <div class="flex-1 min-w-0">
                    <h4 class="text-base sm:text-lg font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug">
                      <a href="<?php echo esc_url( get_permalink( $feat_id ) ); ?>"><?php echo esc_html( get_the_title( $feat_id ) ); ?></a>
                    </h4>
                    <p class="text-xs text-slate-600 mt-2.5 leading-relaxed line-clamp-4">
                      <?php echo wp_trim_words( get_the_excerpt( $feat_id ), 30, '...' ); ?>
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                      <span class="text-[11px] text-slate-400 italic">Rubrik: <?php echo esc_html( $feat_cat ); ?></span>
                      <a href="<?php echo esc_url( get_permalink( $feat_id ) ); ?>" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white text-xs font-black transition shadow-xs">
                        <span>Baca Lengkap</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                      </a>
                    </div>
                  </div>

                </div>
              </div>

            </div>

            <!-- KOLOM KANAN (Col 5): List Tulisan Guru Terkini Lainnya -->
            <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-md flex flex-col justify-between h-full">
              
              <!-- Header List Kanan -->
              <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                  <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-sm">
                    <i class="fa-solid fa-book-open"></i>
                  </span>
                  <div>
                    <h4 class="text-sm font-black text-slate-900 leading-tight">Tulisan Guru Terbaru</h4>
                    <p class="text-[11px] text-slate-400">Praktik baik &amp; esai pedagogis GTK</p>
                  </div>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full">
                  Terbaru
                </span>
              </div>

              <!-- Daftar Artikel Ringkas -->
              <?php if ( ! empty( $posts_arr ) ) : ?>
                <div class="divide-y divide-slate-100 flex-1 flex flex-col justify-between py-2">
                  <?php foreach ( $posts_arr as $p_item ) : 
                      $p_id = $p_item->ID;
                      $p_terms = get_the_terms( $p_id, 'blog-kat' );
                      $p_cat_name = ( $p_terms && ! is_wp_error( $p_terms ) ) ? $p_terms[0]->name : 'Opini';
                      $p_cat_slug = ( $p_terms && ! is_wp_error( $p_terms ) ) ? $p_terms[0]->slug : '';
                      
                      // Style pill berdasarkan kategori
                      $pill_class = 'text-emerald-700 bg-emerald-50';
                      if ( strpos( $p_cat_slug, 'opini' ) !== false || strpos( $p_cat_slug, 'guru' ) !== false ) {
                          $pill_class = 'text-amber-800 bg-amber-50';
                      } elseif ( strpos( $p_cat_slug, 'karakter' ) !== false || strpos( $p_cat_slug, 'kepribadian' ) !== false ) {
                          $pill_class = 'text-blue-700 bg-blue-50';
                      }

                      $p_author = function_exists( 'mtsn1_get_blog_author_data' ) ? mtsn1_get_blog_author_data( $p_id ) : array( 'name' => get_the_author_meta( 'display_name', $p_item->post_author ), 'photo' => '' );
                      $p_photo = ! empty( $p_author['photo'] ) ? $p_author['photo'] : home_url( '/wp-content/uploads/2025/08/WhatsApp-Image-2023-07-20-at-07.43.09-copy-768x1024.png' );
                  ?>
                    <a href="<?php echo esc_url( get_permalink( $p_id ) ); ?>" class="py-3 flex items-center gap-3.5 group block hover:bg-slate-50/90 -mx-2 px-2.5 rounded-2xl transition duration-200">
                      <div class="w-12 h-14 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200 shadow-sm relative">
                        <img src="<?php echo esc_url( $p_photo ); ?>" alt="<?php echo esc_attr( $p_author['name'] ); ?>" class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-300">
                      </div>
                      <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-1">
                          <span class="text-[9px] font-black uppercase tracking-wider <?php echo esc_attr( $pill_class ); ?> px-2 py-0.5 rounded">
                            <?php echo esc_html( $p_cat_name ); ?>
                          </span>
                          <span class="text-[10px] text-slate-400">• <?php echo get_the_date( 'd M Y', $p_id ); ?></span>
                        </div>
                        <h5 class="text-xs sm:text-[13px] font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug line-clamp-2">
                          <?php echo esc_html( get_the_title( $p_id ) ); ?>
                        </h5>
                        <p class="text-[11px] font-medium text-slate-500 mt-1 truncate">
                          <i class="fa-solid fa-user-pen text-[9px] mr-1 text-slate-400"></i> <?php echo esc_html( $p_author['name'] ); ?>
                        </p>
                      </div>
                      <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition shrink-0"></i>
                    </a>
                  <?php endforeach; ?>
                </div>
              <?php else : ?>
                <!-- Fallback jika belum ada artikel tambahan -->
                <div class="py-6 text-center text-slate-400 text-xs">
                  <i class="fa-solid fa-feather-pointed text-2xl mb-2 text-emerald-500"></i>
                  <p>Artikel guru lainnya akan segera dipublikasikan di rubrik Al-Qalam.</p>
                </div>
              <?php endif; ?>

              <!-- Tombol Arsip Seluruh Tulisan GTK -->
              <a href="<?php echo esc_url( get_post_type_archive_link( 'blog' ) ? get_post_type_archive_link( 'blog' ) : home_url( '/blog' ) ); ?>" class="mt-3 w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-300 hover:to-yellow-300 text-slate-950 font-black text-xs text-center transition flex items-center justify-center gap-2 shadow-sm border border-amber-300/80">
                <span>Jelajahi Seluruh Karya Tulis GTK</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
              </a>

            </div>

          </div>
          <?php
          wp_reset_postdata();
      else :
          ?>
          <div class="bg-white rounded-3xl p-8 border border-slate-200 text-center max-w-lg mx-auto">
            <i class="fa-solid fa-pen-nib text-3xl text-emerald-600 mb-2"></i>
            <h4 class="text-base font-bold text-slate-800">Belum Ada Artikel yang Dipublikasikan</h4>
            <p class="text-xs text-slate-500 mt-1">Artikel pemikiran dan opini GTK akan segera hadir di sini.</p>
          </div>
          <?php
      endif;
      ?>
    </div>
  </section>

  <!-- MULTIMEDIA SHOWCASE: GALERI FOTO KEGIATAN & VIDEO RESMI YOUTUBE -->
  <section id="galeri" class="py-14 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-800">
        <div>
          <span class="text-xs font-bold text-gold-400 uppercase tracking-widest">Dokumentasi Visual</span>
          <h3 class="text-2xl font-black text-white mt-1">Galeri Foto & Video Madrasah</h3>
        </div>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'galeri' ) ? get_post_type_archive_link( 'galeri' ) : '#' ); ?>" class="text-xs font-bold text-emerald-400 hover:text-emerald-300">Semua Dokumentasi &rarr;</a>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Video Player Highlight (Col 7) -->
        <?php
        $video_query = new WP_Query( array(
            'post_type'      => 'video',
            'posts_per_page' => 5,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        $videos_list = array();
        if ( $video_query->have_posts() ) {
            while ( $video_query->have_posts() ) {
                $video_query->the_post();
                $v_id = get_the_ID();
                $v_embed = get_post_meta( $v_id, 'video_embed', true );
                $vid_raw = get_post_meta( $v_id, '_vidembed', true );
                $emb_raw = get_post_meta( $v_id, '_embed', true );
                $yt_id = function_exists( 'mtsn1_extract_youtube_id' ) ? mtsn1_extract_youtube_id( $v_embed ) : '';
                if ( ! $yt_id && function_exists( 'mtsn1_extract_youtube_id' ) ) {
                    $yt_id = mtsn1_extract_youtube_id( $vid_raw );
                }
                if ( ! $yt_id && function_exists( 'mtsn1_extract_youtube_id' ) ) {
                    $yt_id = mtsn1_extract_youtube_id( $emb_raw );
                }
                if ( $yt_id && $yt_id !== 'N3_85NxwrNk' ) {
                    $videos_list[] = array(
                        'id'    => $v_id,
                        'title' => get_the_title(),
                        'date'  => get_the_date( 'd M Y' ),
                        'yt_id' => $yt_id,
                    );
                }
            }
            wp_reset_postdata();
        }

        $active_video = ! empty( $videos_list ) ? $videos_list[0] : array(
            'title' => 'INAGURASI MATSAMA MTsN 1 Blitar',
            'yt_id' => 'xa3ya_v01uM',
            'date'  => date( 'd M Y' ),
        );
        ?>
        <div class="lg:col-span-7 bg-slate-950 rounded-3xl p-5 border border-slate-800 shadow-xl flex flex-col justify-between">
          <div>
            <div class="relative overflow-hidden rounded-2xl aspect-video bg-black shadow-inner">
              <iframe id="mainVideoPlayer" class="w-full h-full" src="https://www.youtube.com/embed/<?php echo esc_attr( $active_video['yt_id'] ); ?>?rel=0" title="<?php echo esc_attr( $active_video['title'] ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>

            <!-- Video Info & Subscribe -->
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <div class="flex items-center gap-2">
                  <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                  <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">Saluran Resmi YouTube MTsN 1 Blitar</span>
                </div>
                <h4 id="mainVideoTitle" class="text-sm sm:text-base font-bold text-white mt-0.5 leading-snug"><?php echo esc_html( $active_video['title'] ); ?></h4>
              </div>
              <a href="https://www.youtube.com/@mtsn1blitar610?sub_confirmation=1" target="_blank" rel="noopener noreferrer" class="px-3.5 py-1.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold inline-flex items-center gap-1.5 transition self-start sm:self-center shrink-0 shadow-md">
                <i class="fa-brands fa-youtube text-sm"></i> Subscribe
              </a>
            </div>
          </div>

          <?php if ( count( $videos_list ) > 1 ) : ?>
          <!-- Video Selector List -->
          <div class="mt-4 pt-3 border-t border-slate-800/80">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2"><i class="fa-solid fa-film text-gold-400 mr-1"></i> Pilih Video Dokumentasi Lainnya:</span>
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
              <?php foreach ( $videos_list as $idx => $v_item ) : ?>
                <button type="button" onclick="changeMainVideo('<?php echo esc_js( $v_item['yt_id'] ); ?>', '<?php echo esc_js( addslashes( $v_item['title'] ) ); ?>', this)" class="video-pill text-left px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition border flex items-center gap-2 shrink-0 <?php echo $idx === 0 ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-slate-900 text-slate-300 border-slate-800 hover:bg-slate-800 hover:text-white'; ?>">
                  <i class="fa-solid fa-play text-[10px] text-gold-400"></i>
                  <span class="truncate max-w-[160px] sm:max-w-[200px]"><?php echo esc_html( $v_item['title'] ); ?></span>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
          <script>
          function changeMainVideo(ytId, title, btn) {
            var player = document.getElementById('mainVideoPlayer');
            var titleEl = document.getElementById('mainVideoTitle');
            if (player) {
              player.src = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1&rel=0';
            }
            if (titleEl) {
              titleEl.textContent = title;
            }
            document.querySelectorAll('.video-pill').forEach(function(el) {
              el.className = 'video-pill text-left px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition border flex items-center gap-2 shrink-0 bg-slate-900 text-slate-300 border-slate-800 hover:bg-slate-800 hover:text-white';
            });
            if (btn) {
              btn.className = 'video-pill text-left px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition border flex items-center gap-2 shrink-0 bg-emerald-600 text-white border-emerald-500';
            }
          }
          </script>
          <?php endif; ?>
        </div>

        <!-- Galeri Foto Grid (Col 5) -->
        <div class="lg:col-span-5 grid grid-cols-2 gap-3.5">
          <?php
          $galeri_q = new WP_Query( array(
              'post_type'      => 'galeri',
              'posts_per_page' => 4,
              'post_status'    => 'publish',
          ) );

          if ( $galeri_q->have_posts() ) :
              while ( $galeri_q->have_posts() ) : $galeri_q->the_post();
                  $thumb = get_the_post_thumbnail_url( get_the_ID(), 'mtsn1-card' );
                  ?>
                  <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-slate-800 relative group cursor-pointer border border-slate-800">
                    <?php if ( $thumb ) : ?>
                      <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <?php else : ?>
                      <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/santri.jpg' ) ); ?>" alt="Foto" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="absolute inset-0 bg-slate-950/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                      <i class="fa-solid fa-expand text-white text-xl"></i>
                    </a>
                  </div>
                  <?php
              endwhile;
              wp_reset_postdata();
          else :
              ?>
              <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-slate-800"><img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/santri.jpg' ) ); ?>" class="w-full h-full object-cover"></div>
              <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-slate-800"><img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/DSC03282-scaled.jpg' ) ); ?>" class="w-full h-full object-cover"></div>
              <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-slate-800"><img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/WhatsApp-Image-2026-10-17-at-08.31.54.jpeg' ) ); ?>" class="w-full h-full object-cover"></div>
              <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-slate-800"><img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/DSC_0151-scaled-e1791690209861.jpg' ) ); ?>" class="w-full h-full object-cover"></div>
              <?php
          endif;
          ?>
        </div>
      </div>
    </div>
  </section>

  <!-- JAVASCRIPT SLIDER INTERAKTIF -->
  <script>
    const slides = <?php echo json_encode( $slides_data ); ?>;
    let currentSlide = 0;
    const imgEl = document.getElementById('mainSliderImg');
    const titleEl = document.getElementById('mainSliderTitle');
    const descEl = document.getElementById('mainSliderDesc');

    function updateSlide() {
      if (!imgEl || !slides.length) return;
      imgEl.style.opacity = '0.3';
      setTimeout(() => {
        imgEl.src = slides[currentSlide].img;
        titleEl.textContent = slides[currentSlide].title;
        descEl.textContent = slides[currentSlide].desc;
        imgEl.style.opacity = '1';

        for (let i = 0; i < slides.length; i++) {
          const dot = document.getElementById('dot' + i);
          if (dot) {
            if (i === currentSlide) {
              dot.className = 'w-7 h-2 rounded-full bg-emerald-500 transition-all';
            } else {
              dot.className = 'w-2 h-2 rounded-full bg-white/40 transition-all';
            }
          }
        }
      }, 200);
    }

    function nextSlide() {
      if (!slides.length) return;
      currentSlide = (currentSlide + 1) % slides.length;
      updateSlide();
    }

    function prevSlide() {
      if (!slides.length) return;
      currentSlide = (currentSlide - 1 + slides.length) % slides.length;
      updateSlide();
    }

    // Auto rotate every 6 seconds
    setInterval(nextSlide, 6000);
  </script>

<?php get_footer(); ?>
