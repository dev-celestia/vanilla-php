<?php
/**
 * Badge Component Primitive (ui_badge / ui_status_badge)
 *
 * Status chips, tags, and indicator pills with live pulse dot support.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_badge')) {
    function ui_badge(string $label, string $variant = 'brand', array $options = []): string {
        $dot      = !empty($options['dot']);
        $pulse    = !empty($options['pulse']);
        $icon     = $options['icon'] ?? null;
        $size     = $options['size'] ?? 'md'; // sm, md, lg
        $rounded  = $options['rounded'] ?? 'badge'; // 'badge', 'full'
        $extraCls = $options['class'] ?? '';

        $variants = [
            'brand'   => 'bg-brand-50 text-brand-700 border-brand-200/80',
            'neutral' => 'bg-slate-100 text-slate-700 border-slate-200/80',
            'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
            'warning' => 'bg-amber-50 text-amber-800 border-amber-200/80',
            'danger'  => 'bg-rose-50 text-rose-700 border-rose-200/80',
            'info'    => 'bg-sky-50 text-sky-700 border-sky-200/80',
            'dark'    => 'bg-slate-900 text-white border-slate-800',
            'glass'   => 'bg-white/70 backdrop-blur-md text-slate-800 border-white/60',
        ];
        $vStyle = $variants[$variant] ?? $variants['brand'];

        $sizeCls = match($size) {
            'sm' => 'px-2 py-0.5 text-[10px] gap-1',
            'lg' => 'px-3 py-1.5 text-xs gap-2',
            default => 'px-2.5 py-1 text-xs gap-1.5',
        };

        $radiusCls = ($rounded === 'full' || $rounded === 'pill') ? 'rounded-full' : 'rounded-badge';

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
            $pulseCls = $pulse ? 'animate-pulse' : '';
            $dotHtml = "<span class=\"w-1.5 h-1.5 rounded-full $dotColor $pulseCls flex-shrink-0\"></span>";
        }

        $iconHtml = $icon ? ui_icon($icon, 'text-xs flex-shrink-0') : '';

        return "<span class=\"inline-flex items-center font-bold tracking-tight border select-none $radiusCls $vStyle $sizeCls $extraCls\">$dotHtml$iconHtml<span>" . sanitize($label) . "</span></span>";
    }
}

if (!function_exists('ui_status_badge')) {
    function ui_status_badge(string $status, array $options = []): string {
        $statusLower = strtolower($status);
        $variant = match($statusLower) {
            'active', 'published', 'completed', 'success', 'paid' => 'success',
            'pending', 'processing', 'in_progress', 'draft' => 'warning',
            'inactive', 'cancelled', 'failed', 'rejected', 'danger' => 'danger',
            'info', 'shipped', 'delivered' => 'info',
            default => 'neutral',
        };
        $options['dot'] = $options['dot'] ?? true;
        return ui_badge(ucfirst($status), $variant, $options);
    }
}
