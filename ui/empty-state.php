<?php
/**
 * Empty State Component Primitive (ui_empty_state)
 *
 * Placeholder surface for empty lists, search results, carts, or zero data states.
 */

require_once __DIR__ . '/icon.php';
require_once __DIR__ . '/button.php';

if (!function_exists('ui_empty_state')) {
    function ui_empty_state(string $title, string $description = '', array $options = []): string {
        $icon       = $options['icon'] ?? 'package';
        $buttonText = $options['buttonText'] ?? null;
        $buttonHref = $options['buttonHref'] ?? null;
        $buttonIcon = $options['buttonIcon'] ?? null;
        $extraCls   = $options['class'] ?? '';
        $actionHtml = $options['actionHtml'] ?? '';
        $glass      = !empty($options['glass']);

        $bgCls = $glass 
            ? 'bg-white/80 backdrop-blur-xl border border-white/60' 
            : 'bg-white border border-slate-200/80';

        $btnHtml = '';
        if ($buttonText && $buttonHref) {
            $btnHtml = '<div class="mt-6">' . ui_button($buttonText, [
                'variant' => 'primary',
                'size'    => 'md',
                'href'    => $buttonHref,
                'icon'    => $buttonIcon,
            ]) . '</div>';
        } elseif ($actionHtml) {
            $btnHtml = '<div class="mt-6">' . $actionHtml . '</div>';
        }

        $iconHtml = ui_icon($icon, 'text-3xl text-slate-400');

        return "
        <div class=\"$bgCls rounded-card p-10 sm:p-14 text-center max-w-xl mx-auto my-6 transition-all $extraCls\">
            <div class=\"w-16 h-16 rounded-card bg-slate-100/80 border border-slate-200/80 flex items-center justify-center mx-auto mb-4\">
                $iconHtml
            </div>
            <h3 class=\"text-base sm:text-lg font-semibold text-slate-900 tracking-tight leading-snug\">" . sanitize($title) . "</h3>
            <p class=\"text-xs sm:text-sm text-slate-500 max-w-md mx-auto mt-1.5 leading-relaxed\">" . sanitize($description) . "</p>
            $btnHtml
        </div>";
    }
}
