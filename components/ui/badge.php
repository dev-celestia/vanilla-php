<?php
/**
 * Badge Component Primitive (ui_badge)
 *
 * Status chips, tags, and indicator pills with live dot support.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_badge')) {
    function ui_badge(string $label, string $variant = 'brand', array $options = []): string {
        $dot      = !empty($options['dot']);
        $icon     = $options['icon'] ?? null;
        $size     = $options['size'] ?? 'md'; // sm, md
        $extraCls = $options['class'] ?? '';

        $variants = [
            'brand'   => 'bg-brand-50 text-brand-700 border-brand-200/80 dot:bg-brand-500',
            'neutral' => 'bg-slate-100 text-slate-700 border-slate-200/80 dot:bg-slate-400',
            'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80 dot:bg-emerald-500',
            'warning' => 'bg-amber-50 text-amber-800 border-amber-200/80 dot:bg-amber-500',
            'danger'  => 'bg-rose-50 text-rose-700 border-rose-200/80 dot:bg-rose-500',
            'info'    => 'bg-sky-50 text-sky-700 border-sky-200/80 dot:bg-sky-500',
            'dark'    => 'bg-slate-900 text-white border-slate-800 dot:bg-brand-400',
        ];
        $vStyle = $variants[$variant] ?? $variants['brand'];

        $sizeCls = $size === 'sm' ? 'px-2 py-0.5 text-[10px]' : 'px-2.5 py-1 text-xs';

        $dotHtml = '';
        if ($dot) {
            $dotColor = match($variant) {
                'neutral' => 'bg-slate-400',
                'success' => 'bg-emerald-500',
                'warning' => 'bg-amber-500',
                'danger'  => 'bg-rose-500',
                'info'    => 'bg-sky-500',
                'dark'    => 'bg-brand-400',
                default   => 'bg-brand-500',
            };
            $dotHtml = "<span class=\"w-1.5 h-1.5 rounded-full $dotColor\"></span>";
        }

        $iconHtml = $icon ? ui_icon($icon, 'text-xs') : '';

        return "<span class=\"inline-flex items-center gap-1.5 font-bold tracking-tight rounded-badge border $vStyle $sizeCls $extraCls\">$dotHtml$iconHtml<span>" . sanitize($label) . "</span></span>";
    }
}
