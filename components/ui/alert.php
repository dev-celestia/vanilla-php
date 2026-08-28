<?php
/**
 * Alert Component Primitive (ui_alert)
 *
 * Notification banners, alert dialogs, and contextual messaging.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_alert')) {
    function ui_alert(string $message, string $variant = 'success', array $options = []): string {
        $title       = $options['title'] ?? null;
        $dismissible = !empty($options['dismissible']);
        $extraCls    = $options['class'] ?? '';

        $config = match($variant) {
            'success'         => ['bg' => 'bg-emerald-50 border-emerald-200/90 text-emerald-900', 'icon' => 'check-circle', 'iconCol' => 'text-emerald-600'],
            'danger', 'error' => ['bg' => 'bg-rose-50 border-rose-200/90 text-rose-900', 'icon' => 'warning-circle', 'iconCol' => 'text-rose-600'],
            'warning'         => ['bg' => 'bg-amber-50 border-amber-200/90 text-amber-900', 'icon' => 'warning', 'iconCol' => 'text-amber-600'],
            'info'            => ['bg' => 'bg-sky-50 border-sky-200/90 text-sky-900', 'icon' => 'info', 'iconCol' => 'text-sky-600'],
            default           => ['bg' => 'bg-brand-50 border-brand-200/90 text-brand-900', 'icon' => 'info', 'iconCol' => 'text-brand-600'],
        };

        $titleHtml = $title ? "<h4 class=\"font-bold text-xs sm:text-sm mb-0.5 tracking-tight\">" . sanitize($title) . "</h4>" : '';
        $dismissHtml = $dismissible 
            ? "<button type=\"button\" @click=\"showAlert = false\" class=\"text-slate-400 hover:text-slate-700 p-1 rounded-btn transition\">" . ui_icon('x', 'text-base') . "</button>" 
            : '';
        
        $xData = $dismissible ? 'x-data="{ showAlert: true }" x-show="showAlert" x-transition' : '';
        $mainIcon = ui_icon($config['icon'], 'text-lg ' . $config['iconCol'] . ' flex-shrink-0 mt-0.5');

        return "
        <div $xData class=\"p-4 rounded-card border {$config['bg']} flex items-start justify-between gap-3 text-xs sm:text-sm $extraCls\">
            <div class=\"flex items-start gap-3\">
                $mainIcon
                <div class=\"space-y-0.5\">
                    $titleHtml
                    <div class=\"text-xs leading-relaxed opacity-90\">$message</div>
                </div>
            </div>
            $dismissHtml
        </div>";
    }
}
