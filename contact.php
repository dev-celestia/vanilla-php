<?php
/**
 * Contact & Support Page
 */
$active_nav = 'contact';
$page_title = 'Contact & Support - Native PHP UI';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/format.php';
require_once __DIR__ . '/helpers/csrf.php';

$settings = get_settings();
$successMsg = null;
$errorMsg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $errorMsg = 'Please fill out all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = 'Please provide a valid email address.';
    } else {
        $successMsg = 'Thank you for reaching out! We have received your inquiry and will respond shortly.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="bg-gradient-to-b from-slate-950 to-slate-900 text-white py-16 lg:py-20 border-b border-slate-800 text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="inline-block px-3 py-1 rounded-badge bg-brand-500/10 border border-brand-500/30 text-brand-300 text-xs font-semibold mb-3 tracking-tight">
            📞 Get In Touch
        </span>
        <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight">
            Contact & Community Support
        </h1>
        <p class="text-slate-300 text-sm sm:text-base mt-4 leading-relaxed max-w-2xl mx-auto">
            Have questions about NativePHP UI, need custom integration assistance, or want to contribute? Send us a message below.
        </p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- Contact Information Cards -->
        <div class="lg:col-span-5 space-y-6">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">Direct Channels</span>
                <h2 class="text-2xl font-semibold text-slate-900 tracking-tight mt-1">We're Here to Help</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Connect directly through our verified communication channels.</p>
            </div>

            <!-- WhatsApp Card -->
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '6281234567890') ?>?text=Hello%20NativePHP%20Team" target="_blank" class="p-5 rounded-card bg-white border border-slate-200/80 hover:border-brand-400 transition-all block apple-tap">
                <div class="flex items-center gap-4">
                    <?= ui_icon_box('whatsapp-logo', 'brand', ['size' => 'lg']) ?>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 tracking-tight">Instant WhatsApp Support</h4>
                        <p class="text-xs text-slate-500 mt-0.5"><?= sanitize($settings['whatsapp_number'] ?? '+62 812-3456-7890') ?></p>
                        <span class="text-[11px] font-semibold text-brand-600 mt-1 inline-flex items-center gap-1">Start Chat <i class="ph ph-arrow-right"></i></span>
                    </div>
                </div>
            </a>

            <!-- Email Card -->
            <div class="p-5 rounded-card bg-white border border-slate-200/80">
                <div class="flex items-center gap-4">
                    <?= ui_icon_box('envelope-simple', 'slate', ['size' => 'lg']) ?>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 tracking-tight">Email Inquiries</h4>
                        <p class="text-xs text-slate-500 mt-0.5"><?= sanitize($settings['store_email'] ?? 'support@nativephp-ui.dev') ?></p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Response within 24 hours</span>
                    </div>
                </div>
            </div>

            <!-- GitHub Card -->
            <a href="https://github.com/dev-celestia/simple-native-php" target="_blank" class="p-5 rounded-card bg-white border border-slate-200/80 hover:border-brand-400 transition-all block apple-tap">
                <div class="flex items-center gap-4">
                    <?= ui_icon_box('github-logo', 'dark', ['size' => 'lg']) ?>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 tracking-tight">GitHub Repository</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Issues, Discussions & Pull Requests</p>
                        <span class="text-[11px] font-semibold text-brand-600 mt-1 inline-flex items-center gap-1">Open Repo <i class="ph ph-arrow-square-out"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Contact Form Card -->
        <div class="lg:col-span-7">
            <div class="p-6 sm:p-8 rounded-card bg-white border border-slate-200/80">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 tracking-tight">Send an Inquiry</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Fill out the form and our core maintainers will get back to you.</p>
                </div>

                <?php if ($successMsg): ?>
                    <div class="mb-6">
                        <?= ui_alert($successMsg, 'success', ['title' => 'Message Sent!']) ?>
                    </div>
                <?php endif; ?>

                <?php if ($errorMsg): ?>
                    <div class="mb-6">
                        <?= ui_alert($errorMsg, 'danger', ['title' => 'Validation Notice']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('contact.php') ?>" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?= ui_input('name', [
                            'label'       => 'Your Full Name',
                            'placeholder' => 'Jane Doe',
                            'required'    => true,
                            'icon'        => 'user',
                            'value'       => $_POST['name'] ?? '',
                        ]) ?>

                        <?= ui_input('email', [
                            'type'        => 'email',
                            'label'       => 'Email Address',
                            'placeholder' => 'jane@example.com',
                            'required'    => true,
                            'icon'        => 'envelope-simple',
                            'value'       => $_POST['email'] ?? '',
                        ]) ?>
                    </div>

                    <?= ui_input('subject', [
                        'label'       => 'Subject',
                        'placeholder' => 'e.g. Design token integration question',
                        'icon'        => 'chat-teardrop-text',
                        'value'       => $_POST['subject'] ?? '',
                    ]) ?>

                    <?= ui_textarea('message', [
                        'label'       => 'Your Message',
                        'placeholder' => 'Describe your project or questions in detail...',
                        'required'    => true,
                        'rows'        => 5,
                        'value'       => $_POST['message'] ?? '',
                    ]) ?>

                    <div class="pt-2">
                        <?= ui_button('Send Message', [
                            'type'    => 'submit',
                            'variant' => 'primary',
                            'size'    => 'lg',
                            'icon'    => 'paper-plane-tilt',
                            'class'   => 'w-full sm:w-auto',
                        ]) ?>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
