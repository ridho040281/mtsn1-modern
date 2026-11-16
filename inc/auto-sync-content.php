<?php
/**
 * Auto-sync konten berkala untuk sinkronisasi live server & local database
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'mtsn1_sync_atnawati_article' );
function mtsn1_sync_atnawati_article() {
    // Jalankan sekali saja dengan flag option
    if ( get_option( 'mtsn1_synced_atnawati_v1' ) ) {
        return;
    }

    global $wpdb;

    // Cari post Al-Qalam milik Dra. Atnawati di database
    $post_id = $wpdb->get_var( "
        SELECT p.ID FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_author_name'
        WHERE p.post_type = 'blog' 
          AND (
              p.ID = 8965 
              OR p.post_name = 'menumbuhkan-nalar-kritis-siswa-matematika-realistik'
              OR pm.meta_value LIKE '%Atnawati%'
              OR p.post_title LIKE '%Atnawati%'
              OR p.post_title LIKE '%Matematika Realistik%'
          )
        ORDER BY p.ID DESC LIMIT 1
    " );

    $title   = 'Cintai Profesimu, Dedikasikan Diri untuk Mencerdaskan Generasi Negeri';
    $slug    = 'cintai-profesimu-dedikasikan-diri-untuk-mencerdaskan-generasi-negeri';
    $excerpt = 'Guru yang mencintai profesinya akan mengajar dengan hati, mendidik dengan keteladanan, dan menginspirasi melalui tindakan. Dari guru yang bahagia dalam pengabdiannya, diharapkan tumbuh murid-murid yang bahagia dalam belajar, percaya diri dalam melangkah, dan siap menghadapi masa depan.';

    $content = '<blockquote class="my-6 p-5 sm:p-6 rounded-2xl bg-amber-50/80 border-l-4 border-amber-500 text-slate-800 text-base sm:text-lg italic font-medium leading-relaxed shadow-xs">
  &ldquo;Guru yang mencintai profesinya akan mengajar dengan hati, mendidik dengan keteladanan, dan menginspirasi melalui tindakan. Dari guru yang bahagia dalam pengabdiannya, diharapkan tumbuh murid-murid yang bahagia dalam belajar, percaya diri dalam melangkah, dan siap menghadapi masa depan.&rdquo;
</blockquote>

<p class="text-justify leading-relaxed"><strong>Mencintai profesi</strong> bukan sekadar menjalankan pekerjaan yang disukai, melainkan juga menemukan makna, kebahagiaan, dan kepuasan dalam setiap proses yang dijalani. Kecintaan terhadap profesi merupakan komitmen hati dan pikiran yang mendorong seseorang untuk terus berkembang, memberikan kontribusi terbaik, serta menghadirkan manfaat bagi lingkungan sekitarnya.</p>

<p class="text-justify leading-relaxed">Seseorang yang mencintai profesinya akan menjalankan tugas dengan penuh tanggung jawab dan semangat. Tantangan tidak lagi dipandang sebagai beban, tetapi sebagai kesempatan untuk belajar dan memperbaiki diri. Kecintaan terhadap pekerjaan juga dapat meningkatkan kinerja, menjaga kesejahteraan mental, serta mendorong lahirnya kreativitas dan inovasi.</p>

<p class="text-justify leading-relaxed">Bagi seorang guru, mencintai profesi memiliki makna yang jauh lebih mendalam. Menjadi guru bukan sekadar menjalankan pekerjaan untuk memenuhi kewajiban, melainkan panggilan jiwa dan amanah besar dalam membentuk generasi penerus bangsa. Guru berada di garda terdepan dalam menanamkan ilmu pengetahuan, membangun karakter, menumbuhkan nilai-nilai kebaikan, dan mengantarkan murid menuju masa depan yang lebih baik.</p>

<p class="text-justify leading-relaxed">Ketika seorang guru mencintai profesinya, ia tidak hanya mengajar dengan pengetahuan, tetapi juga mendidik dengan ketulusan, kesabaran, dan kasih sayang. Setiap murid memiliki karakter, kemampuan, dan kebutuhan belajar yang berbeda. Keberagaman tersebut menjadi tantangan sekaligus kesempatan bagi guru untuk terus berinovasi dalam menciptakan pembelajaran yang bermakna, menyenangkan, dan sesuai dengan kebutuhan murid.</p>

<p class="text-justify leading-relaxed">Bagi saya, profesi guru merupakan ladang pengabdian sekaligus kesempatan untuk menebarkan kebaikan. Ilmu yang diberikan, nasihat yang disampaikan, serta keteladanan yang ditunjukkan dapat meninggalkan jejak dalam kehidupan murid. Terkadang, hasil pendidikan tidak terlihat secara langsung. Namun, benih kebaikan yang ditanamkan hari ini dapat tumbuh menjadi karakter, perilaku, dan prestasi yang membanggakan pada masa mendatang.</p>

<p class="text-justify leading-relaxed">Mencintai profesi guru juga berarti menjaga niat, terus memperbaiki diri, dan tidak pernah berhenti belajar. Guru bukanlah sosok yang sempurna, melainkan pribadi yang bersedia bertumbuh bersama murid-muridnya. Guru yang baik bukan hanya membantu murid memahami pelajaran, tetapi juga memberikan ruang bagi mereka untuk mengenali potensi, membangun kepercayaan diri, dan berani meraih cita-cita.</p>

<p class="text-justify leading-relaxed">Kebahagiaan seorang guru tidak selalu diukur dari penghargaan atau pencapaian besar. Terkadang, senyum murid yang mulai memahami pelajaran, keberanian murid untuk mencoba, perubahan perilaku menjadi lebih baik, atau keberhasilan kecil yang mereka raih sudah menjadi kebahagiaan tersendiri. Momen-momen sederhana itulah yang mengingatkan bahwa profesi guru memiliki makna yang begitu istimewa.</p>

<p class="text-justify leading-relaxed">Oleh karena itu, mari kita cintai profesi yang kita jalani dengan sepenuh hati. Jalankan tugas dengan penuh tanggung jawab, rawat semangat untuk terus belajar, dan hadirkan ketulusan dalam setiap pengabdian. Sebab, pekerjaan yang dilakukan dengan cinta akan melahirkan dedikasi, kreativitas, dan semangat untuk memberikan yang terbaik.</p>

<p class="text-justify leading-relaxed">Mari cintai profesi kita, syukuri setiap kesempatan untuk mengabdi, dan terus tebarkan manfaat. Karena menjadi guru bukan hanya tentang apa yang kita ajarkan hari ini, tetapi juga tentang nilai-nilai kebaikan yang akan terus hidup dalam diri generasi masa depan.</p>';

    if ( $post_id ) {
        wp_update_post( array(
            'ID'           => $post_id,
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_excerpt' => $excerpt,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_date'    => '2026-11-03 08:00:00',
            'post_date_gmt'=> '2026-11-03 08:00:00',
        ) );
        update_post_meta( $post_id, '_author_name', 'Dra. Hj. Atnawati' );
        update_post_meta( $post_id, '_author_role', 'Guru Matematika MTsN 1 Blitar' );
        update_option( 'mtsn1_synced_atnawati_v1', 1 );
    } else {
        // Buat post baru jika belum ada
        $new_id = wp_insert_post( array(
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_excerpt' => $excerpt,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'blog',
            'post_date'    => '2026-11-03 08:00:00',
            'post_date_gmt'=> '2026-11-03 08:00:00',
        ) );
        if ( $new_id && ! is_wp_error( $new_id ) ) {
            update_post_meta( $new_id, '_author_name', 'Dra. Hj. Atnawati' );
            update_post_meta( $new_id, '_author_role', 'Guru Matematika MTsN 1 Blitar' );
            update_option( 'mtsn1_synced_atnawati_v1', 1 );
        }
    }
}
