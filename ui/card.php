<?php
/**
 * Card Component Primitive (ui_card / ui_card_interactive)
 *
 * Surface & container following Apple Human Interface Guidelines:
 * - Crisp hairline borders (1px)
 * - Translucent materials (backdrop-filter: blur(20px))
 * - Proportional padding & hierarchical header/footer slots
 * - Fluid pointer feedback on interactive cards
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_card')) {
    function ui_card(string $content, array $options = []): string {
        $title        = $options['title'] ?? null;
        $subtitle     = $options['subtitle'] ?? null;
        $icon         = $options['icon'] ?? null;
        $headerAction = $options['headerAction'] ?? null;
        $footer       = $options['footer'] ?? null;
        $glass        = !empty($options['glass']); // Translucent Apple material
        $variant      = $options['variant'] ?? ($glass ? 'glass' : 'default');
        $extraCls     = $options['class'] ?? '';
        $padding      = $options['padding'] ?? 'p-5 sm:p-6';
        $attrs        = $options['attrs'] ?? '';

        $bgCls = match($variant) {
            'glass'   => 'bg-white/80 backdrop-blur-xl border border-white/60',
            'subtle'  => 'bg-slate-50 border border-slate-200/80',
            'dark'    => 'bg-slate-900 border border-slate-800 text-white',
            'outline' => 'bg-transparent border border-slate-200',
            default   => 'bg-white border border-slate-200/80',
        };

        $headerHtml = '';
        if ($title || $icon || $headerAction) {
            $iconHtml = $icon ? "<div class=\"w-8 h-8 rounded-btn bg-brand-50 text-brand-600 flex items-center justify-center flex-shrink-0\">" . ui_icon($icon, 'text-base') . "</div>" : '';
            $titleHtml = $title ? "<h3 class=\"text-sm sm:text-base font-semibold text-slate-900 tracking-tight leading-snug\">" . sanitize($title) . "</h3>" : '';
            $subHtml = $subtitle ? "<p class=\"text-xs text-slate-500 mt-0.5 leading-normal\">" . sanitize($subtitle) . "</p>" : '';
            $actionHtml = $headerAction ? "<div class=\"flex-shrink-0\">$headerAction</div>" : '';

            $headerBorder = ($variant === 'dark') ? 'border-slate-800' : 'border-slate-100';

            $headerHtml = "
            <div class=\"px-5 sm:px-6 py-4 border-b $headerBorder flex items-center justify-between gap-4\">
                <div class=\"flex items-center gap-3 min-w-0\">
                    $iconHtml
                    <div class=\"min-w-0\">
                        $titleHtml
                        $subHtml
                    </div>
                </div>
                $actionHtml
            </div>";
        }

        $footerHtml = '';
        if ($footer) {
            $footerBorder = ($variant === 'dark') ? 'border-slate-800 bg-slate-950/40' : 'border-slate-100 bg-slate-50/70';
            $footerHtml = "<div class=\"px-5 sm:px-6 py-3.5 $footerBorder border-t rounded-b-card\">$footer</div>";
        }

        return "
        <div class=\"rounded-card $bgCls overflow-hidden transition-all duration-150 $extraCls\" $attrs>
            $headerHtml
            <div class=\"$padding\">
                $content
            </div>
            $footerHtml
        </div>";
    }
}

if (!function_exists('ui_card_interactive')) {
    function ui_card_interactive(string $content, array $options = []): string {
        $href = $options['href'] ?? '#';
        $options['class'] = trim(($options['class'] ?? '') . ' block group apple-tap hover:border-brand-400 hover:bg-slate-50/50 cursor-pointer');
        $cardHtml = ui_card($content, $options);
        return "<a href=\"" . sanitize($href) . "\" class=\"block no-underline\">$cardHtml</a>";
    }
}

