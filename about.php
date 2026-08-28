<?php
/**
 * About Us & Business Profile Page
 */
$active_nav = 'about';
$page_title = 'Tentang Kami - Profil Bisnis & Toko';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';
$settings = get_settings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="bg-gradient-to-b from-slate-950 to-slate-900 text-white py-16 lg:py-20 border-b border-slate-800 text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="inline-block px-3 py-1 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-bold mb-3 tracking-tight">
            🏢 Profil Perusahaan & Toko
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
            Mengenal Lebih Dekat <?= sanitize($settings['store_name']) ?>
        </h1>
        <p class="text-slate-300 text-sm sm:text-base mt-4 leading-relaxed max-w-2xl mx-auto">
            Solusi belanja daring yang mengutamakan kecepatan layanan, jaminan produk asli, dan kenyamanan pemesanan langsung melalui WhatsApp.
        </p>
    </div>
</section>

<!-- Content Section (Zero Shadow, Crisp Borders, Token Radius) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
    
    <!-- Story & Vision -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600">Cerita Kami</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                    Dedikasi Memberikan Pengalaman Belanja Terbaik
                </h2>
            </div>

            <p class="text-sm text-slate-600 leading-relaxed">
                <?= nl2br(sanitize($settings['store_description'])) ?>
            </p>

            <p class="text-sm text-slate-600 leading-relaxed">
                Didirikan dengan visi mempermudah transaksi jual-beli di era digital, kami mengadopsi model katalog interaktif yang terhubung langsung dengan tim penjualan melalui WhatsApp. Anda tidak perlu lagi repot membuat akun atau mengingat banyak password; cukup pilih produk, isi alamat, dan kami siap memproses pesanan Anda secara personal.
            </p>

            <div class="pt-4 flex flex-wrap gap-4">
                <div class="flex items-center gap-3 p-3.5 bg-white rounded-card border border-slate-200/80">
                    <div class="w-10 h-10 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/80 flex items-center justify-center font-bold">
                        ✓
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 tracking-tight">Garansi Kualitas</h4>
                        <p class="text-[11px] text-slate-500">100% Produk Teruji</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3.5 bg-white rounded-card border border-slate-200/80">
                    <div class="w-10 h-10 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/80 flex items-center justify-center font-bold">
                        ⚡
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 tracking-tight">Respon Cepat</h4>
                        <p class="text-[11px] text-slate-500">Admin Siap Melayani</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative">
            <div class="aspect-4/3 rounded-card overflow-hidden border border-slate-200/80 bg-slate-100">
                <img 
                    src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&auto=format&fit=crop&q=80" 
                    alt="Toko Kami" 
                    class="w-full h-full object-cover"
                >
            </div>
        </div>
    </div>

    <!-- Values Grid -->
    <div class="pt-8 border-t border-slate-200/80">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold uppercase tracking-wider text-brand-600">Nilai Utama</span>
            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Mengapa Pelanggan Memilih Kami?</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition">
                <div class="w-12 h-12 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/70 flex items-center justify-center mb-6">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-2 tracking-tight">Transparan & Terpercaya</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Harga tertera jelas tanpa biaya tersembunyi. Pelanggan dapat berdiskusi langsung perihal stok dan rincian sebelum melakukan transfer.
                </p>
            </div>

            <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition">
                <div class="w-12 h-12 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/70 flex items-center justify-center mb-6">
                    <i data-lucide="message-circle" class="w-6 h-6"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-2 tracking-tight">Pelayanan Personal</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Setiap interaksi dilayani oleh admin manusia secara hangat, memastikan kebutuhan pesanan Anda dipahami dengan detail.
                </p>
            </div>

            <div class="bg-white p-8 rounded-card border border-slate-200/80 hover:border-brand-300 transition">
                <div class="w-12 h-12 rounded-btn bg-brand-50 text-brand-600 border border-brand-200/70 flex items-center justify-center mb-6">
                    <i data-lucide="truck" class="w-6 h-6"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-2 tracking-tight">Ekspedisi Fleksibel</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Bekerja sama dengan berbagai jasa kirim reguler, kilat, maupun cargo untuk ongkos kirim terbaik ke lokasi Anda.
                </p>
            </div>
        </div>
    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
