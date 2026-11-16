<?php
/**
 * Auto-sync konten Al-Qalam (Ruang Literasi GTK MTsN 1 Blitar)
 * Sinkronisasi otomatis ke database (Local & Live Hosting)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'mtsn1_sync_all_alqalam_articles' );
function mtsn1_sync_all_alqalam_articles() {
    // Flag versi sinkronisasi
    $current_version = 'v2_alqalam_series';
    if ( get_option( 'mtsn1_alqalam_sync_version' ) === $current_version ) {
        return;
    }

    $articles = array(
        // -------------------------------------------------------------
        // EDISI 0002: Nurul Faridha, S. Pd. (25 Agustus 2025)
        // -------------------------------------------------------------
        array(
            'slug'         => 'implementasi-biologi-wujudkan-mtsn1-blitar-berwawasan-lingkungan',
            'old_slugs'    => array( 'membiasakan-literasi-menulis-kreatif-madrasah' ),
            'title'        => 'Implementasi Biologi untuk Mewujudkan MTSN 1 Blitar Berwawasan Lingkungan',
            'date'         => '2025-08-25 08:00:00',
            'author_name'  => 'Nurul Faridha, S. Pd.',
            'author_role'  => 'Guru Mapel IPA MTsN 1 Blitar',
            'author_photo' => '/wp-content/uploads/2026/11/gtk-nurul-faridha.webp',
            'flyer_file'   => 'al-qalam-0002-nurul-faridha.jpg',
            'cat_slug'     => 'praktik-baik',
            'excerpt'      => 'Biologi di MTsN 1 Blitar tidak hanya diajarkan di kelas, melainkan diaktualisasikan dalam kehidupan sehari-hari: greenhouse anggrek, budidaya burung merpati, kolam perikanan, biopori kompos, hingga tanaman apotik hidup.',
            'quote'        => 'Biologi memiliki peran penting dalam kehidupan di madrasah karena ilmu ini dapat diaplikasikan secara langsung dalam berbagai kegiatan belajar maupun pembiasaan sehari-hari.',
            'content'      => '<p class="text-justify leading-relaxed"><strong>Biologi</strong> memiliki peran penting dalam kehidupan di madrasah karena ilmu ini dapat diaplikasikan secara langsung dalam berbagai kegiatan belajar maupun pembiasaan sehari-hari. Melalui pembelajaran biologi, siswa memahami konsep dasar tentang makhluk hidup, kesehatan, dan lingkungan, yang kemudian dapat diaktualisasikan dalam perilaku nyata.</p>

<p class="text-justify leading-relaxed">Di MTsN 1 Blitar, biologi tidak hanya diajarkan di kelas tetapi juga diwujudkan dalam kehidupan sehari-hari. Siswa diajak ikut serta dalam pengelolaan sampah, pelatihan pertanian mini, program penghijauan, kesehatan, dan pengamatan lingkungan yang nyata.</p>

<p class="text-justify leading-relaxed">Dalam kegiatan madrasah, siswa mempraktikkan pengetahuan tentang fotosintesis, siklus hidup tumbuhan, serta teknik budidaya yang baik. Melalui ekstrakurikuler seperti pramuka konsep ekologi dan konservasi dapat diaplikasikan dalam kegiatan penanaman pohon, pengelolaan sampah organik menjadi kompos, dan menjaga kelestarian lingkungan sekitar madrasah.</p>

<p class="text-justify leading-relaxed">Dengan demikian, biologi tidak hanya dipelajari sebagai teori di kelas tetapi benar-benar menjadi panduan perilaku sehat, peduli lingkungan, dan berkelanjutan dalam kehidupan madrasah. Berikut merupakan kegiatan aktualisasi biologi di MTsN 1 Blitar:</p>

<div class="my-6 space-y-3 bg-slate-50 p-6 rounded-2xl border border-slate-200">
  <p class="leading-relaxed"><strong>1. Bidang Pertanian:</strong> Greenhouse yang digunakan merawat bunga-bunga anggrek dan bunga lain yang memerlukan perawatan intensif dan juga penanaman tumbuhan, buah-buahan. Selain sebagai tanaman pelindung juga bisa dinikmati sebagai buah musiman.</p>
  <p class="leading-relaxed"><strong>2. Bidang Peternakan:</strong> Ada burung merpati yang awalnya hanya 2 pasang, sekarang sudah puluhan bahkan sudah pernah dijual ataupun dinikmati bersama.</p>
  <p class="leading-relaxed"><strong>3. Bidang Kedokteran:</strong> Banyak diterapkan oleh anak-anak yang tergabung dalam ekstrakurikuler PMR dalam berbagai lomba dan juga ada UKS tempat merawat anak-anak yang ada masalah kesehatan saat di madrasah.</p>
  <p class="leading-relaxed"><strong>4. Bidang Perikanan:</strong> Ada satu kolam yang didalamnya dipelihara ikan nila, yang sudah berkembangbiak banyak sekali, bisa dinikmati bersama saat tertentu.</p>
  <p class="leading-relaxed"><strong>5. Bidang Pengelolaan Lingkungan:</strong> Banyak titik dibuat biopori digunakan peresapan air dan membuat kompos, yang secara berkala bisa dipanen untuk memberikan pupuk banyak tanaman yang ada.</p>
  <p class="leading-relaxed"><strong>6. Bidang Farmasi:</strong> Tanaman empon-empon yang ditanam misalnya jahe, kunir dan kencur bisa digunakan sebagai apotik hidup.</p>
</div>'
        ),

        // -------------------------------------------------------------
        // EDISI 0003: Siti Khodijah, S. Pd. (28 Agustus 2025)
        // -------------------------------------------------------------
        array(
            'slug'         => 'mengajar-bukan-sekadar-rumus-tetapi-juga-nilai-hidup',
            'old_slugs'    => array(),
            'title'        => 'Mengajar Bukan Sekadar Rumus Tetapi Juga Nilai Hidup',
            'date'         => '2025-08-28 08:00:00',
            'author_name'  => 'Siti Khodijah, S. Pd.',
            'author_role'  => 'Guru Mapel Matematika MTsN 1 Blitar',
            'author_photo' => '/wp-content/uploads/2026/11/gtk-siti-khodijah.webp',
            'flyer_file'   => 'al-qalam-0003-siti-khodijah.jpg',
            'cat_slug'     => 'opini-guru',
            'excerpt'      => 'Pesan saya kepada generasi guru berikutnya adalah jangan pernah lelah berbagi ilmu. Profesi guru memang penuh tantangan, tetapi juga sarat kebahagiaan.',
            'quote'        => 'Pesan saya kepada generasi guru berikutnya adalah jangan pernah lelah berbagi ilmu. Profesi guru memang penuh tantangan, tetapi juga sarat kebahagiaan.',
            'content'      => '<p class="text-justify leading-relaxed"><strong>Menjadi seorang guru</strong> merupakan sebuah perjalanan panjang yang penuh dengan lika-liku. Sejak Juli 1989, saya memilih jalan ini dengan niat untuk berbagi ilmu dan mendidik generasi penerus bangsa. Selama lebih dari tiga dekade, profesi ini telah memberikan banyak pengalaman-pengalaman berharga yang tidak ternilai.</p>

<p class="text-justify leading-relaxed">Sebagai seorang guru Matematika, saya memiliki tanggung jawab utama dalam membimbing siswa untuk dapat memahami angka, rumus, maupun logika. Matematika sering kali dianggap sebagai mata pelajaran yang sangat sulit oleh sebagian siswa, namun justru di situlah tantangannya. Saya merasa terpanggil untuk membantu mereka melihat bahwa setiap soal memiliki jalan penyelesaian, sebagaimana setiap masalah dalam hidup selalu ada solusi.</p>

<p class="text-justify leading-relaxed">Kesan yang paling menyenangkan selama mengajar adalah ketika melihat wajah siswa yang akhirnya memahami materi setelah berusaha keras dalam memecahkannya. Momen kecil itu selalu memberi kebahagiaan tersendiri. Rasanya seperti menyaksikan cahaya baru lahir dalam diri mereka.</p>

<p class="text-justify leading-relaxed">Namun, perjalanan ini tidak selalu mudah. Ada masa-masa sulit, ketika siswa kehilangan motivasi atau merasa putus asa menghadapi Matematika. Di saat seperti itu, saya belajar untuk lebih sabar, menenangkan, dan memberikan dorongan agar mereka tidak menyerah. Guru bukan hanya pengajar, tetapi juga motivator bagi para siswa-siswanya.</p>

<p class="text-justify leading-relaxed">Selain mengajarkan Matematika, saya selalu berusaha menanamkan nilai-nilai agama. Saya percaya bahwa ilmu tanpa akhlak akan kehilangan arah. Oleh karena itu, saya menyelipkan pesan moral, kedisiplinan, serta sikap tawakal dalam setiap kesempatan. Dengan begitu, siswa tidak hanya cerdas secara akademik, tetapi juga kuat secara spiritual.</p>

<p class="text-justify leading-relaxed">Bagi saya, mengajar adalah seni sekaligus sebuah ibadah. Seni, karena guru perlu kreatif dalam menyusun metode agar pelajaran mudah dipahami dan menyenangkan. Ibadah, karena setiap ilmu yang disampaikan dengan ikhlas akan menjadi amal jariyah yang pahalanya terus mengalir. Inilah motivasi terbesar saya untuk tetap setia dalam profesi ini.</p>

<p class="text-justify leading-relaxed">Selama bertahun-tahun, saya menyadari bahwa setiap siswa memiliki keunikannya masing-masing. Ada yang cepat memahami maupun ada pula yang membutuhkan bimbingan lebih lama. Hal ini mengajarkan saya arti kesabaran, keikhlasan, dan penghargaan terhadap perbedaan. Seorang guru harus mampu menyesuaikan diri dengan karakter murid-muridnya.</p>

<p class="text-justify leading-relaxed">Pesan saya kepada generasi guru berikutnya adalah jangan pernah lelah berbagi ilmu. Profesi guru memang penuh tantangan, tetapi juga sarat kebahagiaan. Setiap kata yang kita ajarkan, setiap nilai yang kita tanamkan, akan menjadi bekal yang melekat pada diri siswa sepanjang hidupnya. Guru sejati bukan hanya memberikan pelajaran, melainkan juga bersedia belajar dari murid-muridnya.</p>

<p class="text-justify leading-relaxed">Akhirnya, saya bersyukur telah menjalani perjalanan panjang sebagai guru Matematika sejak 1989. Kesan yang tertinggal adalah rasa bangga sekaligus haru, karena bisa menjadi bagian dari proses tumbuhnya generasi muda. Semoga setiap langkah kecil ini bernilai di sisi Allah, dan ilmu yang dibagikan terus memberi manfaat bagi kehidupan banyak orang.</p>'
        ),

        // -------------------------------------------------------------
        // EDISI 0004: Drs. Dwi Santoso, M.Si. (4 September 2025)
        // -------------------------------------------------------------
        array(
            'slug'         => 'jejak-langkah-di-ruang-kelas-catatan-reflektif-pendidik',
            'old_slugs'    => array(),
            'title'        => 'Jejak Langkah di Ruang Kelas: Catatan Reflektif Seorang Pendidik',
            'date'         => '2025-09-04 08:00:00',
            'author_name'  => 'Drs. Dwi Santoso, M.Si.',
            'author_role'  => 'Guru Mapel Matematika MTsN 1 Blitar',
            'author_photo' => '/wp-content/uploads/2026/11/gtk-dwi-santoso.webp',
            'flyer_file'   => 'al-qalam-0004-dwi-santoso.jpg',
            'cat_slug'     => 'praktik-baik',
            'excerpt'      => 'Guru bukan hanya penyampai materi, tetapi juga fasilitator, motivator, sekaligus pembimbing yang harus mampu menuntun siswa menjadi pembelajar sepanjang hayat.',
            'quote'        => 'Guru bukan hanya penyampai materi, tetapi juga fasilitator, motivator, sekaligus pembimbing yang harus mampu menuntun siswa menjadi pembelajar sepanjang hayat.',
            'content'      => '<p class="text-justify leading-relaxed"><strong>Matematika</strong> merupakan salah satu mata pelajaran penting di tingkat sekolah menengah pertama. Peranannya sangat besar, baik dalam kehidupan sehari-hari, pembentukan pribadi siswa, maupun perkembangan ilmu pengetahuan dan teknologi.</p>

<p class="text-justify leading-relaxed">Dengan peranan yang demikian besar, guru matematika dituntut memiliki kemampuan memilih metode mengajar yang tepat, menerapkan teori belajar, melibatkan siswa secara aktif, serta menciptakan suasana belajar yang mendukung tercapainya tujuan pembelajaran. Hal ini penting agar siswa menyadari nilai matematika dalam kehidupan.</p>

<p class="text-justify leading-relaxed">Pada kenyataannya, manusia tidak dapat terlepas dari matematika. Dalam rumah tangga, masyarakat, hingga kehidupan bernegara, matematika selalu hadir. Suka atau tidak suka, kita harus mempelajarinya. Bagi siswa yang kurang menyukai matematika, perlu adanya usaha sungguh-sungguh agar merasa lebih nyaman. Dengan belajar secara tenang dan menyenangkan, hasil pembelajaran pun akan lebih optimal.</p>

<p class="text-justify leading-relaxed">Namun, mengajar matematika juga memiliki tantangan tersendiri. Salah satunya adalah menjelaskan konsep abstrak yang sulit dipahami siswa. Tekanan pencapaian target akademis yang tinggi sering kali membuat guru mengalami stres dan kelelahan.</p>

<p class="text-justify leading-relaxed">Pada era globalisasi, dinamika kehidupan semakin ketat, termasuk dalam bidang pendidikan. Dengan semakin tingginya tingkat pendidikan dan kondisi sosial ekonomi masyarakat, kebutuhan terhadap pengetahuan juga meningkat. Penting bagi guru membangun komunikasi yang baik dengan orang tua dan lingkungan sekitar. Dukungan keluarga dalam membiasakan anak belajar di rumah serta menciptakan suasana akademik di lingkungan sangat membantu proses pembelajaran di sekolah. Kolaborasi antara sekolah dan keluarga menjadi fondasi penting agar siswa memiliki semangat belajar yang konsisten.</p>

<p class="text-justify leading-relaxed">Pengalaman mengajar juga mengajarkan bahwa setiap siswa memiliki karakter belajar yang berbeda. Ada yang cepat memahami penjelasan, ada yang membutuhkan waktu lebih lama, bahkan ada yang perlu pendekatan personal. Guru dituntut untuk sabar, kreatif, dan adaptif dalam menemukan strategi yang sesuai bagi masing-masing siswa agar mereka dapat berkembang sesuai potensinya.</p>

<p class="text-justify leading-relaxed">Kedepan, tantangan pendidikan akan semakin kompleks seiring perkembangan teknologi dan informasi. Guru bukan hanya penyampai materi, tetapi juga fasilitator, motivator, sekaligus pembimbing yang harus mampu menuntun siswa menjadi pembelajar sepanjang hayat. Refleksi ini menyadarkan saya bahwa tugas pendidik bukan sekadar mencetak nilai akademis, melainkan membentuk manusia yang berpikir kritis, berakhlak, dan siap menghadapi perubahan zaman.</p>'
        ),

        // -------------------------------------------------------------
        // EDISI 0005: Drs. Achmad Saechoni, M.M (20 Oktober 2026)
        // -------------------------------------------------------------
        array(
            'slug'         => 'inspirasi-menjadi-guru-berdakwah-mengabdi-menebar-manfaat',
            'old_slugs'    => array(),
            'title'        => 'INSPIRASI MENJADI GURU: Berdakwah, Mengabdi, dan Menebar Manfaat',
            'date'         => '2026-10-20 08:00:00',
            'author_name'  => 'Drs. Achmad Saechoni, M.M',
            'author_role'  => 'Guru Mapel Bahasa Inggris MTsN 1 Blitar',
            'author_photo' => '/wp-content/uploads/2026/11/gtk-achmad-saechoni.webp',
            'flyer_file'   => 'al-qalam-0005-achmad-saechoni.jpg',
            'cat_slug'     => 'opini-guru',
            'excerpt'      => 'Guru tidak hanya mengajar untuk hari ini, tetapi juga ikut mempersiapkan kehidupan peserta didik di masa depan. Menjadi guru adalah jalan berdakwah, mengabdi, dan menebar manfaat.',
            'quote'        => 'Guru tidak hanya mengajar untuk hari ini, tetapi juga ikut mempersiapkan kehidupan peserta didik di masa depan.',
            'content'      => '<p class="text-justify leading-relaxed"><strong>Menjadi seorang guru</strong> bagi saya bukan sekadar menjalankan sebuah profesi, tetapi juga merupakan bentuk pengabdian dan kesempatan untuk memberikan manfaat kepada orang lain. Saya Saechoni, salah satu guru di MTsN 1 Blitar yang mengampu mata pelajaran Bahasa Inggris.</p>

<p class="text-justify leading-relaxed">Saya telah mengabdikan diri di dunia pendidikan selama puluhan tahun. Sebelum bertugas di MTsN 1 Blitar, saya mengajar di MAN 1 Blitar sejak tahun 2008. Kemudian, pada tahun 2021, saya mendapatkan amanah untuk melanjutkan pengabdian di MTsN 1 Blitar.</p>

<p class="text-justify leading-relaxed">Ketika berbicara mengenai inspirasi menjadi guru, menurut saya setiap orang memiliki motivasi yang berbeda. Ada yang menjadi guru karena ingin berdakwah, mengabdi, mengembangkan diri, ataupun menjadikannya sebagai pekerjaan. Daya tarik dan tujuan setiap manusia memang tidaklah sama.</p>

<p class="text-justify leading-relaxed">Saya pribadi ingin menjadi guru karena ingin berdakwah dan mengabdi. Bagi saya, menjadi guru merupakan salah satu jalan untuk menyampaikan ilmu sekaligus menanamkan nilai-nilai kebaikan kepada peserta didik.</p>

<p class="text-justify leading-relaxed">Dalam proses pembelajaran, saya tidak hanya ingin menyampaikan materi pelajaran, tetapi juga berusaha memberikan pendidikan yang dapat membentuk karakter peserta didik. Saya percaya bahwa ilmu yang diberikan kepada peserta didik akan lebih bermakna apabila disertai dengan keteladanan, sikap, dan kepedulian.</p>

<p class="text-justify leading-relaxed">Selama puluhan tahun mengajar, saya menyadari bahwa setiap peserta didik memiliki karakter, kemampuan, dan latar belakang yang berbeda. Perbedaan tersebut menjadi tantangan bagi saya untuk terus belajar dan mencari cara terbaik agar pembelajaran dapat diterima oleh setiap peserta didik.</p>

<p class="text-justify leading-relaxed">Bagi saya, mengajar bukan hanya tentang menyampaikan ilmu, tetapi juga tentang mendampingi, membimbing, dan memberikan teladan. Ada kepuasan tersendiri ketika melihat peserta didik berkembang, memiliki akhlak yang baik, serta mampu menerapkan ilmu yang telah dipelajari dalam kehidupan sehari-hari.</p>

<p class="text-justify leading-relaxed">Perjalanan saya sebagai guru sejak tahun 2008 hingga saat ini tentu memberikan banyak pengalaman dan pelajaran berharga. Perpindahan tugas dari MAN 1 Blitar ke MTsN 1 Blitar pada tahun 2021 semakin memperkaya pengalaman saya dalam mendampingi peserta didik dengan karakter dan kebutuhan yang beragam.</p>

<p class="text-justify leading-relaxed">Saya menyadari bahwa menjadi guru merupakan sebuah amanah. Karena itu, saya berusaha menjalankan tugas ini dengan penuh tanggung jawab, kesabaran, dan keikhlasan. Semoga setiap ilmu yang saya sampaikan, setiap nasihat yang saya berikan, dan setiap kebaikan yang saya lakukan dapat menjadi amal jariyah serta memberikan manfaat bagi peserta didik.</p>

<p class="text-justify leading-relaxed">Dalam pembelajaran Bahasa Inggris misalnya, saya tidak hanya berusaha agar peserta didik memahami kosakata, tata bahasa, membaca, berbicara, atau menulis. Saya juga ingin mereka memiliki keberanian untuk berkomunikasi, percaya diri dalam menyampaikan pendapat, serta memiliki semangat untuk terus belajar. Bahasa adalah alat komunikasi. Karena itu, belajar bahasa seharusnya juga menjadi proses membangun keberanian dan keterbukaan.</p>

<p class="text-justify leading-relaxed">Ada banyak bentuk kebahagiaan dalam profesi guru. Salah satunya adalah ketika melihat peserta didik berkembang. Ketika seorang siswa yang awalnya tidak percaya diri kemudian berani berbicara di depan kelas, ketika siswa yang sebelumnya kesulitan akhirnya mampu memahami materi, ketika mereka berhasil mencapai cita-citanya, atau ketika seorang mantan peserta didik datang dan masih mengingat gurunya—semua itu merupakan kebahagiaan tersendiri.</p>

<p class="text-justify leading-relaxed">Bagi saya, keberhasilan seorang guru tidak selalu diukur dari penghargaan yang diterima. Terkadang keberhasilan itu hadir dalam bentuk sederhana: melihat peserta didik tumbuh menjadi manusia yang baik, bertanggung jawab, memiliki akhlak, dan mampu memberikan manfaat bagi orang lain.</p>'
        ),

        // -------------------------------------------------------------
        // EDISI 0006: MOHAMAD JAMIL, S.Pd. (27 Oktober 2026)
        // -------------------------------------------------------------
        array(
            'slug'         => 'siapa-bilang-membosankan-keseruan-matematika-sehari-hari',
            'old_slugs'    => array(),
            'title'        => 'SIAPA BILANG MEMBOSANKAN !!! Temukan Keseruan Matematika dalam Kehidupan Sehari-hari!',
            'date'         => '2026-10-27 08:00:00',
            'author_name'  => 'MOHAMAD JAMIL, S.Pd.',
            'author_role'  => 'Guru Mapel Matematika MTsN 1 Blitar',
            'author_photo' => '/wp-content/uploads/2026/11/gtk-mohamad-jamil.webp',
            'flyer_file'   => 'al-qalam-0006-mohamad-jamil.jpg',
            'cat_slug'     => 'praktik-baik',
            'excerpt'      => 'Yuk, kita ubah anggapan bahwa matematika itu sulit menjadi matematika itu seru! Mari belajar matematika dengan penuh semangat, temukan keseruannya, dan rasakan manfaatnya dalam kehidupan.',
            'quote'        => 'Yuk, kita ubah anggapan bahwa matematika itu sulit menjadi matematika itu seru! Mari belajar matematika dengan penuh semangat, temukan keseruannya, dan rasakan manfaatnya dalam kehidupan sehari-hari.',
            'content'      => '<p class="text-justify leading-relaxed"><strong>Matematika</strong> sering kali dianggap sebagai mata pelajaran yang sulit dan kurang diminati oleh sebagian murid. Padahal, matematika merupakan ilmu penting yang tidak hanya dipelajari di bangku sekolah, tetapi juga diterapkan dalam berbagai aspek kehidupan sehari-hari. Untuk mengubah pandangan tersebut, penting bagi murid untuk mengenal matematika lebih dekat dan memahami manfaatnya melalui kegiatan belajar yang menyenangkan.</p>

<p class="text-justify leading-relaxed">Matematika merupakan salah satu ilmu eksakta yang memiliki konsep, rumus, dan prinsip yang dapat dibuktikan melalui proses berpikir logis dan sistematis. Ilmu ini menjadi dasar bagi berbagai bidang pengetahuan, seperti fisika, ekonomi, teknologi, bahkan kesehatan. Oleh karena itu, kemampuan matematika sangat diperlukan untuk membantu seseorang memahami persoalan dan menemukan solusi yang tepat.</p>

<p class="text-justify leading-relaxed">Ada pepatah mengatakan, "Tak kenal maka tak sayang." Begitu pula dengan matematika. Ketika murid mulai mengenal manfaat dan penerapannya, matematika tidak lagi sekadar kumpulan angka dan rumus, tetapi menjadi ilmu yang menarik untuk dipelajari.</p>

<h4 class="text-lg font-bold text-slate-900 mt-6 mb-2">Lalu, apa saja manfaat belajar matematika?</h4>

<p class="text-justify leading-relaxed"><strong>Pertama, melatih pola pikir sistematis.</strong> Dalam menyelesaikan soal matematika, murid perlu mengikuti langkah-langkah yang runtut dan teratur. Kebiasaan ini membantu murid berpikir lebih terstruktur ketika menghadapi berbagai persoalan, baik dalam pembelajaran maupun kehidupan sehari-hari.</p>

<p class="text-justify leading-relaxed"><strong>Kedua, melatih kesabaran dan ketekunan.</strong> Tidak semua soal matematika dapat diselesaikan dalam waktu singkat. Terkadang, murid perlu mencoba beberapa cara sebelum menemukan jawaban yang tepat. Proses ini mengajarkan pentingnya kesabaran, ketelitian, dan semangat untuk terus berusaha meskipun menghadapi kesulitan.</p>

<p class="text-justify leading-relaxed"><strong>Ketiga, mengembangkan kemampuan berpikir logis dan rasional.</strong> Matematika mengajarkan murid untuk menggunakan alasan yang masuk akal dalam mengambil keputusan. Setiap langkah penyelesaian perlu dipertimbangkan dengan cermat agar memperoleh hasil yang tepat. Kemampuan ini sangat berguna ketika murid harus menganalisis informasi dan menentukan pilihan.</p>

<p class="text-justify leading-relaxed">Selain ketiga manfaat tersebut, matematika juga dapat dipelajari melalui berbagai permainan yang menarik. Permainan domino, kartu angka, teka-teki logika, hingga permainan berhitung dapat menjadi sarana untuk mengasah kemampuan numerasi tanpa membuat suasana belajar terasa membosankan. Melalui permainan, murid dapat belajar sambil bersenang-senang, berlatih bekerja sama, dan mengembangkan strategi.</p>

<p class="text-justify leading-relaxed">Dalam kehidupan sehari-hari, matematika hadir dalam berbagai kegiatan sederhana. Ketika berbelanja, kita menggunakan matematika untuk menghitung total harga dan uang kembalian. Saat memasak, kita membutuhkan perhitungan takaran bahan. Ketika bepergian, kita dapat memperkirakan jarak, waktu, dan kecepatan. Bahkan, saat bermain atau menyusun jadwal kegiatan, kita juga menerapkan konsep matematika tanpa selalu menyadarinya.</p>

<p class="text-justify leading-relaxed">Oleh karena itu, belajar matematika tidak harus selalu dilakukan dengan menghafal rumus dan mengerjakan soal di buku. Murid dapat mulai belajar melalui kegiatan yang dekat dengan kehidupan mereka, melakukan percobaan sederhana, bermain permainan edukatif, serta berdiskusi bersama teman. Dengan cara tersebut, matematika akan terasa lebih nyata, mudah dipahami, dan menyenangkan.</p>

<p class="text-justify leading-relaxed">Pada akhirnya, matematika bukan sekadar mata pelajaran yang menuntut ketepatan jawaban, melainkan juga sarana untuk membentuk pola pikir yang kritis, kreatif, dan logis. Kunci untuk menguasainya adalah kemauan untuk belajar, berlatih secara konsisten, dan tidak takut melakukan kesalahan.</p>

<p class="text-justify leading-relaxed">Yuk, kita ubah anggapan bahwa matematika itu sulit menjadi matematika itu seru! Mari belajar matematika dengan penuh semangat, temukan keseruannya, dan rasakan manfaatnya dalam kehidupan. Karena dengan matematika, kita belajar bukan hanya menghitung angka, tetapi juga berpikir, memecahkan masalah, dan mengambil keputusan dengan lebih bijak.</p>'
        ),

        // -------------------------------------------------------------
        // EDISI 0007: Dra. Hj. ATNAWATI (3 November 2026)
        // -------------------------------------------------------------
        array(
            'slug'         => 'cintai-profesimu-dedikasikan-diri-untuk-mencerdaskan-generasi-negeri',
            'old_slugs'    => array( 'menumbuhkan-nalar-kritis-siswa-matematika-realistik' ),
            'title'        => 'Cintai Profesimu, Dedikasikan Diri untuk Mencerdaskan Generasi Negeri',
            'date'         => '2026-11-03 08:00:00',
            'author_name'  => 'Dra. Hj. Atnawati',
            'author_role'  => 'Guru Mapel Matematika MTsN 1 Blitar',
            'author_photo' => '/wp-content/uploads/2026/11/gtk-atnawati.webp',
            'flyer_file'   => 'al-qalam-atnawati.jpg',
            'cat_slug'     => 'opini-guru',
            'excerpt'      => 'Guru yang mencintai profesinya akan mengajar dengan hati, mendidik dengan keteladanan, dan menginspirasi melalui tindakan. Dari guru yang bahagia dalam pengabdiannya, diharapkan tumbuh murid-murid yang bahagia dalam belajar, percaya diri dalam melangkah, dan siap menghadapi masa depan.',
            'quote'        => 'Guru yang mencintai profesinya akan mengajar dengan hati, mendidik dengan keteladanan, dan menginspirasi melalui tindakan. Dari guru yang bahagia dalam pengabdiannya, diharapkan tumbuh murid-murid yang bahagia dalam belajar, percaya diri dalam melangkah, dan siap menghadapi masa depan.',
            'content'      => '<p class="text-justify leading-relaxed"><strong>Mencintai profesi</strong> bukan sekadar menjalankan pekerjaan yang disukai, melainkan juga menemukan makna, kebahagiaan, dan kepuasan dalam setiap proses yang dijalani. Kecintaan terhadap profesi merupakan komitmen hati dan pikiran yang mendorong seseorang untuk terus berkembang, memberikan kontribusi terbaik, serta menghadirkan manfaat bagi lingkungan sekitarnya.</p>

<p class="text-justify leading-relaxed">Seseorang yang mencintai profesinya akan menjalankan tugas dengan penuh tanggung jawab dan semangat. Tantangan tidak lagi dipandang sebagai beban, tetapi sebagai kesempatan untuk belajar dan memperbaiki diri. Kecintaan terhadap pekerjaan juga dapat meningkatkan kinerja, menjaga kesejahteraan mental, serta mendorong lahirnya kreativitas dan inovasi.</p>

<p class="text-justify leading-relaxed">Bagi seorang guru, mencintai profesi memiliki makna yang jauh lebih mendalam. Menjadi guru bukan sekadar menjalankan pekerjaan untuk memenuhi kewajiban, melainkan panggilan jiwa dan amanah besar dalam membentuk generasi penerus bangsa. Guru berada di garda terdepan dalam menanamkan ilmu pengetahuan, membangun karakter, menumbuhkan nilai-nilai kebaikan, dan mengantarkan murid menuju masa depan yang lebih baik.</p>

<p class="text-justify leading-relaxed">Ketika seorang guru mencintai profesinya, ia tidak hanya mengajar dengan pengetahuan, tetapi juga mendidik dengan ketulusan, kesabaran, dan kasih sayang. Setiap murid memiliki karakter, kemampuan, dan kebutuhan belajar yang berbeda. Keberagaman tersebut menjadi tantangan sekaligus kesempatan bagi guru untuk terus berinovasi dalam menciptakan pembelajaran yang bermakna, menyenangkan, dan sesuai dengan kebutuhan murid.</p>

<p class="text-justify leading-relaxed">Bagi saya, profesi guru merupakan ladang pengabdian sekaligus kesempatan untuk menebarkan kebaikan. Ilmu yang diberikan, nasihat yang disampaikan, serta keteladanan yang ditunjukkan dapat meninggalkan jejak dalam kehidupan murid. Terkadang, hasil pendidikan tidak terlihat secara langsung. Namun, benih kebaikan yang ditanamkan hari ini dapat tumbuh menjadi karakter, perilaku, dan prestasi yang membanggakan pada masa mendatang.</p>

<p class="text-justify leading-relaxed">Mencintai profesi guru juga berarti menjaga niat, terus memperbaiki diri, dan tidak pernah berhenti belajar. Guru bukanlah sosok yang sempurna, melainkan pribadi yang bersedia bertumbuh bersama murid-muridnya. Guru yang baik bukan hanya membantu murid memahami pelajaran, tetapi juga memberikan ruang bagi mereka untuk mengenali potensi, membangun kepercayaan diri, dan berani meraih cita-cita.</p>

<p class="text-justify leading-relaxed">Kebahagiaan seorang guru tidak selalu diukur dari penghargaan atau pencapaian besar. Terkadang, senyum murid yang mulai memahami pelajaran, keberanian murid untuk mencoba, perubahan perilaku menjadi lebih baik, atau keberhasilan kecil yang mereka raih sudah menjadi kebahagiaan tersendiri. Momen-momen sederhana itulah yang mengingatkan bahwa profesi guru memiliki makna yang begitu istimewa.</p>

<p class="text-justify leading-relaxed">Oleh karena itu, mari kita cintai profesi yang kita jalani dengan sepenuh hati. Jalankan tugas dengan penuh tanggung jawab, rawat semangat untuk terus belajar, dan hadirkan ketulusan dalam setiap pengabdian. Sebab, pekerjaan yang dilakukan dengan cinta akan melahirkan dedikasi, kreativitas, dan semangat untuk memberikan yang terbaik.</p>

<p class="text-justify leading-relaxed">Mari cintai profesi kita, syukuri setiap kesempatan untuk mengabdi, dan terus tebarkan manfaat. Karena menjadi guru bukan hanya tentang apa yang kita ajarkan hari ini, tetapi juga tentang nilai-nilai kebaikan yang akan terus hidup dalam diri generasi masa depan.</p>'
        ),

        // -------------------------------------------------------------
        // EDISI 0008: Lilik Wahyuningati, S.Pd. (10 November 2026)
        // -------------------------------------------------------------
        array(
            'slug'         => 'perubahan-sosial-dalam-pendidikan-makna-belajar',
            'old_slugs'    => array(),
            'title'        => 'PERUBAHAN SOSIAL DALAM PENDIDIKAN: Menghidupkan Kembali Makna Belajar di Tengah Arus Perubahan Zaman',
            'date'         => '2026-11-10 08:00:00',
            'author_name'  => 'Lilik Wahyuningati, S.Pd.',
            'author_role'  => 'Guru MTsN 1 Blitar',
            'author_photo' => '/wp-content/uploads/2026/11/gtk-lilik-wahyuningati.webp',
            'flyer_file'   => 'al-qalam-0008-lilik-wahyuningati.jpg',
            'cat_slug'     => 'karakter-siswa',
            'excerpt'      => 'Menghadapi pusaran perubahan sosial, pendidikan bertransformasi menjadi ekosistem belajar yang dinamis. Esensi pendidikan adalah membentuk manusia yang berpengetahuan, berkarakter, mandiri, dan memiliki kepedulian sosial.',
            'quote'        => 'Menghadapi pusaran perubahan sosial, pendidikan bertransformasi menjadi ekosistem belajar yang dinamis.',
            'content'      => '<p class="text-justify leading-relaxed"><strong>Dunia pendidikan</strong> dewasa ini berada di tengah pusaran perubahan sosial yang sangat pesat. Perkembangan teknologi, globalisasi, perubahan pola komunikasi, serta pergeseran nilai dalam masyarakat telah memengaruhi cara manusia memperoleh pengetahuan dan berinteraksi. Pendidikan tidak lagi sekadar menjadi ruang transfer ilmu di dalam kelas, tetapi harus mampu merespons perkembangan zaman dan kebutuhan karakter murid.</p>

<p class="text-justify leading-relaxed">Sayangnya, perubahan pendidikan terkadang lebih banyak disikapi melalui pemenuhan tuntutan administratif dan kurikulum. Padahal, esensi pendidikan bukan sekadar menyelesaikan dokumen atau mengejar target nilai, melainkan membentuk manusia yang berpengetahuan, berkarakter, mandiri, dan memiliki kepedulian sosial.</p>

<h4 class="text-lg font-bold text-slate-900 mt-6 mb-2">Pergeseran Peran Guru di Era Digital</h4>
<p class="text-justify leading-relaxed">Dahulu, guru menjadi sumber informasi utama dalam pembelajaran. Kini, murid dapat memperoleh pengetahuan melalui internet, media sosial, dan berbagai platform digital. Kondisi ini mengubah peran guru menjadi fasilitator, motivator, pembimbing, sekaligus mitra belajar. Guru tidak cukup hanya menyampaikan materi. Guru perlu membimbing murid agar mampu memilah informasi, membedakan fakta dan opini, berpikir kritis, serta menggunakan teknologi secara bijak. Kemajuan teknologi memang memberikan kemudahan, tetapi tetap membutuhkan pendampingan agar tidak mengurangi nilai moral, empati, dan tanggung jawab. Teknologi seharusnya menjadi sarana pendukung pembelajaran, bukan pengganti interaksi manusiawi antara guru dan murid.</p>

<h4 class="text-lg font-bold text-slate-900 mt-6 mb-2">Menyikapi Perubahan Sosial dalam Pendidikan</h4>
<p class="text-justify leading-relaxed">Ada beberapa langkah yang dapat dilakukan untuk menghadapi perubahan sosial dalam dunia pendidikan:</p>

<p class="text-justify leading-relaxed"><strong>Pertama, menghubungkan pembelajaran dengan realitas sosial.</strong> Pembelajaran tidak boleh berhenti pada hafalan konsep. Murid perlu diajak memahami penerapan ilmu dalam kehidupan sehari-hari. Melalui kegiatan observasi, diskusi, proyek, wawancara, dan kegiatan sosial, murid dapat belajar mengenali persoalan di lingkungan sekitar serta menemukan solusi. Dengan demikian, sekolah menjadi ruang belajar yang dekat dengan kehidupan masyarakat. Ilmu pengetahuan tidak hanya digunakan untuk memperoleh nilai, tetapi juga menjadi bekal menghadapi persoalan nyata.</p>

<p class="text-justify leading-relaxed"><strong>Kedua, menanamkan ketahanan mental dan fleksibilitas berpikir.</strong> Perubahan zaman menghadirkan berbagai tantangan dan ketidakpastian. Murid perlu dibekali kesabaran, ketekunan, keberanian mencoba, serta kemampuan beradaptasi. Mereka harus memahami bahwa kegagalan bukan akhir dari proses, melainkan kesempatan untuk mengevaluasi dan memperbaiki diri. Guru perlu memberikan ruang bagi murid untuk bertanya, berpendapat, mencoba, dan mengembangkan berbagai alternatif penyelesaian masalah. Pembelajaran hendaknya menghargai proses, bukan hanya berorientasi pada hasil akhir.</p>

<p class="text-justify leading-relaxed"><strong>Ketiga, membangun budaya kolaborasi.</strong> Kemampuan bekerja sama menjadi keterampilan penting dalam kehidupan masa depan. Melalui diskusi kelompok, proyek pembelajaran, organisasi, dan kegiatan sosial, murid belajar menghargai perbedaan, membagi tanggung jawab, serta menyelesaikan persoalan bersama. Kolaborasi juga perlu dibangun antara sekolah, keluarga, dan masyarakat. Pendidikan bukan hanya tanggung jawab guru, melainkan tanggung jawab bersama dalam membentuk generasi yang berkualitas.</p>

<p class="text-justify leading-relaxed"><strong>Keempat, memperkuat literasi dan karakter.</strong> Di tengah derasnya arus informasi, murid perlu memiliki kemampuan membaca, memahami, menganalisis, dan mengevaluasi informasi. Mereka juga harus dibimbing agar bijak menggunakan media digital serta tidak mudah terpengaruh informasi yang belum terbukti kebenarannya. Kemampuan akademik harus berjalan seiring dengan pembentukan karakter. Kejujuran, kedisiplinan, tanggung jawab, kesantunan, dan kepedulian sosial perlu ditanamkan melalui pembiasaan dan keteladanan dalam kehidupan sekolah.</p>'
        ),
    );

    global $wpdb;

    foreach ( $articles as $art ) {
        // Gabungkan blockquote quote di bagian paling atas konten
        $full_content = '';
        if ( ! empty( $art['quote'] ) ) {
            $full_content .= '<blockquote class="my-6 p-5 sm:p-6 rounded-2xl bg-amber-50/80 border-l-4 border-amber-500 text-slate-800 text-base sm:text-lg italic font-medium leading-relaxed shadow-xs">&ldquo;' . esc_html( $art['quote'] ) . '&rdquo;</blockquote>' . "\n";
        }
        $full_content .= $art['content'];

        // Tambahkan tampilan arsip buletin cetak di bagian bawah
        $flyer_url = get_template_directory_uri() . '/assets/img/' . $art['flyer_file'];
        $full_content .= "\n" . '<div class="mt-10 pt-6 border-t border-slate-200">
  <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-2">
    <i class="fa-regular fa-newspaper text-emerald-600"></i> Lembar Buletin Cetak Al-Qalam
  </div>
  <div class="rounded-2xl overflow-hidden shadow-md border border-slate-200 max-w-xl mx-auto bg-slate-50">
    <img src="' . esc_url( $flyer_url ) . '" alt="' . esc_attr( $art['title'] ) . '" class="w-full h-auto">
  </div>
</div>';

        // Cari apakah post sudah ada berdasarkan slug saat ini, slug lama, atau author name
        $where_clauses = array(
            $wpdb->prepare( "p.post_name = %s", $art['slug'] )
        );
        foreach ( $art['old_slugs'] as $old_slug ) {
            $where_clauses[] = $wpdb->prepare( "p.post_name = %s", $old_slug );
        }
        $where_sql = implode( ' OR ', $where_clauses );

        $post_id = $wpdb->get_var( "
            SELECT p.ID FROM {$wpdb->posts} p
            WHERE p.post_type = 'blog' AND ({$where_sql})
            LIMIT 1
        " );

        // Jika belum ketemu dengan slug, coba cari berdasarkan meta author_name
        if ( ! $post_id ) {
            $post_id = $wpdb->get_var( $wpdb->prepare( "
                SELECT p.ID FROM {$wpdb->posts} p
                JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_author_name'
                WHERE p.post_type = 'blog' AND pm.meta_value LIKE %s
                LIMIT 1
            ", '%' . $wpdb->esc_like( $art['author_name'] ) . '%' ) );
        }

        if ( $post_id ) {
            // Update postingan yang sudah ada
            wp_update_post( array(
                'ID'            => $post_id,
                'post_title'    => $art['title'],
                'post_name'     => $art['slug'],
                'post_excerpt'  => $art['excerpt'],
                'post_content'  => $full_content,
                'post_status'   => 'publish',
                'post_date'     => $art['date'],
                'post_date_gmt' => $art['date'],
            ) );
        } else {
            // Buat postingan baru
            $post_id = wp_insert_post( array(
                'post_type'     => 'blog',
                'post_title'    => $art['title'],
                'post_name'     => $art['slug'],
                'post_excerpt'  => $art['excerpt'],
                'post_content'  => $full_content,
                'post_status'   => 'publish',
                'post_date'     => $art['date'],
                'post_date_gmt' => $art['date'],
            ) );
        }

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, '_author_name', $art['author_name'] );
            update_post_meta( $post_id, '_author_role', $art['author_role'] );
            update_post_meta( $post_id, '_author_photo', home_url( $art['author_photo'] ) );

            // Tetapkan taksonomi blog-kat
            if ( ! empty( $art['cat_slug'] ) ) {
                wp_set_object_terms( $post_id, $art['cat_slug'], 'blog-kat', false );
            }

            // Tetapkan thumbnail default jika belum ada
            if ( ! get_post_thumbnail_id( $post_id ) ) {
                update_post_meta( $post_id, '_thumbnail_id', 8938 );
            }
        }
    }

    update_option( 'mtsn1_alqalam_sync_version', $current_version );
}
