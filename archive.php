<?php
/**
 * The template for displaying archive pages (Category, Tag, Post Type Archives)
 */
get_header();
?>

<div class="py-12 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Archive Header -->
    <div class="mb-10 text-center max-w-2xl mx-auto">
      <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Arsip Publikasi</span>
      <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3">
        <?php the_archive_title(); ?>
      </h1>
      <?php if ( get_the_archive_description() ) : ?>
        <p class="text-sm text-slate-600 mt-2"><?php echo get_the_archive_description(); ?></p>
      <?php endif; ?>
    </div>

    <!-- Grid Cards -->
    <?php if ( have_posts() ) : ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ( have_posts() ) : the_post();
            $is_prestasi = ( get_post_type() === 'prestasi' || is_post_type_archive( 'prestasi' ) );
            $aspect_ratio = $is_prestasi ? 'aspect-[4/5]' : 'aspect-[16/10]';
            $peraih = $is_prestasi ? get_post_meta( get_the_ID(), '_peraih', true ) : '';
        ?>
          <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
            <div>
              <div class="overflow-hidden rounded-2xl <?php echo esc_attr( $aspect_ratio ); ?> bg-slate-950 mb-4 relative flex items-center justify-center">
                <?php if ( has_post_thumbnail() ) : ?>
                  <?php if ( $is_prestasi ) : ?>
                    <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>" class="absolute inset-0 w-full h-full object-cover blur-xl opacity-40 scale-110 pointer-events-none" aria-hidden="true">
                    <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>" alt="<?php the_title(); ?>" class="relative z-10 w-full h-full object-contain p-0.5 group-hover:scale-102 transition duration-500">
                  <?php else : ?>
                    <?php the_post_thumbnail( 'mtsn1-card', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition duration-500' ) ); ?>
                  <?php endif; ?>
                <?php else : ?>
                  <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/santri.jpg' ) ); ?>" class="w-full h-full object-cover">
                <?php endif; ?>

                <?php if ( $is_prestasi ) : ?>
                  <div class="absolute top-2.5 right-2.5">
                    <span class="w-7 h-7 rounded-full bg-slate-950/70 backdrop-blur-md text-pink-400 flex items-center justify-center text-xs shadow-md">
                      <i class="fa-brands fa-instagram"></i>
                    </span>
                  </div>
                <?php endif; ?>
              </div>

              <?php if ( $peraih ) : ?>
                <div class="mb-2 p-2 rounded-xl bg-amber-50 border border-amber-200/80 text-[11px] font-black text-amber-900 truncate">
                  👤 <?php echo esc_html( $peraih ); ?>
                </div>
              <?php endif; ?>
              <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                <span><i class="fa-regular fa-calendar text-emerald-600 mr-1"></i> <?php echo get_the_date( 'd M Y' ); ?></span>
                <span>•</span>
                <span><?php echo esc_html( mtsn1_reading_time() ); ?></span>
              </div>
              <h2 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition leading-snug line-clamp-2">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
              </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
              <a href="<?php the_permalink(); ?>" class="font-bold text-emerald-700 group-hover:underline">Baca Selengkapnya &rarr;</a>
              <span class="text-slate-400"><?php the_author(); ?></span>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <!-- Numeric Pagination -->
      <?php mtsn1_pagination(); ?>

    <?php else : ?>
      <div class="text-center py-16 bg-white rounded-3xl border border-slate-200">
        <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-4">
          <i class="fa-regular fa-folder-open"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Belum ada artikel pada kategori ini</h3>
        <p class="text-xs text-slate-500 mt-1">Silakan kembali ke halaman utama untuk melihat artikel terbaru.</p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mt-4 inline-block px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition">
          Ke Halaman Utama
        </a>
      </div>
    <?php endif; ?>

  </div>
</div>

<?php get_footer(); ?>
