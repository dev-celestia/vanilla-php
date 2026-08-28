<?php
/**
 * Breadcrumb Component Primitive (ui_breadcrumb)
 *
 * Navigation trail bar with caret separators and home icon support.
 *
 * @param array $items Array of ['label' => '...', 'href' => '...', 'icon' => '...']
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_breadcrumb')) {
    function ui_breadcrumb(array $items, array $options = []): string {
        $extraCls   = $options['class'] ?? '';
        $showHome   = $options['showHome'] ?? true;
        $caret      = ui_icon('caret-right', 'text-slate-300 text-xs flex-shrink-0');
        
        $linksHtml = [];

        if ($showHome) {
            $homeIcon = ui_icon('house', 'text-xs flex-shrink-0');
            $homeHref = function_exists('base_url') ? base_url() : '/';
            $linksHtml[] = "<a href=\"$homeHref\" class=\"hover:text-brand-600 transition-colors flex items-center gap-1.5\">$homeIcon<span class=\"hidden sm:inline\">Home</span></a>";
        }

        $total = count($items);
        $i = 0;

        foreach ($items as $item) {
            $i++;
            $label = sanitize($item['label'] ?? '');
            $href = $item['href'] ?? null;
            $icon = $item['icon'] ?? null;
            $isLast = ($i === $total);

            $iconHtml = $icon ? ui_icon($icon, 'text-xs flex-shrink-0 mr-1') : '';

            if ($href && !$isLast) {
                $linksHtml[] = "<a href=\"" . sanitize($href) . "\" class=\"hover:text-brand-600 transition-colors flex items-center\">$iconHtml$label</a>";
            } else {
                $linksHtml[] = "<span class=\"text-slate-900 font-semibold truncate max-w-xs sm:max-w-md flex items-center\">$iconHtml$label</span>";
            }
        }

        $inner = implode(" $caret ", $linksHtml);

        return "
        <div class=\"bg-white/80 backdrop-blur-md border-b border-slate-200/80 py-3 $extraCls\">
            <div class=\"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8\">
                <nav class=\"flex text-xs text-slate-500 gap-2 items-center flex-wrap select-none\" aria-label=\"Breadcrumb\">
                    $inner
                </nav>
            </div>
        </div>";
    }
}

