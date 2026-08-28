<?php
/**
 * Stat Card Component Primitive (ui_stat_card)
 *
 * Dashboard and metric card following Apple Health / Pro Dashboard aesthetics:
 * - Optical typography for large figures
 * - Trend pill indicators with icons
 * - Structured icon containers
 * - Optional glass materials
 */

require_once __DIR__ . '/icon.php';
require_once __DIR__ . '/avatar.php';

if (!function_exists('ui_stat_card')) {
    function ui_stat_card(string $title, string|int $value, array $options = []): string {
        $icon       = $options['icon'] ?? null;
        $subtitle   = $options['subtitle'] ?? null;
        $trend      = $options['trend'] ?? null; // e.g. '+12.4%'
        $trendType  = $options['trendType'] ?? 'up'; // 'up' | 'down' | 'neutral'
        $glass      = !empty($options['glass']);
        $iconVariant = $options['iconVariant'] ?? 'brand';
        $extraCls   = $options['class'] ?? '';

        $bgCls = $glass 
            ? 'bg-white/80 backdrop-blur-xl border border-white/60' 
            : 'bg-white border border-slate-200/80';

        $iconHtml = $icon ? ui_icon_box($icon, $iconVariant, ['size' => 'md', 'rounded' => 'btn']) : '';
        
        $trendHtml = '';
        if ($trend) {
            $tConfig = match($trendType) {
                'up'      => ['col' => 'text-emerald-700 bg-emerald-50 border-emerald-200/80', 'icon' => 'trend-up'],
                'down'    => ['col' => 'text-rose-700 bg-rose-50 border-rose-200/80', 'icon' => 'trend-down'],
                default   => ['col' => 'text-slate-700 bg-slate-100 border-slate-200/80', 'icon' => 'minus'],
            };
            $tIconHtml = ui_icon($tConfig['icon'], 'text-xs flex-shrink-0');
            $trendHtml = "<span class=\"inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-badge border {$tConfig['col']} select-none\">$tIconHtml$trend</span>";
        }

        $subHtml = $subtitle ? "<p class=\"text-xs text-slate-500 mt-1.5 leading-normal\">" . sanitize($subtitle) . "</p>" : '';

        return "
        <div class=\"rounded-card $bgCls p-5 sm:p-6 transition-all duration-150 hover:border-brand-300 $extraCls\">
            <div class=\"flex items-center justify-between gap-3\">
                <span class=\"text-xs font-semibold text-slate-500 uppercase tracking-wider select-none\">" . sanitize($title) . "</span>
                $iconHtml
            </div>
            <div class=\"mt-3 flex items-baseline gap-2.5 flex-wrap\">
                <span class=\"text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight leading-none\">$value</span>
                $trendHtml
            </div>
            $subHtml
        </div>";
    }
}

