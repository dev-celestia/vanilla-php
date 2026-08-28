<?php
/**
 * Avatar & Icon Box Component Primitives (ui_avatar, ui_icon_box)
 *
 * User profile avatars and structured icon containers.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_avatar')) {
    function ui_avatar(string $nameOrImage, array $options = []): string {
        $size     = $options['size'] ?? 'md'; // sm(32px), md(40px), lg(48px), xl(64px)
        $rounded  = $options['rounded'] ?? 'avatar';
        $isImage  = !empty($options['isImage']) || str_starts_with($nameOrImage, 'http') || str_starts_with($nameOrImage, '/');
        $extraCls = $options['class'] ?? '';

        $sizeStyles = [
            'xs' => 'w-6 h-6 text-[10px]',
            'sm' => 'w-8 h-8 text-xs',
            'md' => 'w-10 h-10 text-sm font-bold',
            'lg' => 'w-12 h-12 text-base font-bold',
            'xl' => 'w-16 h-16 text-xl font-extrabold',
        ];
        $sizeCls = $sizeStyles[$size] ?? $sizeStyles['md'];
        $radiusCls = $rounded === 'full' ? 'rounded-full' : 'rounded-avatar';

        if ($isImage) {
            return "<img src=\"" . sanitize($nameOrImage) . "\" alt=\"Avatar\" class=\"$sizeCls $radiusCls object-cover border border-slate-200/80 bg-slate-100 $extraCls\" />";
        }

        $initial = strtoupper(substr(trim($nameOrImage), 0, 1) ?: 'U');
        return "<div class=\"$sizeCls $radiusCls bg-brand-100 text-brand-700 border border-brand-200/80 flex items-center justify-center select-none $extraCls\">$initial</div>";
    }
}

if (!function_exists('ui_icon_box')) {
    function ui_icon_box(string $icon, string $variant = 'brand', array $options = []): string {
        $size     = $options['size'] ?? 'md'; // sm, md, lg
        $weight   = $options['weight'] ?? 'regular';
        $extraCls = $options['class'] ?? '';
        
        $sizeStyles = [
            'sm' => ['box' => 'w-8 h-8 rounded-btn', 'iconSize' => 'text-base'],
            'md' => ['box' => 'w-10 h-10 rounded-btn', 'iconSize' => 'text-lg'],
            'lg' => ['box' => 'w-12 h-12 rounded-card', 'iconSize' => 'text-xl'],
        ];
        $s = $sizeStyles[$size] ?? $sizeStyles['md'];

        $variants = [
            'brand'   => 'bg-brand-50 text-brand-600 border border-brand-200/70',
            'primary' => 'bg-brand-600 text-white border border-brand-500/20',
            'slate'   => 'bg-slate-100 text-slate-700 border border-slate-200/80',
            'dark'    => 'bg-slate-900 text-white border border-slate-800',
        ];
        $vStyle = $variants[$variant] ?? $variants['brand'];

        $iconHtml = ui_icon($icon, $s['iconSize'], $weight);

        return "<div class=\"{$s['box']} $vStyle flex items-center justify-center flex-shrink-0 $extraCls\">$iconHtml</div>";
    }
}
