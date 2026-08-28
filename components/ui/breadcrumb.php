<?php
/**
 * Breadcrumb Component Primitive (ui_breadcrumb)
 *
 * Breadcrumb hierarchy navigation bar with caret separators.
 *
 * @param array $items Array of ['label' => '...', 'href' => '...'] (href is optional for active item)
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_breadcrumb')) {
    function ui_breadcrumb(array $items, array $options = []): string {
        $extraCls = $options['class'] ?? '';
        $caret = ui_icon('caret-right', 'text-slate-300 text-xs');
        
        $linksHtml = [];
        $total = count($items);
        $i = 0;

        foreach ($items as $item) {
            $i++;
            $label = sanitize($item['label'] ?? '');
            $href = $item['href'] ?? null;
            $isLast = ($i === $total);

            if ($href && !$isLast) {
                $linksHtml[] = "<a href=\"" . sanitize($href) . "\" class=\"hover:text-brand-600 transition\">$label</a>";
            } else {
                $linksHtml[] = "<span class=\"text-slate-800 font-semibold truncate max-w-xs sm:max-w-md\">$label</span>";
            }
        }

        $inner = implode(" $caret ", $linksHtml);

        return "
        <div class=\"bg-white border-b border-slate-200/80 py-3.5 $extraCls\">
            <div class=\"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8\">
                <nav class=\"flex text-xs text-slate-500 gap-2 items-center flex-wrap\">
                    $inner
                </nav>
            </div>
        </div>";
    }
}
