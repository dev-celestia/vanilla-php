<?php
/**
 * Card Component Primitive (ui_card)
 *
 * Surface & Card container following Apple design principles:
 * - Crisp hairline borders
 * - Translucent glass materials option
 * - Modular header, icon, action, and footer slots
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_card')) {
    function ui_card(string $content, array $options = []): string {
        $title        = $options['title'] ?? null;
        $subtitle     = $options['subtitle'] ?? null;
        $icon         = $options['icon'] ?? null;
        $headerAction = $options['headerAction'] ?? null;
        $footer       = $options['footer'] ?? null;
        $glass        = !empty($options['glass']); // translucent Apple material
        $extraCls     = $options['class'] ?? '';
        $padding      = $options['padding'] ?? 'p-5 sm:p-6';

        $bgCls = $glass 
            ? 'bg-white/85 backdrop-blur-md border border-slate-200/70' 
            : 'bg-white border border-slate-200/80';

        $headerHtml = '';
        if ($title || $icon || $headerAction) {
            $iconHtml = $icon ? "<div class=\"w-8 h-8 rounded-btn bg-brand-50 text-brand-600 flex items-center justify-center\">" . ui_icon($icon, 'text-base') . "</div>" : '';
            $titleHtml = $title ? "<h3 class=\"text-sm sm:text-base font-bold text-slate-900 tracking-tight\">" . sanitize($title) . "</h3>" : '';
            $subHtml = $subtitle ? "<p class=\"text-xs text-slate-500 mt-0.5\">" . sanitize($subtitle) . "</p>" : '';
            $actionHtml = $headerAction ? "<div>$headerAction</div>" : '';

            $headerHtml = "
            <div class=\"px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-4\">
                <div class=\"flex items-center gap-3\">
                    $iconHtml
                    <div>
                        $titleHtml
                        $subHtml
                    </div>
                </div>
                $actionHtml
            </div>";
        }

        $footerHtml = '';
        if ($footer) {
            $footerHtml = "<div class=\"px-5 sm:px-6 py-3.5 bg-slate-50/70 border-t border-slate-100 rounded-b-card\">$footer</div>";
        }

        return "
        <div class=\"rounded-card $bgCls overflow-hidden $extraCls\">
            $headerHtml
            <div class=\"$padding\">
                $content
            </div>
            $footerHtml
        </div>";
    }
}
