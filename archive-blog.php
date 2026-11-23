<?php
/**
 * Template Name: Arsip Portal Al-Qalam (Blog Guru & Opini GTK)
 * The template for displaying archive of Blog GTK MTsN 1 Blitar
 */
get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$blog_query = new WP_Query( array(
    'post_type'      => 'blog',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

$total_articles = $blog_query->found_posts;
$categories = get_terms( array(
    'taxonomy'   => 'blog-kat',
    'hide_empty' => true,
) );
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
      <span>/</span>
      <span class="text-slate-800 font-semibold">Portal Literasi Al-Qalam</span>
    </nav>

    <!-- Header Banner Al-Qalam -->
    <div class="bg-gradient-to-r from-emerald-950 via-slate-900 to-emerald-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden mb-10 border border-emerald-800/40">
      <!-- Decorative BG elements -->
      <div class="absolute -right-12 -bottom-12 w-72 h-72 rounded-full bg-emerald-500/10 pointer-events-none blur-3xl"></div>
      <div class="absolute right-12 top-1/2 -translate-y-1/2 opacity-10 text-9xl hidden lg:block pointer-events-none text-emerald-400">
        <i class="fa-solid fa-feather-pointed"></i>
      </div>

      <div class="relative z-10 max-w-3xl">
        <div class="flex items-center gap-2 mb-4">
          <span class="px-3.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-gold-400 text-slate-950 inline-flex items-center gap-1.5 shadow-md">
            <i class="fa-solid fa-pen-nib text-slate-950"></i> Ruang Literasi GTK
          </span>
          <span class="text-xs text-emerald-300 font-semibold hidden sm:inline">• MTsN 1 Blitar</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-3">
          <div class="p-2 bg-white rounded-2xl border border-white/20 shadow-md inline-flex shrink-0 w-fit">
            <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2026/11/logo-al-qalam.webp' ) ); ?>" alt="Logo Al-Qalam MTsN 1 Blitar" class="h-10 sm:h-12 w-auto object-contain">
          </div>
          <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight text-white drop-shadow-sm">
              Portal Inspirasi &amp; Opini Guru
            </h1>
            <p class="text-amber-300 text-xs sm:text-sm font-bold tracking-wide mt-1">
              AL QALAM: Aksi - Literasi - Qudwah - Amanah - Lintas - Aksara - Madrasah
            </p>
          </div>
        </div>

        <p class="text-slate-300 text-xs sm:text-sm mt-3 leading-relaxed max-w-2xl">
          Wadah ekspresi intelektual, esai pedagogis, praktik baik pembelajaran (best practice), dan refleksi keteladanan Guru dan Tenaga Kependidikan (GTK) MTsN 1 Blitar.
        </p>

        <!-- Search Bar -->
        <div class="mt-6">
          <div class="relative max-w-md">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
              <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" id="blog-search" placeholder="Cari judul artikel atau nama guru..." class="w-full pl-11 pr-4 py-3 bg-white text-slate-900 rounded-2xl text-xs sm:text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-md">
          </div>
        </div>
      </div>
    </div>

    <!-- Category Filter Tabs & Total Count -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
      <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm bg-emerald-600 text-white">
          Semua Rubrik
        </a>
        <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
          <?php foreach ( $categories as $cat ) : ?>
            <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200">
              <?php echo esc_html( $cat->name ); ?> (<?php echo esc_html( $cat->count ); ?>)
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="text-xs font-semibold text-slate-500 shrink-0">
        Menampilkan <span class="font-bold text-slate-800"><?php echo esc_html( $total_articles ); ?></span> artikel literasi
      </div>
    </div>

    <!-- Grid Cards -->
    <?php if ( $blog_query->have_posts() ) : ?>
      <div id="blog-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        while ( $blog_query->have_posts() ) : $blog_query->the_post();
            $terms = get_the_terms( get_the_ID(), 'blog-kat' );
            $cat_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : 'Opini';
            $cat_slug = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
            $content_words = str_word_count( strip_tags( get_the_content() ) );
            $reading_time = max( 1, ceil( $content_words / 200 ) );

            $author_data  = function_exists( 'mtsn1_get_blog_author_data' ) ? mtsn1_get_blog_author_data( get_the_ID() ) : array( 'name' => get_the_author(), 'role' => 'GTK MTsN 1 Blitar', 'photo' => '' );
            $author_photo = ! empty( $author_data['photo'] ) ? $author_data['photo'] : home_url( '/wp-content/uploads/2025/08/WhatsApp-Image-2023-07-20-at-07.43.09-copy-768x1024.png' );
            $author_name  = $author_data['name'];
            $author_role  = $author_data['role'];

            // Gambar Banner Artikel (Sama seperti gambar di dalam artikel / Featured Image)
            $post_thumb = mtsn1_has_post_thumbnail() ? mtsn1_get_post_thumbnail_url( get_the_ID(), 'large' ) : ( ! empty( $author_photo ) ? $author_photo : mtsn1_get_post_thumbnail_url( get_the_ID(), 'large' ) );
            ?>
            <?php
            // Pengaturan Warna & Ikon Floating Badge
            $badge_bg = 'bg-emerald-600';
            $icon_bg  = 'bg-amber-400 text-slate-900';
            $badge_icon = 'fa-feather-pointed';

            if ( strpos( $cat_slug, 'sekolah' ) !== false ) {
                $badge_bg = 'bg-amber-600';
                $badge_icon = 'fa-school';
            } elseif ( strpos( $cat_slug, 'karakter' ) !== false || strpos( $cat_slug, 'siswa' ) !== false ) {
                $badge_bg = 'bg-blue-600';
                $badge_icon = 'fa-user-graduate';
            }
            ?>
            <article class="blog-card bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group p-3 sm:p-4 pt-3.5 relative" data-title="<?php echo esc_attr( strtolower( get_the_title() ) ); ?>" data-author="<?php echo esc_attr( strtolower( $author_name ) ); ?>">
              
              <!-- Gambar Banner Artikel (Bersih dengan Sudut Melengkung & Floating Badge di Garis Atas) -->
              <div class="relative mb-3">
                <!-- Floating Badge Kategori (Pojok Kanan Atas Melayang) -->
                <div class="absolute -top-2.5 right-2 z-20 shadow-md">
                  <div class="inline-flex items-stretch rounded-full overflow-hidden text-[9px] sm:text-[10px] font-black tracking-wider uppercase border border-black/10 shadow-sm">
                    <span class="<?php echo esc_attr( $icon_bg ); ?> px-2 sm:px-2.5 py-0.5 sm:py-1 flex items-center justify-center font-black">
                      <i class="fa-solid <?php echo esc_attr( $badge_icon ); ?>"></i>
                    </span>
                    <span class="<?php echo esc_attr( $badge_bg ); ?> text-white px-2.5 sm:px-3 py-0.5 sm:py-1 flex items-center">
                      <?php echo esc_html( $cat_name ); ?>
                    </span>
                  </div>
                </div>

                <!-- Banner Image (Bersih 100% Tanpa Efek Gelap/Blur, Sudut Membulat Rapi) -->
                <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-2xl aspect-[16/9] sm:aspect-[21/9] bg-slate-50 border border-slate-100/80 shadow-xs">
                  <img src="<?php echo esc_url( $post_thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                </a>
              </div>

              <!-- Isi Konten Card -->
              <div class="px-2 pb-1 sm:px-3 sm:pb-2 flex-1 flex flex-col justify-between">
                <div>
                  <!-- Meta Info: Tanggal & Waktu Baca (Sejajar Kiri-Kanan) -->
                  <div class="flex items-center justify-between text-[11px] font-semibold text-slate-400 mb-2.5">
                    <div class="flex items-center gap-1.5">
                      <i class="fa-regular fa-calendar-days text-emerald-600"></i>
                      <?php
                      $months_id = array(
                          1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                          5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                          9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                      );
                      $post_time = get_the_time( 'U' );
                      $formatted_date = gmdate( 'd', $post_time ) . ' ' . ( $months_id[ intval( gmdate( 'n', $post_time ) ) ] ?? '' ) . ' ' . gmdate( 'Y', $post_time );
                      ?>
                      <span><?php echo esc_html( $formatted_date ); ?></span>
                    </div>

                    <!-- Waktu Baca (Dipindahkan ke Sebelah Kanan Tanggal) -->
                    <div class="flex items-center gap-1 text-slate-500 bg-slate-100 hover:bg-slate-200 px-2 py-0.5 rounded-md font-bold text-[10px] transition">
                      <i class="fa-regular fa-clock text-emerald-600"></i>
                      <span><?php echo esc_html( $reading_time ); ?> mnt baca</span>
                    </div>
                  </div>

                  <h3 class="text-base sm:text-lg font-black text-slate-900 group-hover:text-emerald-700 transition leading-snug line-clamp-2">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h3>

                  <p class="text-xs text-slate-600 mt-2.5 leading-relaxed line-clamp-3">
                    <?php echo wp_trim_words( get_the_excerpt(), 24, '...' ); ?>
                  </p>
                </div>

                <div class="pt-4 mt-5 border-t border-slate-100 flex items-center justify-between">
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
              </div>

            </article>
            <?php
        endwhile;
        ?>
      </div>

      <!-- Pagination -->
      <div class="mt-10">
        <?php
        global $wp_query;
        $temp_query = $wp_query;
        $wp_query   = $blog_query;
        if ( function_exists( 'mtsn1_pagination' ) ) {
            mtsn1_pagination();
        }
        $wp_query = $temp_query;
        wp_reset_postdata();
        ?>
      </div>

    <?php else : ?>
      <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
        <i class="fa-solid fa-feather-pointed text-4xl text-slate-300 mb-3"></i>
        <h3 class="text-lg font-bold text-slate-800">Belum Ada Artikel Literasi</h3>
        <p class="text-xs text-slate-500 mt-1">Artikel opini dan karya tulis guru akan segera hadir di portal ini.</p>
      </div>
    <?php endif; ?>

  </div>
</div>

<!-- Client-side Live Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('blog-search');
  const cards = document.querySelectorAll('.blog-card');

  if (searchInput && cards.length > 0) {
    searchInput.addEventListener('input', function(e) {
      const q = e.target.value.toLowerCase().trim();
      cards.forEach(card => {
        const title = card.getAttribute('data-title') || '';
        const author = card.getAttribute('data-author') || '';
        if (title.includes(q) || author.includes(q)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  }
});
</script>

<?php
get_footer();
