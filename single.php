<?php
/**
 * The template for displaying all single posts (Berita, Pengumuman, Agenda, etc.)
 */
get_header();
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <?php while ( have_posts() ) : the_post(); 
        $cat_name = '';
        $cat_link = '';
        if ( get_post_type() === 'blog' ) {
            $terms = get_the_terms( get_the_ID(), 'blog-kat' );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $cat_name = $terms[0]->name;
                $cat_link = get_term_link( $terms[0] );
            } else {
                $cat_name = 'Blog & Opini';
                $cat_link = home_url( '/#berita' );
            }
        } else {
            $cats = get_the_category();
            if ( ! empty( $cats ) ) {
                $cat_name = $cats[0]->name;
                $cat_link = get_category_link( $cats[0]->term_id );
            }
        }
    ?>
      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 flex-wrap">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
        <?php if ( ! empty( $cat_name ) ) : ?>
          <span>/</span>
          <a href="<?php echo esc_url( $cat_link ); ?>" class="text-emerald-700 font-semibold hover:underline"><?php echo esc_html( $cat_name ); ?></a>
        <?php endif; ?>
        <span>/</span>
        <span class="line-clamp-1 max-w-xs text-slate-400"><?php the_title(); ?></span>
      </nav>

      <article class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200">
        <!-- Category & Date Header -->
        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mb-4">
          <?php if ( ! empty( $cat_name ) ) : ?>
            <a href="<?php echo esc_url( $cat_link ); ?>" class="px-3 py-1 rounded-full bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold uppercase text-[11px] transition">
              <?php echo esc_html( $cat_name ); ?>
            </a>
          <?php endif; ?>
          <span><i class="fa-regular fa-calendar text-emerald-600 mr-1"></i> <?php echo get_the_date( 'l, d F Y' ); ?></span>
          <?php 
          if ( get_post_type() === 'blog' ) : 
              $blog_author = function_exists( 'mtsn1_get_blog_author_data' ) ? mtsn1_get_blog_author_data( get_the_ID() ) : array( 'name' => get_the_author() );
          ?>
            <span><i class="fa-solid fa-pen-nib text-emerald-600 mr-1"></i> <?php echo esc_html( $blog_author['name'] ); ?></span>
          <?php else : 
              $redaksi = function_exists( 'mtsn1_get_berita_redaksi' ) ? mtsn1_get_berita_redaksi( get_the_ID() ) : array( 'penulis' => get_the_author(), 'editor' => 'Tim Humas MTsN 1 Blitar', 'foto' => 'Tim Media MTsN 1 Blitar' );
          ?>
            <span class="inline-flex items-center gap-1 font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md border border-slate-200/60">
              <i class="fa-solid fa-pen-nib text-emerald-600 mr-0.5"></i> Penulis: <strong class="text-slate-900"><?php echo esc_html( $redaksi['penulis'] ); ?></strong>
            </span>
          <?php endif; ?>
          <span><i class="fa-regular fa-clock text-emerald-600 mr-1"></i> <?php echo esc_html( mtsn1_reading_time() ); ?></span>
          <span class="text-slate-300">•</span>
          <a href="<?php echo esc_url( add_query_arg( 'print', 'berita', get_permalink() ) ); ?>" target="_blank" class="hover:text-emerald-700 transition inline-flex items-center gap-1 font-semibold text-emerald-700" title="Cetak Format Lembar Berita A4">
            <i class="fa-solid fa-print text-emerald-600"></i> Cetak A4
          </a>
        </div>

        <!-- Title -->
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 leading-tight mb-6">
          <?php the_title(); ?>
        </h1>

        <!-- Featured Image -->
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="rounded-2xl overflow-hidden mb-8 shadow-md aspect-video sm:aspect-[21/9] bg-slate-100">
            <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
          </div>
        <?php endif; ?>

        <!-- Post Content -->
        <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed">
          <?php the_content(); ?>
        </div>

        <?php if ( get_post_type() === 'post' ) : 
            $redaksi = function_exists( 'mtsn1_get_berita_redaksi' ) ? mtsn1_get_berita_redaksi( get_the_ID() ) : array( 'penulis' => get_the_author(), 'editor' => 'Tim Humas MTsN 1 Blitar', 'foto' => 'Tim Media MTsN 1 Blitar' );
        ?>
          <!-- Kotak Redaksi & Peliputan (Penulis, Editor, Foto) -->
          <div class="mt-8 mb-2 p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-emerald-50/90 via-slate-50 to-white border border-emerald-100/90 shadow-sm">
            <div class="flex items-center justify-between gap-2 mb-4 pb-2.5 border-b border-emerald-200/60">
              <div class="flex items-center gap-2">
                <span class="w-2 h-4 rounded-full bg-emerald-600"></span>
                <span class="text-xs font-black uppercase tracking-wider text-emerald-950">Tim Liputan & Redaksi Warta</span>
              </div>
              <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100/80 px-2.5 py-0.5 rounded-md">Publikasi Resmi</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
              <!-- Kolom Penulis -->
              <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-emerald-50/80 shadow-xs">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-sm">
                  <i class="fa-solid fa-pen-nib"></i>
                </div>
                <div class="min-w-0">
                  <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Penulis / Pewarta:</span>
                  <span class="text-xs font-bold text-slate-900 block leading-tight truncate"><?php echo esc_html( $redaksi['penulis'] ); ?></span>
                </div>
              </div>

              <!-- Kolom Editor -->
              <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-emerald-50/80 shadow-xs">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-sm">
                  <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="min-w-0">
                  <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Editor / Redaktur:</span>
                  <span class="text-xs font-bold text-slate-900 block leading-tight truncate"><?php echo esc_html( $redaksi['editor'] ); ?></span>
                </div>
              </div>

              <!-- Kolom Foto -->
              <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-emerald-50/80 shadow-xs">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-sm">
                  <i class="fa-solid fa-camera"></i>
                </div>
                <div class="min-w-0">
                  <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Dokumentasi Foto:</span>
                  <span class="text-xs font-bold text-slate-900 block leading-tight truncate"><?php echo esc_html( $redaksi['foto'] ); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?php if ( get_post_type() === 'blog' ) : 
            $blog_author = function_exists( 'mtsn1_get_blog_author_data' ) ? mtsn1_get_blog_author_data( get_the_ID() ) : array( 'name' => get_the_author(), 'role' => 'GTK MTsN 1 Blitar', 'photo' => '' );
        ?>
          <!-- Kotak Profil Penulis (Author Bio Box) -->
          <div class="mt-10 p-6 sm:p-7 rounded-2xl bg-gradient-to-br from-emerald-50/70 via-slate-50 to-white border border-emerald-100 shadow-sm flex flex-col sm:flex-row items-center sm:items-start gap-5">
            <div class="w-20 h-24 sm:w-24 sm:h-28 rounded-2xl overflow-hidden shadow-md shrink-0 bg-slate-900 border-2 border-white ring-2 ring-emerald-500/20">
              <?php if ( ! empty( $blog_author['photo'] ) ) : ?>
                <img src="<?php echo esc_url( $blog_author['photo'] ); ?>" alt="<?php echo esc_attr( $blog_author['name'] ); ?>" class="w-full h-full object-cover object-top">
              <?php else : ?>
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-800 to-slate-900 text-emerald-300 text-2xl">
                  <i class="fa-solid fa-user-pen"></i>
                </div>
              <?php endif; ?>
            </div>
            <div class="flex-1 text-center sm:text-left">
              <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 bg-emerald-100/80 px-2.5 py-0.5 rounded-full inline-block mb-1.5">Penulis Gagasan</span>
              <h4 class="text-base sm:text-lg font-black text-slate-900 leading-tight"><?php echo esc_html( $blog_author['name'] ); ?></h4>
              <p class="text-xs font-semibold text-emerald-600 mt-0.5"><?php echo esc_html( $blog_author['role'] ); ?></p>
              <p class="text-xs text-slate-600 mt-2.5 leading-relaxed">
                Karya opini dan gagasan ini diterbitkan melalui Ruang Literasi GTK MTs Negeri 1 Blitar sebagai wadah pemikiran inspiratif untuk memajukan mutu pembelajaran, peradaban, dan karakter madrasah.
              </p>
            </div>
          </div>
        <?php endif; ?>

        <!-- Share & Print Buttons -->
        <div class="mt-10 pt-6 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
          <div class="flex flex-wrap items-center gap-2.5 text-xs font-bold text-slate-600">
            <span>Bagikan:</span>
            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition shadow-xs" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition shadow-xs" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode( get_the_title() ); ?>&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition shadow-xs" title="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a>
            <span class="text-slate-300">|</span>
            <a href="<?php echo esc_url( add_query_arg( 'print', 'berita', get_permalink() ) ); ?>" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-800 transition flex items-center gap-1.5 border border-emerald-200 shadow-xs" title="Cetak Format Lembar Berita A4">
              <i class="fa-solid fa-print"></i> Cetak Berita (A4)
            </a>
          </div>

          <a href="<?php echo esc_url( home_url( '/#berita' ) ); ?>" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-xs font-bold text-slate-700 transition flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Berita
          </a>
        </div>
      </article>

      <!-- Related News (3 Cards) -->
      <div class="mt-12">
        <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
          <span class="w-2 h-5 rounded-full bg-emerald-600"></span> Warta Terkait Lainnya
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <?php
          $related_q = new WP_Query( array(
              'post_type'      => 'post',
              'posts_per_page' => 3,
              'post__not_in'   => array( get_the_ID() ),
              'post_status'    => 'publish',
          ) );

          if ( $related_q->have_posts() ) :
              while ( $related_q->have_posts() ) : $related_q->the_post();
                  ?>
                  <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-200 hover:shadow-lg transition group flex flex-col justify-between">
                    <div>
                      <div class="overflow-hidden rounded-2xl <?php echo get_post_type() === 'blog' ? 'aspect-[21/9] bg-slate-950' : 'aspect-[16/10] bg-slate-100'; ?> mb-3 flex items-center justify-center">
                        <?php if ( has_post_thumbnail() ) : ?>
                          <?php the_post_thumbnail( 'mtsn1-card', array( 'class' => 'w-full h-full ' . ( get_post_type() === 'blog' ? 'object-contain' : 'object-cover' ) . ' group-hover:scale-105 transition duration-300' ) ); ?>
                        <?php else : ?>
                          <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/santri.jpg' ) ); ?>" class="w-full h-full object-cover">
                        <?php endif; ?>
                      </div>
                      <h4 class="text-xs font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2 leading-snug">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                      </h4>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-100 text-[10px] text-slate-400 flex justify-between">
                      <span><?php echo get_the_date( 'd M Y' ); ?></span>
                      <a href="<?php the_permalink(); ?>" class="text-emerald-700 font-bold">Baca &rarr;</a>
                    </div>
                  </div>
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
