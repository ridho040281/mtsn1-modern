<?php
/**
 * The template for displaying single GTK (Guru & Tenaga Kependidikan) profiles
 */
get_header();
?>

<div class="py-10 bg-slate-100 min-h-screen">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <?php while ( have_posts() ) : the_post();
        $id = get_the_ID();
        $nip   = get_post_meta( $id, '_nip', true );
        $nik   = get_post_meta( $id, '_nik', true );
        $nuptk = get_post_meta( $id, '_nuptk', true );
        $mas   = get_post_meta( $id, '_mas', true );
        $ttl   = get_post_meta( $id, '_ttl', true );
        $tmpt  = get_post_meta( $id, '_tmpt', true );
        $tgl   = get_post_meta( $id, '_tgl', true );
        $sex   = get_post_meta( $id, '_sex', true );
        $rel   = get_post_meta( $id, '_rel', true );
        $pen   = get_post_meta( $id, '_pen', true );
        $stat  = get_post_meta( $id, '_stat', true );
        $jen   = get_post_meta( $id, '_jen', true );
        $jur   = get_post_meta( $id, '_jur', true );
        $add   = get_post_meta( $id, '_add', true );
        $telp  = get_post_meta( $id, '_telp', true );
        if ( ! $telp ) { $telp = get_post_meta( $id, '_waguru', true ); }
        $email = get_post_meta( $id, '_email', true );
        if ( ! $email ) { $email = get_post_meta( $id, '_mail', true ); }

        // Social Media
        $fb = get_post_meta( $id, '_madingfb', true );
        $tw = get_post_meta( $id, '_madingtw', true );
        $ig = get_post_meta( $id, '_madingig', true );

        // Education & Career History
        $edu_guru = get_post_meta( $id, 'edu_guru', true );
        $his_guru = get_post_meta( $id, 'his_guru', true );

        // Taxonomies
        $jab_terms   = get_the_terms( $id, 'jab' );
        $mapel_terms = get_the_terms( $id, 'mapel' );
        $stts_terms  = get_the_terms( $id, 'stts' );
        $tugas_terms = get_the_terms( $id, 'tugas' );

        $jab_name   = ( ! empty( $jab_terms ) && ! is_wp_error( $jab_terms ) ) ? $jab_terms[0]->name : ( $jen ? $jen : 'Guru / Pendidik' );
        $mapel_name = ( ! empty( $mapel_terms ) && ! is_wp_error( $mapel_terms ) ) ? $mapel_terms[0]->name : ( $jur ? $jur : '' );
        $stts_name  = ( ! empty( $stts_terms ) && ! is_wp_error( $stts_terms ) ) ? $stts_terms[0]->name : ( $stat ? $stat : 'Aktif' );
        $tugas_name = ( ! empty( $tugas_terms ) && ! is_wp_error( $tugas_terms ) ) ? $tugas_terms[0]->name : '';

        $thumb = get_the_post_thumbnail_url( $id, 'full' );
    ?>

      <!-- Breadcrumb -->
      <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-700">Beranda</a>
        <span>/</span>
        <a href="<?php echo esc_url( home_url( '/gtk' ) ); ?>" class="text-emerald-700 font-semibold hover:underline">Guru & Tenaga Kependidikan</a>
        <span>/</span>
        <span class="line-clamp-1 max-w-xs text-slate-400"><?php the_title(); ?></span>
      </nav>

      <!-- Main Profile Card -->
      <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

          <!-- Left Column: Photo, Badges & Contact -->
          <div class="lg:col-span-4 flex flex-col items-center text-center">
            <div class="w-48 h-60 sm:w-56 sm:h-72 rounded-3xl overflow-hidden bg-slate-100 border-4 border-emerald-600 shadow-xl relative group">
              <?php if ( $thumb ) : ?>
                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover">
              <?php else : ?>
                <div class="w-full h-full bg-gradient-to-b from-slate-100 to-slate-200 flex flex-col items-center justify-center text-slate-400">
                  <div class="w-24 h-24 rounded-full bg-white shadow-sm flex items-center justify-center text-4xl mb-2 text-emerald-700">
                    <i class="fa-solid fa-user-tie"></i>
                  </div>
                  <span class="text-xs font-bold text-slate-500">MTsN 1 Blitar</span>
                </div>
              <?php endif; ?>
              <div class="absolute top-3 right-3">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-600 text-white shadow-md">
                  <?php echo esc_html( $stts_name ); ?>
                </span>
              </div>
            </div>

            <!-- Position & Subject Badge -->
            <div class="mt-4 flex flex-wrap justify-center gap-1.5">
              <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                <?php echo esc_html( $jab_name ); ?>
              </span>
              <?php if ( $mapel_name && $mapel_name !== $jab_name ) : ?>
                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                  <?php echo esc_html( $mapel_name ); ?>
                </span>
              <?php endif; ?>
            </div>

            <!-- Quick Contact & Social Buttons -->
            <div class="mt-6 flex flex-col gap-2 w-full max-w-xs">
              <?php if ( $telp ) : ?>
                <a href="https://api.whatsapp.com/send?phone=<?php echo preg_replace('/[^0-9]/', '', $telp); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2.5 rounded-xl bg-green-500 hover:bg-green-600 text-white text-xs font-bold flex items-center justify-center gap-2 shadow-sm transition">
                  <i class="fa-brands fa-whatsapp text-sm"></i> Hubungi WhatsApp
                </a>
              <?php endif; ?>
              <?php if ( $email ) : ?>
                <a href="mailto:<?php echo esc_attr( $email ); ?>" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-bold flex items-center justify-center gap-2 border border-slate-200 transition">
                  <i class="fa-solid fa-envelope text-sm"></i> Kirim Email
                </a>
              <?php endif; ?>

              <?php if ( $fb || $tw || $ig ) : ?>
                <div class="flex items-center justify-center gap-2 pt-2">
                  <?php if ( $fb ) : ?>
                    <a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition text-xs">
                      <i class="fa-brands fa-facebook-f"></i>
                    </a>
                  <?php endif; ?>
                  <?php if ( $tw ) : ?>
                    <a href="<?php echo esc_url( $tw ); ?>" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-sky-50 text-sky-500 flex items-center justify-center hover:bg-sky-500 hover:text-white transition text-xs">
                      <i class="fa-brands fa-twitter"></i>
                    </a>
                  <?php endif; ?>
                  <?php if ( $ig ) : ?>
                    <a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-pink-50 text-pink-600 flex items-center justify-center hover:bg-pink-600 hover:text-white transition text-xs">
                      <i class="fa-brands fa-instagram"></i>
                    </a>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Right Column: Full Biodata -->
          <div class="lg:col-span-8 space-y-6">
            <div>
              <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Biodata Pendidik & Tenaga Kependidikan</span>
              <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1 leading-snug">
                <?php the_title(); ?>
              </h1>
              <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">
                MTsN 1 Blitar • Kementerian Agama Republik Indonesia
              </p>
            </div>

            <!-- Info Grid Table -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nomor Induk Pegawai (NIP)</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $nip ? $nip : '-' ); ?></p>
              </div>

              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">NUPTK</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $nuptk ? $nuptk : '-' ); ?></p>
              </div>

              <?php if ( $nik ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">NIK</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $nik ); ?></p>
              </div>
              <?php endif; ?>

              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jabatan / Amanah</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $jab_name ? $jab_name : 'Tenaga Pendidik' ); ?></p>
              </div>

              <?php if ( $mapel_name ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran (Mapel)</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $mapel_name ); ?></p>
              </div>
              <?php endif; ?>

              <?php if ( $mas ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mulai Mengabdi</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( date_i18n( 'd F Y', strtotime( $mas ) ) ); ?></p>
              </div>
              <?php endif; ?>

              <?php if ( $tugas_name ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 sm:col-span-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tugas Tambahan</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $tugas_name ); ?></p>
              </div>
              <?php endif; ?>

              <?php if ( $ttl || $tmpt || $tgl ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 sm:col-span-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tempat, Tanggal Lahir</span>
                <p class="text-sm font-black text-slate-800 mt-0.5">
                  <?php echo esc_html( $ttl ? $ttl : ( $tmpt . ( $tgl ? ', ' . date_i18n( 'd F Y', strtotime( $tgl ) ) : '' ) ) ); ?>
                </p>
              </div>
              <?php endif; ?>

              <?php if ( $sex ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jenis Kelamin</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $sex ); ?></p>
              </div>
              <?php endif; ?>

              <?php if ( $pen ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pendidikan Terakhir</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $pen ); ?></p>
              </div>
              <?php endif; ?>

              <?php if ( $rel ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Agama</span>
                <p class="text-sm font-black text-slate-800 mt-0.5"><?php echo esc_html( $rel ); ?></p>
              </div>
              <?php endif; ?>

              <?php if ( $add ) : ?>
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 sm:col-span-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alamat / Domisili</span>
                <p class="text-sm font-medium text-slate-700 mt-0.5"><?php echo esc_html( $add ); ?></p>
              </div>
              <?php endif; ?>
            </div>

            <!-- Riwayat Pendidikan (Educational History) -->
            <?php if ( ! empty( $edu_guru ) && is_array( $edu_guru ) ) : ?>
              <div class="mt-6 pt-6 border-t border-slate-200">
                <h4 class="text-sm font-black text-slate-900 mb-3 flex items-center gap-2">
                  <i class="fa-solid fa-graduation-cap text-emerald-600"></i> Riwayat Pendidikan
                </h4>
                <div class="divide-y divide-slate-100 rounded-2xl bg-slate-50 border border-slate-200 overflow-hidden">
                  <?php foreach ( $edu_guru as $edu ) : ?>
                    <div class="p-3 sm:px-4 flex items-center justify-between text-xs">
                      <span class="font-bold text-slate-800"><?php echo esc_html( isset( $edu['eduname'] ) ? $edu['eduname'] : '' ); ?></span>
                      <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-mono font-semibold"><?php echo esc_html( isset( $edu['eduyear'] ) ? $edu['eduyear'] : '' ); ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- Riwayat Karir / Pengalaman Kerja -->
            <?php if ( ! empty( $his_guru ) && is_array( $his_guru ) ) : ?>
              <div class="mt-6 pt-6 border-t border-slate-200">
                <h4 class="text-sm font-black text-slate-900 mb-3 flex items-center gap-2">
                  <i class="fa-solid fa-briefcase text-emerald-600"></i> Riwayat Karir & Pengalaman
                </h4>
                <div class="divide-y divide-slate-100 rounded-2xl bg-slate-50 border border-slate-200 overflow-hidden">
                  <?php foreach ( $his_guru as $his ) : ?>
                    <div class="p-3 sm:px-4 flex items-center justify-between text-xs">
                      <span class="font-bold text-slate-800"><?php echo esc_html( isset( $his['hisname'] ) ? $his['hisname'] : '' ); ?></span>
                      <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-mono font-semibold"><?php echo esc_html( isset( $his['hisyear'] ) ? $his['hisyear'] : '' ); ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- Additional content if exists -->
            <?php if ( get_the_content() ) : ?>
              <div class="mt-6 pt-6 border-t border-slate-200 prose max-w-none text-slate-700 text-sm">
                <?php the_content(); ?>
              </div>
            <?php endif; ?>

            <!-- Back to Directory Button -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3">
              <a href="<?php echo esc_url( home_url( '/gtk' ) ); ?>" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-xs font-bold text-slate-700 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Direktori Guru & GTK
              </a>
              <span class="text-xs text-slate-400">Pendidik Profesional MTsN 1 Blitar</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Other Teachers Cards -->
      <div class="mt-12">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
            <span class="w-2 h-5 rounded-full bg-emerald-600"></span> Profil Pendidik Lainnya
          </h3>
          <a href="<?php echo esc_url( home_url( '/gtk' ) ); ?>" class="text-xs font-bold text-emerald-700 hover:underline">Semua Guru &rarr;</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
          <?php
          $other_gtk = new WP_Query( array(
              'post_type'      => 'gtk',
              'posts_per_page' => 6,
              'post__not_in'   => array( $id ),
              'post_status'    => 'publish',
          ) );

          if ( $other_gtk->have_posts() ) :
              while ( $other_gtk->have_posts() ) : $other_gtk->the_post();
                  $o_thumb = get_the_post_thumbnail_url( get_the_ID(), 'mtsn1-avatar' );
                  $o_nip   = get_post_meta( get_the_ID(), '_nip', true );
                  ?>
                  <a href="<?php the_permalink(); ?>" class="bg-white rounded-2xl p-4 text-center border border-slate-200 hover:border-emerald-500 hover:shadow-md transition group block">
                    <div class="w-16 h-16 mx-auto rounded-full overflow-hidden bg-slate-100 border-2 border-emerald-500 mb-3 shadow-inner">
                      <?php if ( $o_thumb ) : ?>
                        <img src="<?php echo esc_url( $o_thumb ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover">
                      <?php else : ?>
                        <div class="w-full h-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-bold">
                          <i class="fa-solid fa-user-tie"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                    <h5 class="text-xs font-extrabold text-slate-900 group-hover:text-emerald-700 line-clamp-1"><?php the_title(); ?></h5>
                    <p class="text-[10px] text-slate-500 mt-0.5 line-clamp-1"><?php echo esc_html( $o_nip ? 'NIP. ' . $o_nip : 'Pendidik' ); ?></p>
                  </a>
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
