<?php
/**
 * Empty State Component Primitive (ui_empty_state)
 *
 * Visual placeholder for empty lists, search results, carts, or 404 views.
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

        $btnHtml = '';
        if ($buttonText && $buttonHref) {
            $btnHtml = '<div class="mt-6">' . ui_button($buttonText, [
                'variant' => 'primary',
                'size'    => 'sm',
                'href'    => $buttonHref,
                'icon'    => $buttonIcon,
            ]) . '</div>';
        } elseif ($actionHtml) {
            $btnHtml = '<div class="mt-6">' . $actionHtml . '</div>';
        }

        $iconHtml = ui_icon($icon, 'text-3xl');

        return "
        <div class=\"bg-white rounded-card border border-slate-200/80 p-12 sm:p-16 text-center max-w-xl mx-auto my-6 $extraCls\">
            <div class=\"w-16 h-16 rounded-card bg-slate-100 border border-slate-200/80 text-slate-400 flex items-center justify-center mx-auto mb-4\">
                $iconHtml
            </div>
            <h3 class=\"text-base sm:text-lg font-bold text-slate-900 tracking-tight\">" . sanitize($title) . "</h3>
            <p class=\"text-xs text-slate-500 max-w-md mx-auto mt-1 leading-relaxed\">" . sanitize($description) . "</p>
            $btnHtml
        </div>";
    }
}
