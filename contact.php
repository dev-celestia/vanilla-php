<?php
/**
 * Standalone Storefront Contact & Support Page
 */
$active_nav = 'contact';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';
require_once __DIR__ . '/helpers/csrf.php';

$settings = get_settings();
$page_title = 'Contact Us - ' . ($settings['store_name'] ?? 'Store Showcase');

$successMsg = null;
$errorMsg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $errorMsg = 'Please complete all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = 'The email address format is invalid.';
    } else {
        $successMsg = 'Thank you for reaching out! We have received your inquiry and our support team will respond shortly via WhatsApp/Email.';
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
            <span>Customer Care &amp; Support</span>
        </span>
        <h1 class="text-2xl sm:text-4xl font-semibold tracking-tight text-white">
            Contact <?= sanitize($settings['store_name'] ?? 'Store Showcase') ?>
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm sm:text-base mt-3 leading-relaxed max-w-2xl mx-auto">
            Have questions regarding product availability, shipping status, or want instant shopping assistance? We're here to help.
        </p>
    </div>
</section>

<!-- Breadcrumb -->
<div class="bg-white border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="<?= base_url('demo.php') ?>" class="hover:text-brand-600 transition-colors flex items-center gap-1">
                <i class="ph ph-storefront"></i>
                <span>Catalog</span>
            </a>
            <i class="ph ph-caret-right text-[10px] text-slate-400"></i>
            <span class="text-slate-900 font-semibold">Contact Us</span>
        </div>
    </div>
</div>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
        
        <!-- Contact Information Cards -->
        <div class="lg:col-span-5 space-y-5">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">Official Channels</span>
                <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mt-1">We're Ready to Help</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Choose the communication channel that works best for you.</p>
            </div>

            <!-- WhatsApp Card -->
            <?php 
                $cleanWa = preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '15552345678');
                $waText = urlencode("Hello Admin " . ($settings['store_name'] ?? 'Store') . ", I would like to inquire about products/orders.");
            ?>
            <a href="https://wa.me/<?= $cleanWa ?>?text=<?= $waText ?>" target="_blank" class="p-5 rounded-card bg-white border border-slate-200/80 hover:border-brand-400 hover:shadow-sm transition-all block apple-tap group">
                <div class="flex items-center gap-4">
                    <?= ui_icon_box('whatsapp-logo', 'brand', ['size' => 'lg']) ?>
                    <div class="flex-grow">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-slate-900 tracking-tight group-hover:text-brand-600 transition-colors">WhatsApp Customer Support</h4>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5"><?= sanitize($settings['whatsapp_number'] ?? '+1 (555) 234-5678') ?></p>
                        <span class="text-[11px] font-semibold text-brand-600 mt-1.5 inline-flex items-center gap-1">Chat on WhatsApp <i class="ph ph-arrow-right group-hover:translate-x-0.5 transition-transform"></i></span>
                    </div>
                </div>
            </a>

            <!-- Email Card -->
            <a href="mailto:<?= sanitize($settings['store_email'] ?? 'contact@store.local') ?>" class="p-5 rounded-card bg-white border border-slate-200/80 hover:border-brand-400 hover:shadow-sm transition-all block apple-tap group">
                <div class="flex items-center gap-4">
                    <?= ui_icon_box('envelope-simple', 'slate', ['size' => 'lg']) ?>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 tracking-tight group-hover:text-brand-600 transition-colors">Official Email</h4>
                        <p class="text-xs text-slate-500 mt-0.5"><?= sanitize($settings['store_email'] ?? 'contact@store.local') ?></p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Replies within 24 business hours</span>
                    </div>
                </div>
            </a>

            <!-- Store Location & Hours Card -->
            <div class="p-5 rounded-card bg-white border border-slate-200/80 space-y-3">
                <div class="flex items-start gap-4">
                    <?= ui_icon_box('map-pin', 'slate', ['size' => 'lg']) ?>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 tracking-tight">Store Location &amp; Address</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            <?= nl2br(sanitize($settings['store_address'] ?? '742 Evergreen Terrace, Springfield, OR 97477')) ?>
                        </p>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <i class="ph ph-clock text-slate-400"></i>
                        <span>Operating Hours:</span>
                    </span>
                    <span class="font-semibold text-slate-700">08:00 AM - 09:00 PM</span>
                </div>
            </div>
        </div>

        <!-- Contact Form Card -->
        <div class="lg:col-span-7">
            <div class="p-6 sm:p-8 rounded-card bg-white border border-slate-200/80">
                <div class="mb-6">
                    <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">Send Message</span>
                    <h3 class="text-xl font-semibold text-slate-900 tracking-tight mt-1">Leave Us a Message</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Please fill out the form below and our representative will get back to you promptly.</p>
                </div>

                <?php if ($successMsg): ?>
                    <div class="mb-6">
                        <?= ui_alert($successMsg, 'success', ['title' => 'Message Sent Successfully!']) ?>
                    </div>
                <?php endif; ?>

                <?php if ($errorMsg): ?>
                    <div class="mb-6">
                        <?= ui_alert($errorMsg, 'danger', ['title' => 'Notice']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('contact.php') ?>" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?= ui_input('name', [
                            'label'       => 'Full Name *',
                            'placeholder' => 'e.g. John Doe',
                            'required'    => true,
                            'icon'        => 'user',
                            'value'       => $_POST['name'] ?? '',
                        ]) ?>

                        <?= ui_input('email', [
                            'type'        => 'email',
                            'label'       => 'Email Address *',
                            'placeholder' => 'john@example.com',
                            'required'    => true,
                            'icon'        => 'envelope-simple',
                            'value'       => $_POST['email'] ?? '',
                        ]) ?>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?= ui_input('phone', [
                            'type'        => 'tel',
                            'label'       => 'WhatsApp / Phone Number',
                            'placeholder' => '+1 (555) 234-5678',
                            'icon'        => 'phone',
                            'value'       => $_POST['phone'] ?? '',
                        ]) ?>

                        <?= ui_input('subject', [
                            'label'       => 'Subject / Order No.',
                            'placeholder' => 'e.g. Stock inquiry / #ORD-1234',
                            'icon'        => 'chat-teardrop-text',
                            'value'       => $_POST['subject'] ?? '',
                        ]) ?>
                    </div>

                    <?= ui_textarea('message', [
                        'label'       => 'Your Message or Question *',
                        'placeholder' => 'Describe your question or order details here...',
                        'required'    => true,
                        'rows'        => 5,
                        'value'       => $_POST['message'] ?? '',
                    ]) ?>

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <?= ui_button('Send Message Now', [
                            'type'    => 'submit',
                            'variant' => 'primary',
                            'size'    => 'lg',
                            'icon'    => 'paper-plane-tilt',
                            'class'   => 'w-full sm:w-auto',
                        ]) ?>
                        <span class="text-[11px] text-slate-400 text-center sm:text-right">
                            <i class="ph ph-shield-check text-emerald-500 mr-0.5"></i> Your information is safely encrypted
                        </span>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
