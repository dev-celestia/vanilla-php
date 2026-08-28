<?php
/**
 * Standalone Storefront Contact & Support Page
 */
$active_nav = 'contact';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';
require_once __DIR__ . '/helpers/csrf.php';

$settings = get_settings();
$page_title = 'Hubungi Kami - ' . ($settings['store_name'] ?? 'KatalogStore');

$successMsg = null;
$errorMsg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $errorMsg = 'Mohon lengkapi seluruh formulir yang bertanda wajib.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = 'Format alamat email yang Anda masukkan tidak valid.';
    } else {
        $successMsg = 'Terima kasih telah menghubungi kami! Pesan Anda telah kami terima dan tim kami akan segera merespons melalui WhatsApp/Email.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white py-14 sm:py-16 border-b border-slate-800 text-center relative overflow-hidden">
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-brand-400/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-semibold mb-3 tracking-tight">
            <i class="ph ph-headset text-sm"></i>
            <span>Layanan Pelanggan &amp; Bantuan</span>
        </span>
        <h1 class="text-2xl sm:text-4xl font-semibold tracking-tight text-white">
            Hubungi <?= sanitize($settings['store_name'] ?? 'Official Store') ?>
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm sm:text-base mt-3 leading-relaxed max-w-2xl mx-auto">
            Punya pertanyaan seputar ketersediaan produk, status pengiriman, atau ingin konsultasi belanja langsung? Kami siap membantu Anda.
        </p>
    </div>
</section>

<!-- Breadcrumb -->
<div class="bg-white border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="<?= base_url('demo.php') ?>" class="hover:text-brand-600 transition-colors flex items-center gap-1">
                <i class="ph ph-storefront"></i>
                <span>Katalog</span>
            </a>
            <i class="ph ph-caret-right text-[10px] text-slate-400"></i>
            <span class="text-slate-900 font-semibold">Hubungi Kami</span>
        </div>
    </div>
</div>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
        
        <!-- Contact Information Cards -->
        <div class="lg:col-span-5 space-y-5">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">Kontak Resmi</span>
                <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mt-1">Kami Siap Melayani Anda</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Pilih saluran komunikasi resmi yang paling nyaman untuk Anda.</p>
            </div>

            <!-- WhatsApp Card -->
            <?php 
                $cleanWa = preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '6281234567890');
                $waText = urlencode("Halo Admin " . ($settings['store_name'] ?? 'Toko') . ", saya ingin bertanya seputar produk/pesanan.");
            ?>
            <a href="https://wa.me/<?= $cleanWa ?>?text=<?= $waText ?>" target="_blank" class="p-5 rounded-card bg-white border border-slate-200/80 hover:border-brand-400 hover:shadow-sm transition-all block apple-tap group">
                <div class="flex items-center gap-4">
                    <?= ui_icon_box('whatsapp-logo', 'brand', ['size' => 'lg']) ?>
                    <div class="flex-grow">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-slate-900 tracking-tight group-hover:text-brand-600 transition-colors">WhatsApp Customer Service</h4>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5"><?= sanitize($settings['whatsapp_number'] ?? '+62 812-3456-7890') ?></p>
                        <span class="text-[11px] font-semibold text-brand-600 mt-1.5 inline-flex items-center gap-1">Chat WhatsApp Langsung <i class="ph ph-arrow-right group-hover:translate-x-0.5 transition-transform"></i></span>
                    </div>
                </div>
            </a>

            <!-- Email Card -->
            <a href="mailto:<?= sanitize($settings['store_email'] ?? 'support@toko.com') ?>" class="p-5 rounded-card bg-white border border-slate-200/80 hover:border-brand-400 hover:shadow-sm transition-all block apple-tap group">
                <div class="flex items-center gap-4">
                    <?= ui_icon_box('envelope-simple', 'slate', ['size' => 'lg']) ?>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 tracking-tight group-hover:text-brand-600 transition-colors">Email Resmi</h4>
                        <p class="text-xs text-slate-500 mt-0.5"><?= sanitize($settings['store_email'] ?? 'support@toko.com') ?></p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Respons balasan dalam 1x24 jam kerja</span>
                    </div>
                </div>
            </a>

            <!-- Store Location & Hours Card -->
            <div class="p-5 rounded-card bg-white border border-slate-200/80 space-y-3">
                <div class="flex items-start gap-4">
                    <?= ui_icon_box('map-pin', 'slate', ['size' => 'lg']) ?>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 tracking-tight">Lokasi &amp; Alamat Toko</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            <?= nl2br(sanitize($settings['store_address'] ?? 'Jl. Jenderal Sudirman No. 123, Jakarta Selatan, Indonesia')) ?>
                        </p>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <i class="ph ph-clock text-slate-400"></i>
                        <span>Jam Operasional:</span>
                    </span>
                    <span class="font-semibold text-slate-700">08:00 - 21:00 WIB</span>
                </div>
            </div>
        </div>

        <!-- Contact Form Card -->
        <div class="lg:col-span-7">
            <div class="p-6 sm:p-8 rounded-card bg-white border border-slate-200/80">
                <div class="mb-6">
                    <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">Formulir Pesan</span>
                    <h3 class="text-xl font-semibold text-slate-900 tracking-tight mt-1">Kirim Pesan ke Customer Service</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Silakan isi formulir di bawah ini, tim representatif kami akan segera menghubungi Anda kembali.</p>
                </div>

                <?php if ($successMsg): ?>
                    <div class="mb-6">
                        <?= ui_alert($successMsg, 'success', ['title' => 'Pesan Berhasil Terkirim!']) ?>
                    </div>
                <?php endif; ?>

                <?php if ($errorMsg): ?>
                    <div class="mb-6">
                        <?= ui_alert($errorMsg, 'danger', ['title' => 'Pemberitahuan']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('contact.php') ?>" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?= ui_input('name', [
                            'label'       => 'Nama Lengkap *',
                            'placeholder' => 'Contoh: Ahmad Pratama',
                            'required'    => true,
                            'icon'        => 'user',
                            'value'       => $_POST['name'] ?? '',
                        ]) ?>

                        <?= ui_input('email', [
                            'type'        => 'email',
                            'label'       => 'Alamat Email *',
                            'placeholder' => 'nama@domain.com',
                            'required'    => true,
                            'icon'        => 'envelope-simple',
                            'value'       => $_POST['email'] ?? '',
                        ]) ?>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?= ui_input('phone', [
                            'type'        => 'tel',
                            'label'       => 'Nomor WhatsApp / HP',
                            'placeholder' => '0812-xxxx-xxxx',
                            'icon'        => 'phone',
                            'value'       => $_POST['phone'] ?? '',
                        ]) ?>

                        <?= ui_input('subject', [
                            'label'       => 'Subjek / No. Pesanan',
                            'placeholder' => 'Contoh: Tanya stok barang / #ORD-1234',
                            'icon'        => 'chat-teardrop-text',
                            'value'       => $_POST['subject'] ?? '',
                        ]) ?>
                    </div>

                    <?= ui_textarea('message', [
                        'label'       => 'Pesan atau Pertanyaan Anda *',
                        'placeholder' => 'Tuliskan detail pertanyaan atau pesanan Anda di sini...',
                        'required'    => true,
                        'rows'        => 5,
                        'value'       => $_POST['message'] ?? '',
                    ]) ?>

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <?= ui_button('Kirim Pesan Sekarang', [
                            'type'    => 'submit',
                            'variant' => 'primary',
                            'size'    => 'lg',
                            'icon'    => 'paper-plane-tilt',
                            'class'   => 'w-full sm:w-auto',
                        ]) ?>
                        <span class="text-[11px] text-slate-400 text-center sm:text-right">
                            <i class="ph ph-shield-check text-emerald-500 mr-0.5"></i> Data Anda terlindungi aman
                        </span>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
