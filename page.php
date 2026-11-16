<?php
/**
 * The template for displaying all pages (Profil, Sambutan, Visi Misi, etc.)
 */
get_header();
?>

<div class="py-12 bg-slate-100 min-h-screen">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <?php while ( have_posts() ) : the_post(); ?>
      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
        <span>/</span>
        <span class="text-emerald-700 font-semibold"><?php the_title(); ?></span>
      </nav>

      <article class="bg-white rounded-3xl p-6 sm:p-12 shadow-sm border border-slate-200">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight pb-6 border-b border-slate-100 mb-8">
          <?php the_title(); ?>
        </h1>

        <?php if ( has_post_thumbnail() ) : ?>
          <div class="rounded-2xl overflow-hidden mb-8 shadow-md">
            <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto object-cover' ) ); ?>
          </div>
        <?php endif; ?>

        <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>

  </div>
</div>

<?php get_footer(); ?>
