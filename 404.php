<?php
/**
 * 404 Page Template
 */
get_header();
?>

<div class="py-24 bg-slate-100 min-h-screen flex items-center justify-center text-center">
  <div class="max-w-lg mx-auto px-4">
    <div class="w-24 h-24 mx-auto rounded-3xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-4xl mb-6 shadow-inner">
      <i class="fa-solid fa-triangle-exclamation"></i>
    </div>
    <h1 class="text-6xl font-black text-slate-900 leading-none">404</h1>
    <h2 class="text-xl font-bold text-slate-800 mt-3">Halaman Tidak Ditemukan</h2>
    <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
      Mohon maaf, halaman atau berkas yang Anda tuju mungkin telah dipindahkan, berganti nama, atau sudah tidak tersedia.
    </p>
    <div class="mt-8">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-700/20 transition">
        <i class="fa-solid fa-house mr-1.5"></i> Kembali ke Beranda
      </a>
    </div>
  </div>
</div>

<?php get_footer(); ?>
