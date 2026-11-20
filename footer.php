  <!-- MEGA FOOTER 4 KOLOM LENGKAP -->
  <footer class="bg-slate-950 text-slate-400 pt-16 pb-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
        <!-- Kolom 1: Profil Lembaga -->
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <img src="<?php echo esc_url( home_url( '/wp-content/uploads/2024/01/ikon_mtsn1blitar.png' ) ); ?>" alt="Logo MTsN 1 Blitar" class="w-11 h-11 object-contain" onerror="this.onerror=null; this.src='<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>';">
            <div>
              <h4 class="text-lg font-black text-white leading-tight">MTsN 1 BLITAR</h4>
              <p class="text-[11px] text-emerald-400 font-bold">Kementerian Agama RI</p>
            </div>
          </div>
          <p class="text-xs text-slate-400 leading-relaxed">
            Madrasah Tsanawiyah Negeri 1 Blitar menyelenggarakan pendidikan berbasis nilai keislaman, penguasaan IPTEK, dan pembiasaan adab mulia.
          </p>
          <?php $foot_medsos = function_exists( 'mtsn1_get_medsos' ) ? mtsn1_get_medsos() : []; ?>
          <div class="text-xs text-slate-400 space-y-1.5 pt-2">
            <?php 
            $foot_alamat = ! empty( $foot_medsos['alamat'] ) ? $foot_medsos['alamat'] : 'Jl. Raya Kunir No. 01, Wonodadi, Blitar, Jatim';
            ?>
            <p><i class="fa-solid fa-location-dot text-emerald-400 mr-2"></i> <?php echo esc_html( $foot_alamat ); ?></p>
            <?php if ( ! empty( $foot_medsos['telepon'] ) ) : ?>
              <p><i class="fa-solid fa-phone text-emerald-400 mr-2"></i> Telepon: <?php echo esc_html( $foot_medsos['telepon'] ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $foot_medsos['email'] ) ) : ?>
              <p><i class="fa-solid fa-envelope text-emerald-400 mr-2"></i> Email: <?php echo esc_html( $foot_medsos['email'] ); ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Kolom 2: Profil & Akademik -->
        <div>
          <h5 class="text-sm font-black text-white uppercase tracking-wider mb-4 border-l-4 border-emerald-500 pl-2.5">Madrasah</h5>
          <ul class="space-y-2 text-xs">
            <li><a href="<?php echo esc_url( home_url( '/profil' ) ); ?>" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-emerald-500"></i> Sejarah Singkat</a></li>
            <li><a href="<?php echo esc_url( home_url( '/visi-misi' ) ); ?>" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-emerald-500"></i> Visi, Misi & Tujuan</a></li>
            <li><a href="<?php echo esc_url( home_url( '/gtk' ) ); ?>" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-emerald-500"></i> Struktur Organisasi GTK</a></li>
            <li><a href="<?php echo esc_url( home_url( '/#berita' ) ); ?>" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-emerald-500"></i> Kabar Warta Madrasah</a></li>
            <li><a href="<?php echo esc_url( home_url( '/fasilitas' ) ); ?>" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-emerald-500"></i> Sarana & Fasilitas Belajar</a></li>
            <li><a href="<?php echo esc_url( home_url( '/prestasi' ) ); ?>" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-emerald-500"></i> Prestasi Akademik & Ekskul</a></li>
          </ul>
        </div>

        <!-- Kolom 3: Layanan Siswa & Publik -->
        <div>
          <h5 class="text-sm font-black text-white uppercase tracking-wider mb-4 border-l-4 border-emerald-500 pl-2.5">Layanan Digital</h5>
          <ul class="space-y-2 text-xs">
            <?php
            $footer_layanan = function_exists( 'mtsn1_get_portal_layanan' ) ? mtsn1_get_portal_layanan() : [];
            foreach ( $footer_layanan as $f_item ) :
                if ( empty( $f_item['active'] ) ) continue;
                $f_url = ! empty( $f_item['url'] ) ? esc_url( $f_item['url'] ) : '#';
                $f_target = ( isset( $f_item['target'] ) && $f_item['target'] === '_blank' && $f_url !== '#' ) ? ' target="_blank" rel="noopener noreferrer"' : '';
            ?>
            <li><a href="<?php echo $f_url; ?>"<?php echo $f_target; ?> class="hover:text-emerald-400 transition flex items-center gap-1.5"><i class="fa-solid fa-angle-right text-[10px] text-emerald-500"></i> <?php echo esc_html( $f_item['title'] ); ?><?php if ( ! empty( $f_item['desc'] ) ) : ?> <span class="text-slate-400 text-[10px]">• <?php echo esc_html( $f_item['desc'] ); ?></span><?php endif; ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Kolom 4: Lokasi & Medsos -->
        <div>
          <h5 class="text-sm font-black text-white uppercase tracking-wider mb-4 border-l-4 border-emerald-500 pl-2.5">Media & Jam Layanan</h5>
          <p class="text-xs text-slate-400 mb-3">Saluran komunikasi & publikasi resmi:</p>
          <div class="flex gap-2 mb-4">
            <?php if ( ! empty( $foot_medsos['youtube'] ) ) : ?>
              <a href="<?php echo esc_url( $foot_medsos['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-red-600 hover:text-white flex items-center justify-center transition" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
            <?php endif; ?>
            <?php if ( ! empty( $foot_medsos['instagram'] ) ) : ?>
              <a href="<?php echo esc_url( $foot_medsos['instagram'] ); ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-pink-600 hover:text-white flex items-center justify-center transition" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
            <?php endif; ?>
            <?php if ( ! empty( $foot_medsos['facebook'] ) ) : ?>
              <a href="<?php echo esc_url( $foot_medsos['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-blue-600 hover:text-white flex items-center justify-center transition" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
            <?php endif; ?>
            <?php if ( ! empty( $foot_medsos['tiktok'] ) ) : ?>
              <a href="<?php echo esc_url( $foot_medsos['tiktok'] ); ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-slate-700 hover:text-white flex items-center justify-center transition" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
            <?php endif; ?>
            <?php if ( ! empty( $foot_medsos['whatsapp'] ) ) : ?>
              <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $foot_medsos['whatsapp'] ) ); ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            <?php endif; ?>
          </div>
          <div class="p-3 rounded-2xl bg-slate-900 border border-slate-800 text-xs">
            <p class="text-slate-300 font-bold mb-1"><i class="fa-regular fa-clock text-emerald-400 mr-1"></i> Jam Layanan Kantor:</p>
            <p class="text-slate-400 text-[11px]">Senin – Kamis : 07.00 – 14.30 WIB<br>Jum'at : 07.00 – 11.00 WIB</p>
          </div>
        </div>
      </div>

      <div class="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
        <p>&copy; <?php echo date( 'Y' ); ?> MTsN 1 Blitar. Seluruh hak cipta dilindungi undang-undang.</p>
        <p class="flex items-center gap-2">
          <span>Desain Modern Tailwind CSS</span>
          <span>•</span>
          <span class="text-emerald-400 font-semibold">Mandiri Berprestasi</span>
        </p>
      </div>
    </div>
  </footer>

  <!-- Floating Back to Top Button -->
  <button id="backToTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-20 md:bottom-6 right-5 w-11 h-11 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white shadow-xl shadow-emerald-950/30 flex items-center justify-center transition-all duration-300 hover:-translate-y-1 z-40" title="Kembali ke atas">
    <i class="fa-solid fa-arrow-up"></i>
  </button>

  <!-- STICKY MOBILE BOTTOM NAVIGATION BAR (Thumb-Friendly for Mobile) -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-slate-200/90 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] py-1.5 px-2">
    <div class="flex items-center justify-around max-w-md mx-auto">
      
      <!-- Beranda -->
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl text-slate-600 hover:text-emerald-700 active:scale-95 transition-all group">
        <div class="w-6 h-6 flex items-center justify-center text-base mb-0.5 group-hover:text-emerald-700 text-emerald-800">
          <i class="fa-solid fa-house"></i>
        </div>
        <span class="text-[10px] font-bold tracking-tight">Beranda</span>
      </a>

      <!-- Prestasi Siswa -->
      <a href="<?php echo esc_url( home_url( '/prestasi/' ) ); ?>" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl text-slate-600 hover:text-amber-600 active:scale-95 transition-all group">
        <div class="w-6 h-6 flex items-center justify-center text-base mb-0.5 text-amber-500">
          <i class="fa-solid fa-trophy"></i>
        </div>
        <span class="text-[10px] font-bold tracking-tight">Prestasi</span>
      </a>

      <!-- SPMB Center Badge (Elevated Button) -->
      <a href="https://ppdb.mtsn1blitar.sch.id" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center justify-center -mt-5 group active:scale-95 transition-transform">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 via-amber-400 to-yellow-300 text-slate-950 flex items-center justify-center text-lg shadow-lg shadow-amber-500/40 border-2 border-white">
          <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <span class="text-[10px] font-black text-amber-800 tracking-tight mt-0.5">SPMB</span>
      </a>

      <!-- Berita -->
      <a href="<?php echo esc_url( home_url( '/#berita' ) ); ?>" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl text-slate-600 hover:text-emerald-700 active:scale-95 transition-all group">
        <div class="w-6 h-6 flex items-center justify-center text-base mb-0.5 text-slate-600 group-hover:text-emerald-700">
          <i class="fa-solid fa-newspaper"></i>
        </div>
        <span class="text-[10px] font-bold tracking-tight">Berita</span>
      </a>

      <!-- WhatsApp PTSP -->
      <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $foot_medsos['whatsapp'] ?? '6281234567890' ) ); ?>?text=Assalamu'alaikum%20Admin%20MTsN%201%20Blitar,%20saya%20ingin%20bertanya%20informasi%20madrasah" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl text-slate-600 hover:text-emerald-600 active:scale-95 transition-all group">
        <div class="w-6 h-6 flex items-center justify-center text-base mb-0.5 text-emerald-600">
          <i class="fa-brands fa-whatsapp text-lg"></i>
        </div>
        <span class="text-[10px] font-bold tracking-tight">PTSP WA</span>
      </a>

    </div>
  </nav>

  <!-- PWA INSTALL APP BANNER (Mobile & Desktop App Install Toast) -->
  <div id="pwaInstallBanner" class="hidden fixed bottom-20 left-4 right-4 sm:bottom-6 sm:right-6 sm:left-auto sm:max-w-sm z-40 bg-slate-950/95 backdrop-blur-md text-white p-3.5 rounded-2xl shadow-2xl border border-emerald-500/30 flex items-center justify-between gap-3 animate-fade-in">
    <div class="flex items-center gap-2.5">
      <img src="<?php echo esc_url( home_url( '/pwa-icon-192.png' ) ); ?>" alt="Logo MTsN 1 Blitar" class="w-9 h-9 rounded-xl shadow">
      <div class="leading-tight">
        <h5 class="text-xs font-black text-white">Pasang Aplikasi MTsN 1 Blitar</h5>
        <p class="text-[10px] text-emerald-300">Akses lebih cepat langsung dari layar HP</p>
      </div>
    </div>
    <div class="flex items-center gap-1.5 shrink-0">
      <button id="pwaInstallBtn" class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-slate-950 font-black text-xs shadow-md active:scale-95 transition">
        Pasang
      </button>
      <button onclick="document.getElementById('pwaInstallBanner').style.display='none'" class="w-7 h-7 rounded-lg text-slate-400 hover:text-white flex items-center justify-center text-xs">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
  </div>

  <!-- PWA & SPEED ENHANCEMENT SCRIPTS -->
  <script>
    // 0. Auto-clean cached navigation items
    document.querySelectorAll('header nav a, #mobileMenu a').forEach(function(a) {
      if (a.textContent && a.textContent.trim().toLowerCase() === 'statistik publikasi') {
        var li = a.closest('li');
        if (li) li.remove(); else a.remove();
      }
    });

    // 1. Service Worker Registration
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('<?php echo esc_url( home_url( '/sw.js' ) ); ?>')
          .then(function(reg) {
            console.log('MTsN 1 Blitar PWA Service Worker Registered with scope: ', reg.scope);
          })
          .catch(function(err) {
            console.log('PWA Service Worker registration failed: ', err);
          });
      });
    }

    // 2. PWA BeforeInstallPrompt Handling
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferredPrompt = e;
      const banner = document.getElementById('pwaInstallBanner');
      if (banner) {
        banner.classList.remove('hidden');
        banner.style.display = 'flex';
      }
    });

    const installBtn = document.getElementById('pwaInstallBtn');
    if (installBtn) {
      installBtn.addEventListener('click', async () => {
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        console.log('User response to install prompt: ', outcome);
        deferredPrompt = null;
        document.getElementById('pwaInstallBanner').style.display = 'none';
      });
    }

    // 3. Instant Hover/Touch Link Preloader (Sub-second navigation)
    (function() {
      const prefetched = new Set();
      function prefetchUrl(url) {
        if (prefetched.has(url)) return;
        prefetched.add(url);
        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = url;
        document.head.appendChild(link);
      }

      function handleHover(e) {
        const a = e.target.closest('a');
        if (!a || !a.href) return;
        const url = a.href;
        // Only prefetch internal links
        if (url.startsWith('<?php echo esc_url( home_url() ); ?>') && !url.includes('#') && !url.includes('wp-admin') && !url.includes('wp-login')) {
          prefetchUrl(url);
        }
      }

      document.addEventListener('mouseover', handleHover, { passive: true });
      document.addEventListener('touchstart', handleHover, { passive: true });
    })();

    // 4. Smart Document Embed Enhancer (Full-page PDF & Google Drive Viewer)
    (function() {
      const proseContainers = document.querySelectorAll('.prose');
      if (!proseContainers.length) return;

      proseContainers.forEach(function(container) {
        const iframes = container.querySelectorAll('iframe');
        iframes.forEach(function(iframe) {
          const src = iframe.getAttribute('src') || '';
          const isDoc = src.includes('drive.google.com') ||
                        src.includes('docs.google.com') ||
                        src.includes('onedrive.live.com') ||
                        src.includes('office.com') ||
                        src.includes('.pdf');

          const isVideo = src.includes('youtube') ||
                          src.includes('youtu.be') ||
                          src.includes('vimeo') ||
                          src.includes('dailymotion');

          if (isDoc || (!isVideo && !src.includes('maps') && !src.includes('spotify') && !src.includes('soundcloud'))) {
            iframe.classList.add('document-embed-fullpage');
            iframe.setAttribute('allowfullscreen', 'true');
            iframe.setAttribute('loading', 'lazy');

            // If it's a document and doesn't already have a header bar
            if (isDoc && (!iframe.previousElementSibling || !iframe.previousElementSibling.classList.contains('document-viewer-header'))) {
              let viewUrl = src;
              if (src.includes('drive.google.com/file/d/')) {
                viewUrl = src.replace('/preview', '/view?usp=sharing');
              }

              const header = document.createElement('div');
              header.className = 'document-viewer-header';
              header.innerHTML = `
                <div class="flex items-center gap-2 text-slate-800 font-bold text-xs sm:text-sm">
                  <span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-file-pdf"></i>
                  </span>
                  <span>Dokumen Lampiran Resmi</span>
                </div>
                <div class="flex items-center gap-2">
                  <a href="${viewUrl}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-emerald-600 hover:text-white text-emerald-800 font-bold rounded-lg border border-slate-200 shadow-xs transition text-xs">
                    <span>Buka Layar Penuh / Unduh</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                  </a>
                </div>
              `;
              iframe.parentNode.insertBefore(header, iframe);
            }
          }
        });
      });
    })();
  </script>

<?php wp_footer(); ?>
</body>
</html>
