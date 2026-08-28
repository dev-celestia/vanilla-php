<?php
/**
 * Stat Card Component Primitive (ui_stat_card)
 *
 * Dashboard metrics card with icon box, value display, and trend indicators.
 */

require_once __DIR__ . '/icon.php';
require_once __DIR__ . '/avatar.php';

if (!function_exists('ui_stat_card')) {
    function ui_stat_card(string $title, string|int $value, array $options = []): string {
        $icon      = $options['icon'] ?? null;
        $subtitle  = $options['subtitle'] ?? null;
        $trend     = $options['trend'] ?? null; // e.g. '+12%'
        $trendType = $options['trendType'] ?? 'up'; // 'up' | 'down'
        $extraCls  = $options['class'] ?? '';

        $iconHtml = $icon ? ui_icon_box($icon, 'brand', ['size' => 'md']) : '';
        
        $trendHtml = '';
        if ($trend) {
            $tCol = $trendType === 'up' ? 'text-emerald-600 bg-emerald-50 border-emerald-200/80' : 'text-rose-600 bg-rose-50 border-rose-200/80';
            $tIcon = $trendType === 'up' ? 'trend-up' : 'trend-down';
            $tIconHtml = ui_icon($tIcon, 'text-xs');
            $trendHtml = "<span class=\"inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-badge border $tCol\">$tIconHtml$trend</span>";
        }

        $subHtml = $subtitle ? "<p class=\"text-xs text-slate-500 mt-1\">" . sanitize($subtitle) . "</p>" : '';

        return "
        <div class=\"rounded-card bg-white border border-slate-200/80 p-5 sm:p-6 transition hover:border-brand-300 $extraCls\">
            <div class=\"flex items-center justify-between gap-3\">
                <span class=\"text-xs font-bold text-slate-500 uppercase tracking-wider\">" . sanitize($title) . "</span>
                $iconHtml
            </div>
            <div class=\"mt-3 flex items-baseline gap-3\">
                <span class=\"text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight\">$value</span>
                $trendHtml
            </div>
            $subHtml
        </div>";
    }
}
