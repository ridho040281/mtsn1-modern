<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: https://ogp.me/ns#">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php if ( function_exists( 'mtsn1_opengraph_meta_tags' ) ) { mtsn1_opengraph_meta_tags(); } ?>

  <!-- PWA & Mobile Capabilities -->
  <link rel="manifest" href="<?php echo esc_url( home_url( '/manifest.json' ) ); ?>">
  <meta name="theme-color" content="#064e3b">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="MTsN 1 Blitar">
  <link rel="apple-touch-icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/img/pwa/apple-touch-icon.png' ); ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( get_template_directory_uri() . '/assets/img/pwa/favicon-32.png' ); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( get_template_directory_uri() . '/assets/img/pwa/favicon-16.png' ); ?>">

  <!-- Instant Page Preloading (Speculation Rules API) -->
  <script type="speculationrules">
  {
    "prerender": [
      {
        "source": "list",
        "urls": [
          "<?php echo esc_url( home_url( '/' ) ); ?>",
          "<?php echo esc_url( home_url( '/prestasi/' ) ); ?>",
          "<?php echo esc_url( home_url( '/profil/' ) ); ?>",
          "<?php echo esc_url( home_url( '/gtk/' ) ); ?>"
        ]
      }
    ],
    "prefetch": [
      {
        "source": "document",
        "where": {
          "href_matches": "/*"
        },
        "eagerness": "moderate"
      }
    ]
  }
  </script>
  <!-- Fail-safe: Sembunyikan item Statistik Publikasi di bawah dropdown Info Publikasi -->
  <style>
    li[data-menu*="PUBLIKASI"] li:has(a[href*="statistik"]),
    li[data-menu*="PUBLIKASI"] a[href*="statistik"] {
      display: none !important;
    }
  </style>
  <script>
    (function() {
      function purgeOldStatMenu() {
        document.querySelectorAll('header nav a, #mobileMenu a').forEach(function(a) {
          if (a.textContent && a.textContent.trim().toLowerCase() === 'statistik publikasi') {
            var li = a.closest('li');
            if (li) {
              li.remove();
            } else {
              a.remove();
            }
          }
        });
      }
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', purgeOldStatMenu);
      } else {
        purgeOldStatMenu();
      }
    })();
  </script>
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-slate-100 text-slate-800 font-sans antialiased selection:bg-madrasah-600 selection:text-white' ); ?>>
<?php wp_body_open(); ?>

  <!-- TOPBAR DENGAN TANGGAL MASEHI & HIJRIAH & MEDSOS RESMI -->
  <?php $top_medsos = function_exists( 'mtsn1_get_medsos' ) ? mtsn1_get_medsos() : []; ?>
  <div class="bg-madrasah-950 text-slate-200 text-xs py-2 px-3 sm:px-4 border-b border-madrasah-800 overflow-hidden w-full">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
      <div class="flex flex-wrap justify-center sm:justify-start items-center gap-2 sm:gap-3 text-[11px] sm:text-xs text-center">
        <span class="flex items-center gap-1 font-medium"><i class="fa-solid fa-moon text-gold-400"></i> <?php echo esc_html( mtsn1_hijri_date() ); ?></span>
        <span class="text-slate-600">|</span>
        <span class="flex items-center gap-1 font-medium"><i class="fa-regular fa-calendar text-emerald-400"></i> <?php echo esc_html( mtsn1_indonesian_date() ); ?></span>
        <?php if ( ! empty( $top_medsos['npsn'] ) ) : ?>
          <span class="hidden lg:inline text-slate-600">|</span>
          <span class="hidden lg:inline text-gold-300 font-semibold"><i class="fa-solid fa-shield-halved mr-1"></i> <?php echo esc_html( $top_medsos['npsn'] ); ?></span>
        <?php endif; ?>
      </div>
      <div class="flex flex-wrap justify-center items-center gap-3 sm:gap-5 text-[11px] sm:text-xs">
        <?php if ( ! empty( $top_medsos['telepon'] ) ) : ?>
          <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $top_medsos['telepon'] ) ); ?>" class="hover:text-gold-400 transition flex items-center gap-1"><i class="fa-solid fa-phone text-emerald-400"></i> <?php echo esc_html( $top_medsos['telepon'] ); ?></a>
        <?php endif; ?>
        <?php if ( ! empty( $top_medsos['email'] ) ) : ?>
          <a href="mailto:<?php echo esc_attr( $top_medsos['email'] ); ?>" class="hover:text-gold-400 transition flex items-center gap-1"><i class="fa-solid fa-envelope text-emerald-400"></i> <?php echo esc_html( $top_medsos['email'] ); ?></a>
        <?php endif; ?>
        <div class="flex items-center gap-2 sm:gap-2.5 text-xs sm:text-sm border-l border-slate-700 pl-3 sm:pl-4">
          <?php if ( ! empty( $top_medsos['youtube'] ) ) : ?>
            <a href="<?php echo esc_url( $top_medsos['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-gold-400 transition" title="YouTube MTsN 1 Blitar"><i class="fa-brands fa-youtube"></i></a>
          <?php endif; ?>
          <?php if ( ! empty( $top_medsos['instagram'] ) ) : ?>
            <a href="<?php echo esc_url( $top_medsos['instagram'] ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-gold-400 transition" title="Instagram Resmi"><i class="fa-brands fa-instagram"></i></a>
          <?php endif; ?>
          <?php if ( ! empty( $top_medsos['facebook'] ) ) : ?>
            <a href="<?php echo esc_url( $top_medsos['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-gold-400 transition" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <?php endif; ?>
          <?php if ( ! empty( $top_medsos['tiktok'] ) ) : ?>
            <a href="<?php echo esc_url( $top_medsos['tiktok'] ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-gold-400 transition" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- BREAKING NEWS / RUNNING TEXT TICKER (1 BARIS BERJALAN) -->
  <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-madrasah-900 border-b border-emerald-700/50 text-white text-xs py-2 shadow-inner overflow-hidden w-full">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 flex items-center gap-2 sm:gap-3 overflow-hidden min-w-0">
      <span class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-red-600 text-white font-extrabold tracking-wide uppercase text-[10px] shadow-sm shrink-0 animate-pulse">
        <i class="fa-solid fa-bullhorn text-xs"></i> PENGUMUMAN
      </span>
      <div class="overflow-hidden relative w-full min-w-0 flex-1">
        <marquee onmouseover="this.stop();" onmouseout="this.start();" scrollamount="6" class="font-medium text-slate-100 block align-middle">
          <div class="inline-flex items-center gap-8 whitespace-nowrap">
            <?php
            $ticker_q = new WP_Query( array(
                'post_type'      => array( 'pengumuman', 'post' ),
                'posts_per_page' => 5,
                'post_status'    => 'publish',
            ) );
            if ( $ticker_q->have_posts() ) :
                while ( $ticker_q->have_posts() ) : $ticker_q->the_post();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="hover:text-gold-300 transition inline-flex items-center gap-2 whitespace-nowrap text-slate-100">
                      <span>📢 <?php the_title(); ?></span>
                    </a>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <span class="whitespace-nowrap">📢 Selamat Datang di Website Resmi MTsN 1 Blitar (Madrasah Mandiri Berprestasi).</span>
                <?php
            endif;
            ?>
          </div>
        </marquee>
      </div>
    </div>
  </div>

  <!-- MAIN HEADER & NAVIGATION -->
  <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm w-full">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-2.5 sm:py-3 flex items-center justify-between gap-2 sm:gap-4">
      
      <!-- Logo MTsN 1 Blitar & Identitas Kemenag -->
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1 sm:flex-initial group">
        <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2024/01/ikon_mtsn1blitar.png' ) ); ?>" alt="Logo MTsN 1 Blitar" class="h-10 w-10 sm:h-14 sm:w-14 object-contain shrink-0 group-hover:scale-105 transition-transform duration-300" onerror="this.onerror=null; this.src='<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>';">
        <div class="leading-tight min-w-0">
          <span class="text-base sm:text-2xl font-black text-madrasah-900 tracking-tight block truncate">MTsN 1 BLITAR</span>
          <p class="text-[9px] sm:text-xs font-bold text-emerald-700 tracking-wide mt-0.5 truncate">MADRASAH TSANAWIYAH NEGERI 1 BLITAR</p>
        </div>
      </a>

      <!-- Menu Navigasi Lengkap Desktop -->
      <nav class="hidden xl:flex items-center gap-5 2xl:gap-7 shrink-0">
        <ul class="flex items-center gap-5 2xl:gap-7 list-none m-0 p-0">
        <?php
        if ( has_nav_menu( 'primary' ) ) :
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new MTSN1_Walker_Nav_Menu(),
                'fallback_cb'    => false,
            ) );
        else :
            ?>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="whitespace-nowrap px-1 py-1 text-emerald-700 border-b-2 border-emerald-700">Beranda</a></li>
            <li><a href="<?php echo esc_url( home_url( '/profil' ) ); ?>" class="whitespace-nowrap px-1 py-1 hover:text-emerald-700 transition">Profil</a></li>
            <li><a href="<?php echo esc_url( home_url( '/gtk' ) ); ?>" class="whitespace-nowrap px-1 py-1 hover:text-emerald-700 transition">GTK & Staf</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#berita' ) ); ?>" class="whitespace-nowrap px-1 py-1 hover:text-emerald-700 transition">Berita</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#pengumuman' ) ); ?>" class="whitespace-nowrap px-1 py-1 hover:text-emerald-700 transition">Pengumuman</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#agenda' ) ); ?>" class="whitespace-nowrap px-1 py-1 hover:text-emerald-700 transition">Agenda</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#prestasi' ) ); ?>" class="whitespace-nowrap px-1 py-1 hover:text-emerald-700 transition">Prestasi</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#galeri' ) ); ?>" class="whitespace-nowrap px-1 py-1 hover:text-emerald-700 transition">Galeri</a></li>
            <li class="relative shrink-0 my-auto ml-1 2xl:ml-2">
              <a href="https://ppdb.mtsn1blitar.sch.id" target="_blank" rel="noopener noreferrer" class="group/spmb relative inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gradient-to-r from-amber-400 via-amber-300 to-yellow-400 hover:from-amber-300 hover:to-yellow-300 text-slate-950 font-black text-xs sm:text-[13px] shadow-sm hover:shadow-md hover:-translate-y-0.5 border border-amber-200 transition-all duration-300">
                <span class="w-6 h-6 rounded-lg bg-slate-950 text-gold-400 flex items-center justify-center text-[11px] shadow-inner group-hover/spmb:scale-105 group-hover/spmb:bg-emerald-950 group-hover/spmb:text-amber-300 transition-all shrink-0"><i class="fa-solid fa-graduation-cap"></i></span>
                <span class="tracking-tight whitespace-nowrap">SPMB 2026-2027</span>
                <i class="fa-solid fa-arrow-right text-[10px] text-slate-800 group-hover/spmb:translate-x-0.5 transition-transform ml-0.5"></i>
              </a>
            </li>
            <?php
        endif;
        ?>
        </ul>
      </nav>

      <!-- Search & Mobile Toggle -->
      <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        <!-- Search form popup trigger -->
        <button onclick="document.getElementById('searchModal').classList.toggle('hidden')" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 flex items-center justify-center transition border border-slate-200" title="Pencarian">
          <i class="fa-solid fa-magnifying-glass text-xs sm:text-sm"></i>
        </button>

        <!-- Compact SPMB card visible only on tablet (sm to xl) when desktop nav is collapsed -->
        <a href="https://ppdb.mtsn1blitar.sch.id" target="_blank" rel="noopener noreferrer" class="xl:hidden hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gradient-to-r from-amber-400 via-amber-300 to-yellow-400 text-slate-950 font-black text-xs shadow-sm border border-amber-200 hover:shadow-md hover:-translate-y-0.5 transition-all">
          <i class="fa-solid fa-graduation-cap text-xs"></i>
          <span>SPMB 2026-2027</span>
        </a>

        <!-- Mobile hamburger menu button -->
        <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="xl:hidden w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-md shrink-0">
          <i class="fa-solid fa-bars text-sm"></i>
        </button>
      </div>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div id="mobileMenu" class="hidden xl:hidden bg-white border-t border-slate-200 px-6 py-4 space-y-2 font-bold text-sm shadow-xl max-h-[80vh] overflow-y-auto">
      <?php
      if ( has_nav_menu( 'primary' ) ) :
          wp_nav_menu( array(
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'space-y-2 text-slate-800',
              'fallback_cb'    => false,
          ) );
      else :
          ?>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block py-2 text-emerald-700 border-b border-slate-100">Beranda</a>
          <a href="<?php echo esc_url( home_url( '/profil' ) ); ?>" class="block py-2 text-slate-700 hover:text-emerald-700 border-b border-slate-100">Profil Madrasah</a>
          <a href="<?php echo esc_url( home_url( '/gtk' ) ); ?>" class="block py-2 text-slate-700 hover:text-emerald-700 border-b border-slate-100">GTK & Dewan Guru</a>
          <a href="<?php echo esc_url( home_url( '/#berita' ) ); ?>" class="block py-2 text-slate-700 hover:text-emerald-700 border-b border-slate-100">Kabar Berita</a>
          <a href="<?php echo esc_url( home_url( '/#pengumuman' ) ); ?>" class="block py-2 text-slate-700 hover:text-emerald-700 border-b border-slate-100">Pengumuman</a>
          <a href="<?php echo esc_url( home_url( '/#agenda' ) ); ?>" class="block py-2 text-slate-700 hover:text-emerald-700 border-b border-slate-100">Agenda Kegiatan</a>
          <a href="<?php echo esc_url( home_url( '/#prestasi' ) ); ?>" class="block py-2 text-slate-700 hover:text-emerald-700 border-b border-slate-100">Prestasi Siswa</a>
          <a href="<?php echo esc_url( home_url( '/#galeri' ) ); ?>" class="block py-2 text-slate-700 hover:text-emerald-700 border-b border-slate-100">Galeri & Video</a>
          <?php
      endif;
      ?>
      <a href="https://ppdb.mtsn1blitar.sch.id" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between p-3.5 rounded-2xl bg-gradient-to-r from-amber-400 via-amber-300 to-yellow-400 text-slate-950 shadow-md border border-amber-200 mt-4 group transition-all">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-slate-950 text-amber-400 flex items-center justify-center text-sm shadow group-hover:scale-105 transition-transform shrink-0">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>
          <div class="text-left leading-tight">
            <div class="flex items-center gap-1.5 mb-0.5">
              <span class="relative flex h-1.5 w-1.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-600 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-700"></span>
              </span>
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-900/80">Pendaftaran Siswa Baru</span>
            </div>
            <span class="text-sm font-black text-slate-950 block">SPMB 2026-2027 ONLINE</span>
          </div>
        </div>
        <div class="w-7 h-7 rounded-lg bg-black/5 flex items-center justify-center text-slate-800 text-xs group-hover:translate-x-1 transition-transform">
          <i class="fa-solid fa-arrow-right"></i>
        </div>
      </a>
    </div>

    <!-- Search Modal -->
    <div id="searchModal" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl p-6 w-full max-w-xl shadow-2xl relative">
        <button onclick="document.getElementById('searchModal').classList.add('hidden')" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm">
          <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-lg font-black text-slate-900 mb-3 flex items-center gap-2">
          <i class="fa-solid fa-magnifying-glass text-emerald-600"></i> Cari Informasi Madrasah
        </h3>
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex gap-2">
          <input type="search" name="s" placeholder="Ketik kata kunci pencarian..." class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:border-emerald-600 text-sm">
          <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm">Cari</button>
        </form>
      </div>
    </div>
  </header>
