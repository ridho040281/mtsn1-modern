<?php
/**
 * Main template file (fallback)
 */
if ( is_front_page() ) {
    include get_template_directory() . '/front-page.php';
    exit;
}

get_header();
?>

<div class="py-12 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">Kabar Madrasah</span>
      <h1 class="text-3xl font-black text-slate-900 mt-2">Warta & Publikasi</h1>
    </div>

    <?php if ( have_posts() ) : ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ( have_posts() ) : the_post(); ?>
          <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200 hover:shadow-xl transition flex flex-col justify-between">
            <div>
              <div class="overflow-hidden rounded-2xl aspect-[16/10] bg-slate-100 mb-4">
                <?php if ( has_post_thumbnail() ) : ?>
                  <?php the_post_thumbnail( 'mtsn1-card', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                <?php else : ?>
                  <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/santri.jpg' ) ); ?>" class="w-full h-full object-cover">
                <?php endif; ?>
              </div>
              <h2 class="text-base font-bold text-slate-900 line-clamp-2">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
              </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between text-xs text-slate-400">
              <span><?php echo get_the_date( 'd M Y' ); ?></span>
              <a href="<?php the_permalink(); ?>" class="text-emerald-700 font-bold">Baca &rarr;</a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      <?php mtsn1_pagination(); ?>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>
