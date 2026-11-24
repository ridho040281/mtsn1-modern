<?php
/**
 * The template for displaying single Agenda items
 * Theme: mtsn1-modern
 */
get_header();

while ( have_posts() ) : the_post();
    $pid      = get_the_ID();
    $sem      = get_post_meta( $pid, '_semester', true );
    $kat      = get_post_meta( $pid, '_kategori', true );
    $tevent   = get_post_meta( $pid, '_tevent', true );
    $tgl_teks = get_post_meta( $pid, '_tgl_teks', true );
    $jam      = get_post_meta( $pid, '_jam', true );
    $lokasi   = get_post_meta( $pid, '_lokasi', true );
    $icon     = get_post_meta( $pid, '_icon', true );
    $badge    = get_post_meta( $pid, '_badge', true );
    $contper  = get_post_meta( $pid, '_contper', true );
    $contwa   = get_post_meta( $pid, '_contwa', true );

    $tevent_ts = $tevent ? strtotime( $tevent ) : false;
    $month_str = $tevent_ts ? date_i18n( 'F', $tevent_ts ) : '';
    $day_str   = $tevent_ts ? date_i18n( 'd', $tevent_ts ) : '';
    $year_str  = $tevent_ts ? date_i18n( 'Y', $tevent_ts ) : '2026';
    $day_name  = $tevent_ts ? date_i18n( 'l', $tevent_ts ) : '';

    $display_date = $tgl_teks ? $tgl_teks : ( $tevent_ts ? ( $day_name . ', ' . $day_str . ' ' . $month_str . ' ' . $year_str ) : 'Sesuai Jadwal' );
    $display_jam  = ( $jam && $jam !== '00:00' ) ? $jam . ' WIB' : 'Menyesuaikan Jadwal';
    $display_loc  = $lokasi ? $lokasi : 'Madrasah MTsN 1 Blitar';
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 flex-wrap">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
      <span>/</span>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'agenda' ) ? get_post_type_archive_link( 'agenda' ) : home_url( '/agenda' ) ); ?>" class="text-emerald-700 font-semibold hover:underline">
        Agenda & Kalender Akademik
      </a>
      <span>/</span>
      <span class="line-clamp-1 max-w-xs text-slate-400"><?php the_title(); ?></span>
    </nav>

    <!-- Main Card -->
    <article class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200 overflow-hidden relative">

      <!-- Top Badges & Meta -->
      <div class="flex flex-wrap items-center gap-2.5 mb-6">
        <?php if ( $sem ) : ?>
          <span class="px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-black uppercase tracking-wider">
            <?php echo esc_html( $sem ); ?>
          </span>
        <?php endif; ?>

        <?php if ( $kat ) : ?>
          <span class="px-3.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider <?php echo esc_attr( $badge ? $badge : 'bg-slate-100 text-slate-700' ); ?>">
            <i class="fa-solid <?php echo esc_attr( $icon ? $icon : 'fa-calendar-check' ); ?> mr-1"></i><?php echo esc_html( $kat ); ?>
          </span>
        <?php endif; ?>

        <span class="text-xs text-slate-400 flex items-center gap-1 ml-auto">
          <i class="fa-solid fa-graduation-cap text-emerald-600"></i> TA 2026/2027
        </span>
      </div>

      <!-- Title -->
      <h1 class="text-2xl sm:text-4xl font-black text-slate-900 leading-tight mb-8">
        <?php the_title(); ?>
      </h1>

      <!-- Event Details Highlight Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        
        <!-- Tanggal -->
        <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-emerald-50/70 to-teal-50/40 border border-emerald-100/80 flex items-start gap-3.5">
          <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
            <i class="fa-regular fa-calendar-check"></i>
          </div>
          <div class="min-w-0">
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">Tanggal Pelaksanaan</span>
            <p class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 leading-snug">
              <?php echo esc_html( $display_date ); ?>
            </p>
          </div>
        </div>

        <!-- Waktu -->
        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-start gap-3.5">
          <div class="w-11 h-11 rounded-xl bg-teal-600 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
            <i class="fa-regular fa-clock"></i>
          </div>
          <div class="min-w-0">
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">Waktu / Jam</span>
            <p class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 leading-snug">
              <?php echo esc_html( $display_jam ); ?>
            </p>
          </div>
        </div>

        <!-- Lokasi -->
        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-start gap-3.5 sm:col-span-2 md:col-span-1">
          <div class="w-11 h-11 rounded-xl bg-amber-600 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <div class="min-w-0">
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">Lokasi / Tempat</span>
            <p class="text-xs sm:text-sm font-black text-slate-900 mt-0.5 leading-snug">
              <?php echo esc_html( $display_loc ); ?>
            </p>
          </div>
        </div>

      </div>

      <!-- Optional Featured Image Dinamis (Tanpa Terpotong) -->
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="rounded-2xl overflow-hidden mb-8 shadow-sm border border-slate-200/80 bg-slate-900/5 flex items-center justify-center">
          <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto max-h-[640px] object-contain rounded-2xl block mx-auto' ) ); ?>
        </div>
      <?php endif; ?>

      <!-- Description Content -->
      <div class="prose max-w-none text-slate-700 text-sm sm:text-base leading-relaxed pt-2 border-t border-slate-100">
        <h3 class="text-lg font-black text-slate-900 mb-3 flex items-center gap-2">
          <i class="fa-solid fa-circle-info text-emerald-600"></i> Keterangan & Rincian Agenda
        </h3>
        <?php the_content(); ?>
      </div>

      <?php if ( $contper || $contwa ) : ?>
        <!-- Contact Person Box -->
        <div class="mt-8 p-4 sm:p-5 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-base">
              <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
              <span class="text-[10px] uppercase font-bold text-amber-800">Koordinator Kegiatan</span>
              <p class="text-xs sm:text-sm font-black text-slate-900"><?php echo esc_html( $contper ? $contper : 'Panitia Madrasah' ); ?></p>
            </div>
          </div>
          <?php if ( $contwa ) : ?>
            <a href="https://wa.me/<?php echo esc_attr( preg_replace('/[^0-9]/', '', $contwa) ); ?>" target="_blank" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
              <i class="fa-brands fa-whatsapp"></i> Hubungi WA
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <!-- Share & Action Bar -->
      <div class="mt-10 pt-6 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
          <span>Bagikan Jadwal:</span>
          <a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_permalink() ); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition shadow-xs" title="WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
          </a>
          <button type="button" onclick="window.print()" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
            <i class="fa-solid fa-print"></i> Cetak
          </button>
        </div>

        <a href="<?php echo esc_url( get_post_type_archive_link( 'agenda' ) ? get_post_type_archive_link( 'agenda' ) : home_url( '/agenda' ) ); ?>" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-xs font-bold text-slate-700 transition flex items-center gap-1.5">
          <i class="fa-solid fa-arrow-left text-[10px]"></i> Semua Kalender Agenda
        </a>
      </div>

    </article>

    <!-- Other Upcoming Agendas (3 Cards) -->
    <div class="mt-12">
      <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
        <span class="w-2 h-5 rounded-full bg-emerald-600"></span> Agenda Terkait & Mendatang Lainnya
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php
        $related_agenda_q = new WP_Query( array(
            'post_type'      => 'agenda',
            'posts_per_page' => 3,
            'post__not_in'   => array( get_the_ID() ),
            'post_status'    => 'publish',
            'meta_key'       => '_tevent',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
        ) );

        if ( $related_agenda_q->have_posts() ) :
            while ( $related_agenda_q->have_posts() ) : $related_agenda_q->the_post();
                $r_tevent   = get_post_meta( get_the_ID(), '_tevent', true );
                $r_tgl_teks = get_post_meta( get_the_ID(), '_tgl_teks', true );
                $r_jam      = get_post_meta( get_the_ID(), '_jam', true );
                $r_lokasi   = get_post_meta( get_the_ID(), '_lokasi', true );
                $r_kategori = get_post_meta( get_the_ID(), '_kategori', true );
                $r_semester = get_post_meta( get_the_ID(), '_semester', true );

                $r_m = $r_tevent ? date_i18n( 'M', strtotime( $r_tevent ) ) : '';
                $r_d = $r_tevent ? date_i18n( 'd', strtotime( $r_tevent ) ) : '';
                ?>
                <a href="<?php the_permalink(); ?>" class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200 hover:border-emerald-500 hover:shadow-lg transition group flex flex-col justify-between">
                  <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                      <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex flex-col items-center justify-center font-bold">
                        <span class="text-[9px] uppercase leading-none"><?php echo esc_html( $r_m ); ?></span>
                        <span class="text-base font-black leading-none mt-0.5"><?php echo esc_html( $r_d ); ?></span>
                      </div>
                      <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                        <?php echo esc_html( $r_kategori ? $r_kategori : 'Agenda' ); ?>
                      </span>
                    </div>

                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2 leading-snug">
                      <?php the_title(); ?>
                    </h4>

                    <?php if ( $r_tgl_teks ) : ?>
                      <p class="text-[11px] font-semibold text-amber-700 mt-1 line-clamp-1">
                        <i class="fa-regular fa-calendar-days text-[9px] mr-1"></i><?php echo esc_html( $r_tgl_teks ); ?>
                      </p>
                    <?php endif; ?>
                  </div>

                  <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span class="truncate"><i class="fa-solid fa-location-dot text-emerald-600 mr-1"></i><?php echo esc_html( $r_lokasi ? $r_lokasi : 'Madrasah' ); ?></span>
                    <span class="text-emerald-700 font-bold ml-2">Lihat &rarr;</span>
                  </div>
                </a>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
      </div>
    </div>

  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
