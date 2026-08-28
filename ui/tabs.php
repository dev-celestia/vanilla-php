<?php
/**
 * Tabs & Segmented Control Component Primitive (ui_tabs / ui_segmented_control)
 *
 * Apple-style tactile sliding segmented control and tab navigation bar.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_segmented_control')) {
    /**
     * Renders an Apple-style segmented control bar using Alpine.js for active state.
     *
     * @param string $xModel The Alpine data property name to bind to (e.g. "activeTab")
     * @param array $items Array of ['key' => '...', 'label' => '...', 'icon' => '...']
     */
    function ui_segmented_control(string $xModel, array $items, array $options = []): string {
        $size     = $options['size'] ?? 'md'; // sm, md, lg
        $extraCls = $options['class'] ?? '';
        $rounded  = $options['rounded'] ?? 'btn';

        $radiusCls = ($rounded === 'full' || $rounded === 'pill') ? 'rounded-full' : 'rounded-btn';

        $sizeStyles = match($size) {
            'sm' => 'p-0.5 text-xs',
            'lg' => 'p-1.5 text-sm sm:text-base font-semibold',
            default => 'p-1 text-xs sm:text-sm font-semibold',
        };

        $itemPadding = match($size) {
            'sm' => 'px-2.5 py-1 gap-1',
            'lg' => 'px-5 py-2.5 gap-2',
            default => 'px-3.5 py-1.5 gap-1.5',
        };

        $buttonsHtml = '';
        foreach ($items as $item) {
            $key = sanitize($item['key'] ?? '');
            $label = sanitize($item['label'] ?? '');
            $icon = $item['icon'] ?? null;
            $iconHtml = $icon ? ui_icon($icon, 'text-xs flex-shrink-0') : '';

            $buttonsHtml .= "
            <button 
                type=\"button\" 
                @click=\"$xModel = '$key'\" 
                :class=\"$xModel === '$key' ? 'bg-white text-slate-900 border border-slate-200/80 shadow-none' : 'text-slate-500 hover:text-slate-900 border border-transparent'\" 
                class=\"flex items-center justify-center $itemPadding $radiusCls transition-all duration-150 ease-out apple-tap select-none tracking-tight\">
                $iconHtml
                <span>$label</span>
            </button>";
        }

        return "
        <div class=\"inline-flex items-center bg-slate-100/90 border border-slate-200/70 $radiusCls $sizeStyles select-none $extraCls\" role=\"tablist\">
            $buttonsHtml
        </div>";
    }
}

if (!function_exists('ui_tabs')) {
    function ui_tabs(string $xModel, array $items, array $options = []): string {
        return ui_segmented_control($xModel, $items, $options);
    }
}
