<?php
/**
 * Template Name: Sekapur Sirih / Sambutan Kepala Madrasah
 * Template Post Type: editorial
 * 
 * Khusus menampilkan Halaman Lengkap Sekapur Sirih Kepala Madrasah
 * Membedakan foto di dalam halaman dengan foto portrait di beranda
 */
get_header();
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <?php while ( have_posts() ) : the_post(); 
        $kepala_nama        = get_theme_mod( 'kepala_sekolah', 'Drs. SIHABBUDIN' );
        $editorial_headline = get_post_meta( get_the_ID(), '_editorial_headline', true );
        if ( empty( $editorial_headline ) && has_excerpt() ) {
            $editorial_headline = get_the_excerpt();
        }

        // 1. Foto Khusus Beranda
        $foto_depan = get_post_meta( get_the_ID(), '_foto_kepala_depan', true );
        if ( empty( $foto_depan ) ) {
            $foto_depan = get_template_directory_uri() . '/assets/img/kepala-madrasah.webp';
        }

        // 2. Banner / Foto Khusus di Dalam Halaman Sambutan
        $inside_banner = get_post_meta( get_the_ID(), '_editorial_inside_banner', true );
        if ( empty( $inside_banner ) && has_post_thumbnail() ) {
            $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            // Jika thumbnail bukan foto potret kepala madrasah yang sama dengan beranda, gunakan sebagai banner
            if ( strpos( $thumb_url, 'DSC05956' ) === false && strpos( $thumb_url, 'kepala-madrasah' ) === false ) {
                $inside_banner = $thumb_url;
            }
        }
    ?>
      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 flex-wrap">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
        <span>/</span>
        <a href="<?php echo esc_url( home_url( '/profil' ) ); ?>" class="hover:text-emerald-700">Profil</a>
        <span>/</span>
        <span class="text-emerald-700 font-semibold"><?php the_title(); ?></span>
      </nav>

      <article class="bg-white rounded-3xl p-6 sm:p-12 shadow-sm border border-slate-200">
        
        <!-- Badge & Header Info -->
        <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500 mb-4 pb-4 border-b border-slate-100">
          <div class="flex items-center gap-2">
            <span class="px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-extrabold uppercase text-[11px] tracking-wide inline-flex items-center gap-1.5">
              <i class="fa-solid fa-feather-pointed text-emerald-600"></i> <?php the_title(); ?>
            </span>
            <span class="text-slate-400">• MTsN 1 Blitar</span>
          </div>
          <div class="flex items-center gap-3 text-[11px] text-slate-400">
            <span><i class="fa-regular fa-clock text-emerald-600 mr-1"></i> <?php echo esc_html( mtsn1_reading_time() ); ?></span>
          </div>
        </div>

        <!-- Headline Sambutan Besar -->
        <?php if ( ! empty( $editorial_headline ) ) : ?>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight mb-4">
            "<?php echo esc_html( trim( $editorial_headline, "\"'“”" ) ); ?>"
          </h1>
        <?php else : ?>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight mb-4">
            <?php the_title(); ?>
          </h1>
        <?php endif; ?>

        <!-- Bilah Berbagi Cepat & Salin Link -->
        <div class="mb-8 pb-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
          <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="mtsn1CopyLink(this)" data-url="<?php echo esc_url( get_permalink() ); ?>" class="copy-link-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold transition border border-slate-200/80 shadow-xs cursor-pointer active:scale-95" title="Salin Tautan">
              <i class="fa-regular fa-copy text-emerald-600"></i>
              <span class="copy-text">Salin Link</span>
            </button>
            <button type="button" onclick="mtsn1NativeShare(this)" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-url="<?php echo esc_url( get_permalink() ); ?>" class="native-share-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold transition border border-slate-200/80 shadow-xs cursor-pointer active:scale-95" title="Bagikan">
              <i class="fa-solid fa-share-nodes text-emerald-600"></i>
              <span>Bagikan</span>
            </button>
            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . "\n\n" . get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold transition shadow-xs active:scale-95" title="WhatsApp">
              <i class="fa-brands fa-whatsapp text-sm"></i>
              <span>WhatsApp</span>
            </a>
          </div>
          <div class="hidden sm:flex items-center gap-1.5 text-[11px] font-semibold text-slate-400">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span>Portal Resmi MTsN 1 Blitar</span>
          </div>
        </div>

        <!-- Banner Khusus di Dalam Halaman (Jika diset banner lanskap tersendiri) -->
        <?php if ( ! empty( $inside_banner ) ) : ?>
          <div class="rounded-2xl overflow-hidden mb-10 shadow-md aspect-video sm:aspect-[21/9] bg-slate-100">
            <img src="<?php echo esc_url( $inside_banner ); ?>" alt="Banner Sambutan Kepala Madrasah" class="w-full h-full object-cover">
          </div>
        <?php endif; ?>

        <!-- Layout Isi Sambutan & Profil Pimpinan -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
          
          <!-- Kolom Kiri: Profil & Foto Resmi Kepala Madrasah (Utuh Tanpa Terpotong) -->
          <div class="lg:col-span-4 shrink-0">
            <div class="sticky top-28 bg-gradient-to-br from-slate-50 via-emerald-50/40 to-slate-100 p-5 rounded-2xl border border-slate-200/80 shadow-sm text-center">
              
              <!-- Bingkai Foto Potret Proporsional -->
              <div class="w-48 sm:w-52 h-64 sm:h-70 mx-auto rounded-2xl overflow-hidden bg-slate-900 shadow-md border-2 border-white ring-2 ring-emerald-500/20 mb-4 group">
                <img src="<?php echo esc_url( $foto_depan ); ?>" alt="<?php echo esc_attr( $kepala_nama ); ?>" class="w-full h-full object-cover object-top group-hover:scale-103 transition-transform duration-500">
              </div>

              <!-- Identitas Pimpinan -->
              <h3 class="text-base sm:text-lg font-black text-slate-900 leading-tight">
                <?php echo esc_html( $kepala_nama ); ?>
              </h3>
              <p class="text-xs font-bold text-emerald-700 mt-1">
                Kepala MTsN 1 Blitar
              </p>
              <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                Masa Khidmat 2022 - Sekarang
              </p>

              <div class="mt-4 pt-3 border-t border-slate-200/70 text-[11px] text-slate-600 leading-relaxed italic">
                "Mendidik generasi unggul, beriman dan bertaqwa dengan berwawasan digital."
              </div>
            </div>
          </div>

          <!-- Kolom Kanan: Teks Lengkap Sambutan -->
          <div class="lg:col-span-8">
            <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
              <?php the_content(); ?>
            </div>

            <!-- Tanda Tangan & Penutup Resmi -->
            <div class="mt-12 pt-8 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6">
              <div>
                <p class="text-xs text-slate-500 mb-1">Blitar, Jawa Timur</p>
                <p class="text-xs font-bold text-slate-700">Kepala MTs Negeri 1 Blitar</p>
                <div class="h-16 flex items-center text-slate-300 font-serif italic text-2xl select-none">
                  ( Tanda Tangan Resmi )
                </div>
                <p class="text-sm font-black text-slate-900 underline underline-offset-4"><?php echo esc_html( $kepala_nama ); ?></p>
                <p class="text-xs text-slate-500">NIP. 196805141994031002</p>
              </div>

              <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                <span>Bagikan:</span>
                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
              </div>
            </div>
          </div>

        </div>

      </article>

    <?php endwhile; ?>

  </div>
</div>

<?php get_footer(); ?>
