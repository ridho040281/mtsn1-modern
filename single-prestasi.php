<?php
/**
 * The template for displaying single Prestasi (Student Achievement Flyer)
 */
get_header();
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <?php while ( have_posts() ) : the_post();
        $id = get_the_ID();
        $peraih = get_post_meta( $id, '_peraih', true );
        $thumb = get_the_post_thumbnail_url( $id, 'full' );
        $ig_url = get_post_meta( $id, '_instagram_url', true );
    ?>

      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
        <span>/</span>
        <a href="<?php echo esc_url( get_post_type_archive_link( 'prestasi' ) ? get_post_type_archive_link( 'prestasi' ) : home_url( '/prestasi' ) ); ?>" class="text-amber-700 font-semibold hover:underline">Prestasi & Kejuaraan</a>
        <span>/</span>
        <span class="line-clamp-1 max-w-xs text-slate-400"><?php the_title(); ?></span>
      </nav>

      <!-- Main Prestasi Showcase Card -->
      <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

          <!-- Left Column: Instagram Flyer Display (Full Uncut Flyer) -->
          <div class="lg:col-span-6 flex flex-col items-center">
            <div class="w-full max-w-md rounded-3xl overflow-hidden bg-slate-950 shadow-2xl border-4 border-amber-400/40 relative group flex items-center justify-center">
              <?php if ( $thumb ) : ?>
                <!-- Ambient Backdrop Blur -->
                <img src="<?php echo esc_url( $thumb ); ?>" class="absolute inset-0 w-full h-full object-cover blur-2xl opacity-40 scale-125 pointer-events-none" aria-hidden="true">
                <!-- Main Flyer: Natural Full Ratio (Zero Crop) -->
                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title(); ?>" class="relative z-10 w-full h-auto max-h-[750px] object-contain block mx-auto group-hover:scale-102 transition-transform duration-500">
              <?php else : ?>
                <div class="w-full aspect-[4/5] bg-gradient-to-b from-amber-600 to-amber-900 flex flex-col items-center justify-center text-white p-8 text-center relative z-10">
                  <div class="w-24 h-24 rounded-full bg-white/10 flex items-center justify-center text-5xl mb-4">
                    <i class="fa-solid fa-trophy text-amber-300"></i>
                  </div>
                  <h3 class="text-xl font-black"><?php the_title(); ?></h3>
                </div>
              <?php endif; ?>

              <!-- Badge Overlay -->
              <div class="absolute top-4 left-4 z-20">
                <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-slate-950/80 backdrop-blur-md text-amber-400 border border-amber-400/50 shadow-lg flex items-center gap-2">
                  <i class="fa-solid fa-trophy text-amber-400"></i>
                  <span>Prestasi Juara</span>
                </span>
              </div>

              <!-- Tingkat Kejuaraan Badge in Top Right -->
              <div class="absolute top-4 right-4 z-20">
                <?php echo mtsn1_render_tingkat_badge( mtsn1_get_tingkat_prestasi( $id ), 'text-xs px-3.5 py-1.5' ); ?>
              </div>
            </div>

            <!-- Download / Open Fullsize / Instagram Buttons -->
            <div class="mt-4 flex flex-col sm:flex-row items-center gap-3 w-full max-w-md">
              <?php if ( $thumb ) : ?>
                <a href="<?php echo esc_url( $thumb ); ?>" target="_blank" class="w-full sm:flex-1 py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold text-center flex items-center justify-center gap-2 transition">
                  <i class="fa-solid fa-expand"></i> Buka Flyer
                </a>
              <?php endif; ?>
              <?php if ( $ig_url ) : ?>
                <a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" rel="noopener noreferrer" class="w-full sm:flex-1 py-2.5 px-4 rounded-xl bg-gradient-to-r from-pink-500 via-rose-500 to-amber-500 hover:opacity-90 text-white text-xs font-bold text-center flex items-center justify-center gap-1.5 transition shadow-sm">
                  <i class="fa-brands fa-instagram"></i> Cek di IG
                </a>
              <?php endif; ?>
              <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' - MTsN 1 Blitar: ' . get_permalink() ); ?>" target="_blank" class="w-full sm:flex-1 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold text-center flex items-center justify-center gap-1.5 transition shadow-sm">
                <i class="fa-brands fa-whatsapp"></i> Bagikan
              </a>
            </div>
          </div>

          <!-- Right Column: Details & Student Peraih -->
          <div class="lg:col-span-6 space-y-6">
            <div>
              <div class="flex items-center gap-2 mb-2 flex-wrap">
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800">
                  Hall of Fame
                </span>
                <?php echo mtsn1_render_tingkat_badge( mtsn1_get_tingkat_prestasi( $id ) ); ?>
                <span class="text-xs text-slate-400">
                  <i class="fa-regular fa-calendar text-amber-600 mr-1"></i> <?php echo get_the_date( 'd F Y' ); ?>
                </span>
              </div>

              <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                <?php the_title(); ?>
              </h1>
              <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">
                Madrasah Tsanawiyah Negeri 1 Blitar
              </p>
            </div>

            <!-- Peraih Prestasi Highlight Card -->
            <?php if ( $peraih ) : ?>
              <div class="p-5 rounded-2xl bg-gradient-to-br from-amber-50 to-yellow-50 border-2 border-amber-300 shadow-sm">
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 block mb-1 flex items-center gap-1.5">
                  <i class="fa-solid fa-medal text-amber-600"></i> Siswa / Tim Peraih Prestasi:
                </span>
                <h3 class="text-base sm:text-lg font-black text-slate-900">
                  <?php echo esc_html( $peraih ); ?>
                </h3>
                <p class="text-[11px] text-amber-900/80 mt-1">Selamat dan sukses atas raihan kejuaraan yang membanggakan madrasah!</p>
              </div>
            <?php endif; ?>

            <!-- Article Body / Content -->
            <div class="prose max-w-none text-slate-700 text-sm leading-relaxed border-t border-b border-slate-100 py-6">
              <?php
              if ( get_the_content() ) {
                  the_content();
              } else {
                  echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
              }
              ?>
            </div>

            <!-- Back to Archive -->
            <div class="pt-4 flex items-center justify-between">
              <a href="<?php echo esc_url( get_post_type_archive_link( 'prestasi' ) ? get_post_type_archive_link( 'prestasi' ) : home_url( '/prestasi' ) ); ?>" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-amber-500 hover:text-white text-slate-700 text-xs font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Galeri Prestasi
              </a>
              <span class="text-xs text-slate-400">MTsN 1 Blitar</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Other Achievements Grid (Instagram Flyer Ratio 4:5) -->
      <div class="mt-12">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
            <span class="w-2 h-5 rounded-full bg-amber-500"></span> Prestasi Lainnya
          </h3>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'prestasi' ) ? get_post_type_archive_link( 'prestasi' ) : home_url( '/prestasi' ) ); ?>" class="text-xs font-bold text-amber-700 hover:underline">Semua Prestasi &rarr;</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <?php
          $other_q = new WP_Query( array(
              'post_type'      => 'prestasi',
              'posts_per_page' => 4,
              'post__not_in'   => array( $id ),
              'post_status'    => 'publish',
          ) );

          if ( $other_q->have_posts() ) :
              while ( $other_q->have_posts() ) : $other_q->the_post();
                  $o_thumb = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                  ?>
                  <a href="<?php the_permalink(); ?>" class="bg-white rounded-2xl p-3 border border-slate-200 hover:border-amber-400 hover:shadow-lg transition group block">
                    <div class="aspect-[4/5] rounded-xl overflow-hidden bg-slate-950 mb-2 relative flex items-center justify-center">
                      <?php if ( $o_thumb ) : ?>
                        <img src="<?php echo esc_url( $o_thumb ); ?>" class="absolute inset-0 w-full h-full object-cover blur-md opacity-35 scale-110 pointer-events-none" aria-hidden="true">
                        <img src="<?php echo esc_url( $o_thumb ); ?>" alt="<?php the_title(); ?>" class="relative z-10 w-full h-full object-contain group-hover:scale-105 transition duration-500">
                      <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center bg-amber-500 text-white relative z-10">
                          <i class="fa-solid fa-trophy text-2xl"></i>
                        </div>
                      <?php endif; ?>

                      <!-- Tingkat Kejuaraan Badge -->
                      <div class="absolute top-2 right-2 z-20">
                        <?php echo mtsn1_render_tingkat_badge( mtsn1_get_tingkat_prestasi( get_the_ID() ) ); ?>
                      </div>
                    </div>
                    <h5 class="text-xs font-black text-slate-900 group-hover:text-amber-700 line-clamp-2 leading-snug"><?php the_title(); ?></h5>
                  </a>
                  <?php
              endwhile;
              wp_reset_postdata();
          endif;
          ?>
        </div>
      </div>

    <?php endwhile; ?>

  </div>
</div>

<?php get_footer(); ?>
