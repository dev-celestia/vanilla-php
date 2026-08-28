<?php
/**
 * Contact Us & Location Page
 */
$active_nav = 'contact';
$page_title = 'Hubungi Kami & Lokasi Toko';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';
require_once __DIR__ . '/helpers/csrf.php';

$settings = get_settings();
$feedbackSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $feedbackName = trim($_POST['name'] ?? '');
        $feedbackEmail = trim($_POST['email'] ?? '');
        $feedbackSubject = trim($_POST['subject'] ?? '');
        $feedbackMessage = trim($_POST['message'] ?? '');

        // Redirect directly to WhatsApp with user's question
        $waAdminNumber = preg_replace('/[^0-9]/', '', $settings['whatsapp_number']);
        $text = "Halo Admin " . $settings['store_name'] . ",\n\n"
              . "Saya ingin bertanya:\n"
              . "• Nama: " . $feedbackName . "\n"
              . "• Email: " . $feedbackEmail . "\n"
              . "• Subjek: " . $feedbackSubject . "\n"
              . "• Pesan:\n" . $feedbackMessage;
        
        $directWaUrl = "https://api.whatsapp.com/send?phone=" . $waAdminNumber . "&text=" . urlencode($text);
        header('Location: ' . $directWaUrl);
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="bg-gradient-to-b from-slate-950 to-slate-900 text-white py-16 lg:py-20 border-b border-slate-800 text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="inline-block px-3 py-1 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-bold mb-3 tracking-tight">
            📞 Layanan Pelanggan
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
            Hubungi Kami & Layanan Pelanggan
        </h1>
        <p class="text-slate-300 text-sm sm:text-base mt-4 leading-relaxed max-w-2xl mx-auto">
            Punya pertanyaan mengenai produk, stok khusus, atau kerjasama bisnis? Tim kami siap melayani Anda.
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left: Contact Details Cards (Zero Shadow, Crisp Border) -->
        <div class="lg:col-span-5 space-y-6">
            
            <div class="bg-white rounded-card border border-slate-200/80 p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-extrabold text-slate-900 border-b border-slate-100 pb-3 tracking-tight">Informasi Kontak Toko</h3>

                <div class="space-y-4 text-xs">
                    <!-- WhatsApp -->
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) ?>" target="_blank" class="flex items-start gap-4 p-4 rounded-card bg-brand-50/60 border border-brand-200/80 hover:bg-brand-100/60 transition apple-tap group">
                        <div class="w-10 h-10 rounded-btn bg-brand-600 text-white flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <i class="ph ph-whatsapp-logo text-xl"></i>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block text-[11px]">Chat WhatsApp Resmi</span>
                            <span class="text-sm font-extrabold text-brand-700"><?= sanitize($settings['whatsapp_number']) ?></span>
                            <p class="text-[11px] text-slate-500 mt-0.5">Respon cepat setiap jam operasional</p>
                        </div>
                    </a>

                    <!-- Phone -->
                    <div class="flex items-start gap-4 p-4 rounded-card bg-slate-50 border border-slate-200/80">
                        <div class="w-10 h-10 rounded-btn bg-slate-200 text-slate-700 flex items-center justify-center flex-shrink-0">
                            <i class="ph ph-phone text-xl"></i>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block text-[11px]">Telepon / Hotline</span>
                            <span class="text-sm font-bold text-slate-800"><?= sanitize($settings['store_phone']) ?></span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-4 p-4 rounded-card bg-slate-50 border border-slate-200/80">
                        <div class="w-10 h-10 rounded-btn bg-slate-200 text-slate-700 flex items-center justify-center flex-shrink-0">
                            <i class="ph ph-envelope-simple text-xl"></i>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block text-[11px]">Email Resmi</span>
                            <span class="text-sm font-bold text-slate-800"><?= sanitize($settings['store_email']) ?></span>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex items-start gap-4 p-4 rounded-card bg-slate-50 border border-slate-200/80">
                        <div class="w-10 h-10 rounded-btn bg-slate-200 text-slate-700 flex items-center justify-center flex-shrink-0">
                            <i class="ph ph-map-pin text-xl"></i>
                        </div>
                        <div>
                            <span class="text-slate-400 font-medium block text-[11px]">Alamat Toko & Gudang</span>
                            <span class="text-xs font-semibold text-slate-700 leading-relaxed block mt-0.5">
                                <?= sanitize($settings['store_address']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operational Hours -->
            <div class="bg-white rounded-card border border-slate-200/80 p-6 space-y-3">
                <h4 class="font-extrabold text-sm text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-clock text-base text-brand-600"></i>
                    <span>Jam Operasional Layanan</span>
                </h4>
                <div class="text-xs text-slate-600 space-y-1.5 pt-1">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span>Senin - Jumat</span>
                        <span class="font-bold text-slate-800">08:00 - 21:00 WIB</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span>Sabtu</span>
                        <span class="font-bold text-slate-800">09:00 - 18:00 WIB</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span>Minggu & Hari Libur</span>
                        <span class="text-brand-600 font-bold">Tetap Menerima Chat WA</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Inquiry Form -->
        <div class="lg:col-span-7 bg-white rounded-card border border-slate-200/80 p-6 sm:p-8 space-y-6">
            
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Kirim Pesan Langsung ke WhatsApp</h2>
                <p class="text-xs text-slate-500 mt-1">Tulis pertanyaan Anda di bawah ini dan sistem akan mengarahkan format chat otomatis ke admin.</p>
            </div>

            <form action="<?= base_url('contact.php') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?= ui_input('name', [
                        'label'       => 'Nama Anda',
                        'placeholder' => 'Nama lengkap',
                        'required'    => true,
                    ]) ?>

                    <?= ui_input('email', [
                        'label'       => 'Email Anda (Opsional)',
                        'type'        => 'email',
                        'placeholder' => 'email@contoh.com',
                    ]) ?>
                </div>

                <?= ui_input('subject', [
                    'label'       => 'Subjek Pertanyaan',
                    'placeholder' => 'Contoh: Tanya ketersediaan stok grosir',
                    'required'    => true,
                ]) ?>

                <?= ui_textarea('message', [
                    'label'       => 'Isi Pesan atau Pertanyaan',
                    'rows'        => 4,
                    'placeholder' => 'Tuliskan detail pertanyaan atau pesanan khusus Anda di sini...',
                    'required'    => true,
                ]) ?>

                <div class="pt-2">
                    <?= ui_button('Kirimkan Pesan via WhatsApp', [
                        'variant' => 'primary',
                        'type'    => 'submit',
                        'size'    => 'lg',
                        'icon'    => 'send',
                        'class'   => 'w-full',
                    ]) ?>
                </div>
            </form>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
