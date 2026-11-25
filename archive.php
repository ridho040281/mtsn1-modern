<?php
/**
 * The template for displaying archive pages (Category, Tag, Post Type Archives)
 */
get_header();
?>

<div class="py-12 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Archive Header -->
    <?php
    $is_blog_tax = is_tax( 'blog-kat' ) || is_post_type_archive( 'blog' );
    $current_term_id = is_tax( 'blog-kat' ) ? get_queried_object_id() : 0;
    ?>
    <div class="mb-8 text-center max-w-2xl mx-auto">
      <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full">
        <?php echo $is_blog_tax ? 'Portal Literasi Al-Qalam' : 'Arsip Publikasi'; ?>
      </span>
      <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3">
        <?php the_archive_title(); ?>
      </h1>
      <?php if ( get_the_archive_description() ) : ?>
        <p class="text-sm text-slate-600 mt-2"><?php echo get_the_archive_description(); ?></p>
      <?php endif; ?>
    </div>

    <?php if ( $is_blog_tax ) :
      $blog_categories = get_terms( array(
          'taxonomy'   => 'blog-kat',
          'hide_empty' => true,
      ) );
    ?>
      <!-- Navigasi Filter Kategori Blog Guru -->
      <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
        <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm <?php echo ( ! $current_term_id ) ? 'bg-emerald-600 text-white' : 'bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200'; ?>">
          Semua Rubrik
        </a>
        <?php if ( ! empty( $blog_categories ) && ! is_wp_error( $blog_categories ) ) : ?>
          <?php foreach ( $blog_categories as $b_cat_item ) :
            $is_active_cat = ( intval( $current_term_id ) === intval( $b_cat_item->term_id ) );
          ?>
            <a href="<?php echo esc_url( get_term_link( $b_cat_item ) ); ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm <?php echo $is_active_cat ? 'bg-emerald-600 text-white' : 'bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200'; ?>">
              <?php echo esc_html( $b_cat_item->name ); ?> (<?php echo esc_html( $b_cat_item->count ); ?>)
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Grid Cards -->
    <?php if ( have_posts() ) : ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ( have_posts() ) : the_post();
            $is_prestasi = ( get_post_type() === 'prestasi' || is_post_type_archive( 'prestasi' ) );
            $is_blog     = ( get_post_type() === 'blog' || is_tax( 'blog-kat' ) );
            $aspect_ratio = $is_prestasi ? 'aspect-[4/5]' : 'aspect-[16/10]';
            $peraih = $is_prestasi ? get_post_meta( get_the_ID(), '_peraih', true ) : '';

            if ( $is_blog ) {
                $author_data  = function_exists( 'mtsn1_get_blog_author_data' ) ? mtsn1_get_blog_author_data( get_the_ID() ) : array( 'name' => get_the_author(), 'role' => 'GTK MTsN 1 Blitar', 'photo' => '' );
                $author_photo = ! empty( $author_data['photo'] ) ? $author_data['photo'] : home_url( '/wp-content/uploads/2025/08/WhatsApp-Image-2023-07-20-at-07.43.09-copy-768x1024.png' );
                $author_name  = $author_data['name'];
                $author_role  = $author_data['role'];
            }
        ?>
          <div class="bg-white rounded-3xl <?php echo $is_blog ? 'p-3 sm:p-4 pt-4 sm:pt-5' : 'p-5'; ?> shadow-sm border border-slate-200 hover:shadow-xl transition-all duration-300 group flex flex-col justify-between relative">
            <div>
              <?php if ( $is_prestasi ) : ?>
                <div class="mb-3 flex items-center justify-between gap-2 px-0.5">
                  <?php echo mtsn1_render_peringkat_badge( get_the_ID() ); ?>
                  <div class="shrink-0">
                    <?php echo mtsn1_render_tingkat_badge( mtsn1_get_tingkat_prestasi( get_the_ID() ) ); ?>
                  </div>
                </div>
              <?php endif; ?>

              <div class="relative mb-4 <?php echo $is_blog ? 'mt-2 sm:mt-2.5' : 'mt-1.5 sm:mt-2'; ?>">
                <?php if ( $is_blog ) : 
                  $b_terms = get_the_terms( get_the_ID(), 'blog-kat' );
                  $b_cat_name = ( $b_terms && ! is_wp_error( $b_terms ) ) ? $b_terms[0]->name : 'Opini';
                  $b_cat_slug = ( $b_terms && ! is_wp_error( $b_terms ) ) ? $b_terms[0]->slug : '';

                  $badge_bg   = 'bg-emerald-600';
                  $icon_bg    = 'bg-amber-400 text-slate-900';
                  $badge_icon = 'fa-feather-pointed';

                  if ( strpos( $b_cat_slug, 'sekolah' ) !== false ) {
                      $badge_bg   = 'bg-amber-600';
                      $badge_icon = 'fa-school';
                  } elseif ( strpos( $b_cat_slug, 'karakter' ) !== false || strpos( $b_cat_slug, 'siswa' ) !== false ) {
                      $badge_bg   = 'bg-blue-600';
                      $badge_icon = 'fa-user-graduate';
                  }
                ?>
                  <!-- Floating Badge Kategori (Pojok Kanan Atas Melayang) -->
                  <div class="blog-floating-badge" style="top: -13px; right: 12px;">
                    <div class="inline-flex items-stretch rounded-full overflow-hidden text-[9px] sm:text-[10px] font-black tracking-wider uppercase border border-black/10 shadow-sm">
                      <span class="<?php echo esc_attr( $icon_bg ); ?> px-2 sm:px-2.5 py-0.5 sm:py-1 flex items-center justify-center font-black">
                        <i class="fa-solid <?php echo esc_attr( $badge_icon ); ?>"></i>
                      </span>
                      <span class="<?php echo esc_attr( $badge_bg ); ?> text-white px-2.5 sm:px-3 py-0.5 sm:py-1 flex items-center">
                        <?php echo esc_html( $b_cat_name ); ?>
                      </span>
                    </div>
                  </div>
                <?php endif; ?>

                <?php
                $thumb_size = ( $is_prestasi || $is_blog ) ? 'full' : 'mtsn1-card';
                $card_thumb = mtsn1_get_post_thumbnail_url( get_the_ID(), $thumb_size );
                ?>
                <?php if ( $is_blog ) : ?>
                  <!-- Banner Blog Utuh 100% Tanpa Terpotong (Zero Crop) -->
                  <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-2xl bg-slate-50 border border-slate-100/80 shadow-xs">
                    <img src="<?php echo esc_url( $card_thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-auto object-contain block group-hover:scale-102 transition-transform duration-500">
                  </a>
                <?php else : ?>
                  <div class="overflow-hidden rounded-2xl <?php echo esc_attr( $aspect_ratio ); ?> bg-slate-950 relative flex items-center justify-center">
                    <?php if ( $is_prestasi ) : ?>
                      <img src="<?php echo esc_url( $card_thumb ); ?>" class="absolute inset-0 w-full h-full object-cover blur-xl opacity-40 scale-110 pointer-events-none" aria-hidden="true">
                      <img src="<?php echo esc_url( $card_thumb ); ?>" alt="<?php the_title(); ?>" class="relative z-10 w-full h-full object-contain p-0.5 group-hover:scale-102 transition duration-500">
                    <?php else : ?>
                      <img src="<?php echo esc_url( $card_thumb ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>

              <div class="<?php echo $is_blog ? 'px-2 sm:px-3' : ''; ?>">
                <?php if ( $peraih ) : ?>
                  <div class="mb-2 p-2 rounded-xl bg-amber-50 border border-amber-200/80 text-[11px] font-black text-amber-900 truncate">
                    👤 <?php echo esc_html( $peraih ); ?>
                  </div>
                <?php endif; ?>
                <div class="flex items-center justify-between gap-2 text-xs text-slate-400 mb-2">
                  <span><i class="fa-regular fa-calendar-days text-emerald-600 mr-1"></i> <?php echo get_the_date( 'd M Y' ); ?></span>
                  <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold text-[10px]">
                    <i class="fa-regular fa-clock text-emerald-600 mr-0.5"></i> <?php echo esc_html( mtsn1_reading_time() ); ?>
                  </span>
                </div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug line-clamp-2">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <p class="text-xs text-slate-600 mt-2.5 line-clamp-3 leading-relaxed">
                  <?php echo wp_trim_words( get_the_excerpt(), 22, '...' ); ?>
                </p>
              </div>
            </div>

            <?php if ( $is_blog ) : ?>
              <div class="px-2 pb-1 sm:px-3 sm:pb-2 pt-4 mt-5 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0">
                  <div class="w-8 h-8 rounded-full overflow-hidden bg-emerald-100 border border-emerald-300 shadow-sm shrink-0">
                    <img src="<?php echo esc_url( $author_photo ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="w-full h-full object-cover object-top">
                  </div>
                  <div class="leading-tight truncate">
                    <span class="text-xs font-bold text-slate-900 block truncate"><?php echo esc_html( $author_name ); ?></span>
                    <span class="text-[10px] text-slate-400 block truncate"><?php echo esc_html( $author_role ); ?></span>
                  </div>
                </div>
                <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-50 group-hover:bg-emerald-600 text-emerald-700 group-hover:text-white text-xs font-black transition shrink-0 ml-2 shadow-xs">
                  <span>Baca</span>
                  <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
              </div>
            <?php else : ?>
              <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="<?php the_permalink(); ?>" class="font-bold text-emerald-700 group-hover:underline">Baca Selengkapnya &rarr;</a>
                <span class="text-slate-400"><?php the_author(); ?></span>
              </div>
            <?php endif; ?>
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
