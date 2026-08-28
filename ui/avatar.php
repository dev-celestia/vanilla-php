<?php
/**
 * Avatar & Icon Box Component Primitives (ui_avatar, ui_avatar_group, ui_icon_box)
 *
 * User profile avatars, stacked avatar groups, and structured icon containers.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_avatar')) {
    function ui_avatar(string $nameOrImage, array $options = []): string {
        $size     = $options['size'] ?? 'md'; // xs(24px), sm(32px), md(40px), lg(48px), xl(64px)
        $rounded  = $options['rounded'] ?? 'avatar';
        $status   = $options['status'] ?? null; // 'online', 'busy', 'offline', 'away'
        $isImage  = !empty($options['isImage']) || str_starts_with($nameOrImage, 'http') || str_starts_with($nameOrImage, '/') || str_ends_with($nameOrImage, '.jpg') || str_ends_with($nameOrImage, '.png');
        $extraCls = $options['class'] ?? '';

        $sizeStyles = [
            'xs' => 'w-6 h-6 text-[10px]',
            'sm' => 'w-8 h-8 text-xs',
            'md' => 'w-10 h-10 text-sm font-semibold',
            'lg' => 'w-12 h-12 text-base font-semibold',
            'xl' => 'w-16 h-16 text-xl font-semibold',
        ];
        $sizeCls = $sizeStyles[$size] ?? $sizeStyles['md'];
        $radiusCls = ($rounded === 'full' || $rounded === 'circle') ? 'rounded-full' : 'rounded-avatar';

        $statusHtml = '';
        if ($status) {
            $statusCol = match($status) {
                'online'  => 'bg-emerald-500 ring-white',
                'busy'    => 'bg-rose-500 ring-white',
                'away'    => 'bg-amber-500 ring-white',
                'offline' => 'bg-slate-400 ring-white',
                default   => 'bg-slate-400 ring-white',
            };
            $dotSize = in_array($size, ['xs', 'sm']) ? 'w-2 h-2 ring-1' : 'w-3 h-3 ring-2';
            $statusHtml = "<span class=\"absolute bottom-0 right-0 rounded-full $dotSize $statusCol\"></span>";
        }

        if ($isImage) {
            $avatarContent = "<img src=\"" . sanitize($nameOrImage) . "\" alt=\"Avatar\" class=\"$sizeCls $radiusCls object-cover border border-slate-200/80 bg-slate-100 $extraCls\" />";
        } else {
            $initial = strtoupper(substr(trim($nameOrImage), 0, 1) ?: 'U');
            $avatarContent = "<div class=\"$sizeCls $radiusCls bg-brand-100 text-brand-700 border border-brand-200/80 flex items-center justify-center select-none $extraCls\">$initial</div>";
        }

        if ($status) {
            return "<div class=\"relative inline-block flex-shrink-0\">$avatarContent$statusHtml</div>";
        }

        return $avatarContent;
    }
}

if (!function_exists('ui_avatar_group')) {
    function ui_avatar_group(array $users = [], array $options = []): string {
        $size      = $options['size'] ?? 'sm';
        $max       = $options['max'] ?? 4;
        $extraCls  = $options['class'] ?? '';

        $total = count($users);
        $displayUsers = array_slice($users, 0, $max);
        $remaining = $total - count($displayUsers);

        $html = '<div class="inline-flex items-center -space-x-2 ' . $extraCls . '">';
        foreach ($displayUsers as $u) {
            $nameOrImg = is_array($u) ? ($u['image'] ?? $u['name'] ?? 'User') : $u;
            $html .= '<div class="ring-2 ring-white rounded-full inline-block">' . ui_avatar($nameOrImg, ['size' => $size, 'rounded' => 'full']) . '</div>';
        }

        if ($remaining > 0) {
            $sizeCls = ($size === 'xs') ? 'w-6 h-6 text-[10px]' : (($size === 'sm') ? 'w-8 h-8 text-xs' : 'w-10 h-10 text-sm');
            $html .= "<div class=\"ring-2 ring-white rounded-full bg-slate-100 border border-slate-200 text-slate-600 font-semibold flex items-center justify-center $sizeCls select-none\">+$remaining</div>";
        }
        $html .= '</div>';

        return $html;
    }
}

if (!function_exists('ui_icon_box')) {
    function ui_icon_box(string $icon, string $variant = 'brand', array $options = []): string {
        $size     = $options['size'] ?? 'md'; // sm, md, lg, xl
        $weight   = $options['weight'] ?? 'regular';
        $extraCls = $options['class'] ?? '';
        $rounded  = $options['rounded'] ?? 'btn';
        
        $sizeStyles = [
            'sm' => ['box' => 'w-8 h-8', 'iconSize' => 'text-base'],
            'md' => ['box' => 'w-10 h-10', 'iconSize' => 'text-lg'],
            'lg' => ['box' => 'w-12 h-12', 'iconSize' => 'text-xl'],
            'xl' => ['box' => 'w-14 h-14', 'iconSize' => 'text-2xl'],
        ];
        $s = $sizeStyles[$size] ?? $sizeStyles['md'];
        $radiusCls = ($rounded === 'full' || $rounded === 'circle') ? 'rounded-full' : 'rounded-btn';

        $variants = [
            'brand'   => 'bg-brand-50 text-brand-600 border border-brand-200/70',
            'primary' => 'bg-brand-600 text-white border border-brand-500/20',
            'slate'   => 'bg-slate-100 text-slate-700 border border-slate-200/80',
            'dark'    => 'bg-slate-900 text-white border border-slate-800',
            'glass'   => 'bg-white/80 backdrop-blur-md text-slate-900 border border-white/60',
            'rose'    => 'bg-rose-50 text-rose-600 border border-rose-200/70',
            'amber'   => 'bg-amber-50 text-amber-600 border border-amber-200/70',
            'sky'     => 'bg-sky-50 text-sky-600 border border-sky-200/70',
        ];
        $vStyle = $variants[$variant] ?? $variants['brand'];

        $iconHtml = ui_icon($icon, $s['iconSize'], $weight);

        return "<div class=\"{$s['box']} $radiusCls $vStyle flex items-center justify-center flex-shrink-0 $extraCls\">$iconHtml</div>";
    }
}

