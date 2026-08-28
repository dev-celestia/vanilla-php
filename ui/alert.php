<?php
/**
 * Alert Component Primitive (ui_alert)
 *
 * Notification banners, status alerts, and contextual messaging.
 * Follows Apple Human Interface principles:
 * - Subtle translucent background or solid crisp colors
 * - Optical alignment of leading icons and actions
 * - Smooth dismissal transitions with Alpine.js
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_alert')) {
    function ui_alert(string $message, string $variant = 'success', array $options = []): string {
        $title       = $options['title'] ?? null;
        $dismissible = !empty($options['dismissible']);
        $glass       = !empty($options['glass']);
        $icon        = $options['icon'] ?? null;
        $extraCls    = $options['class'] ?? '';
        $action      = $options['action'] ?? null;

        $config = match($variant) {
            'success' => [
                'solid'   => 'bg-emerald-50 border-emerald-200/90 text-emerald-950',
                'glass'   => 'bg-emerald-500/10 backdrop-blur-md border-emerald-500/20 text-emerald-950',
                'icon'    => 'check-circle',
                'iconCol' => 'text-emerald-600',
            ],
            'danger', 'error' => [
                'solid'   => 'bg-rose-50 border-rose-200/90 text-rose-950',
                'glass'   => 'bg-rose-500/10 backdrop-blur-md border-rose-500/20 text-rose-950',
                'icon'    => 'warning-circle',
                'iconCol' => 'text-rose-600',
            ],
            'warning' => [
                'solid'   => 'bg-amber-50 border-amber-200/90 text-amber-950',
                'glass'   => 'bg-amber-500/10 backdrop-blur-md border-amber-500/20 text-amber-950',
                'icon'    => 'warning',
                'iconCol' => 'text-amber-600',
            ],
            'info' => [
                'solid'   => 'bg-sky-50 border-sky-200/90 text-sky-950',
                'glass'   => 'bg-sky-500/10 backdrop-blur-md border-sky-500/20 text-sky-950',
                'icon'    => 'info',
                'iconCol' => 'text-sky-600',
            ],
            'neutral', 'dark' => [
                'solid'   => 'bg-slate-900 border-slate-800 text-white',
                'glass'   => 'bg-slate-900/80 backdrop-blur-md border-slate-700/80 text-white',
                'icon'    => 'bell',
                'iconCol' => 'text-brand-400',
            ],
            default => [
                'solid'   => 'bg-brand-50 border-brand-200/90 text-brand-950',
                'glass'   => 'bg-brand-500/10 backdrop-blur-md border-brand-500/20 text-brand-950',
                'icon'    => 'info',
                'iconCol' => 'text-brand-600',
            ],
        };

        $chosenBg = $glass ? $config['glass'] : $config['solid'];
        $finalIconName = $icon ?? $config['icon'];

        $titleHtml = $title ? "<h4 class=\"font-semibold text-xs sm:text-sm mb-0.5 tracking-tight leading-snug\">" . sanitize($title) . "</h4>" : '';
        
        $actionHtml = $action ? "<div class=\"mt-2\">$action</div>" : '';

        $dismissHtml = $dismissible 
            ? "<button type=\"button\" @click=\"showAlert = false\" class=\"text-slate-400 hover:text-slate-700 p-1 rounded-btn transition apple-tap -mr-1 -mt-1\" aria-label=\"Dismiss\">" . ui_icon('x', 'text-base') . "</button>" 
            : '';
        
        $xData = $dismissible ? 'x-data="{ showAlert: true }" x-show="showAlert" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"' : '';
        $mainIcon = ui_icon($finalIconName, 'text-lg ' . $config['iconCol'] . ' flex-shrink-0 mt-0.5');

        return "
        <div $xData class=\"p-4 rounded-card border $chosenBg flex items-start justify-between gap-3 text-xs sm:text-sm transition-all $extraCls\">
            <div class=\"flex items-start gap-3 min-w-0 flex-1\">
                $mainIcon
                <div class=\"min-w-0 flex-1\">
                    $titleHtml
                    <div class=\"text-xs leading-relaxed opacity-90\">$message</div>
                    $actionHtml
                </div>
            </div>
            $dismissHtml
        </div>";
    }
}

