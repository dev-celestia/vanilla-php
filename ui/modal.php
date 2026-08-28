<?php
/**
 * Modal & Sheet Component Primitive (ui_modal)
 *
 * Apple-style floating sheet or centered modal dialog with translucent backdrop blur.
 */

require_once __DIR__ . '/icon.php';
require_once __DIR__ . '/button.php';

if (!function_exists('ui_modal')) {
    /**
     * Renders a modal dialog using Alpine.js.
     *
     * @param string $id Modal identifier or Alpine variable (e.g. 'isOpen')
     * @param string $title Modal title
     * @param string $content HTML content of the modal body
     * @param array $options Options (footer, size, icon, dismissible, isVar)
     */
    function ui_modal(string $id, string $title, string $content, array $options = []): string {
        $subtitle    = $options['subtitle'] ?? null;
        $icon        = $options['icon'] ?? null;
        $footer      = $options['footer'] ?? null;
        $size        = $options['size'] ?? 'md'; // sm, md, lg, xl, full
        $isVar       = !empty($options['isVar']); // If true, $id is an Alpine variable expression
        $extraCls    = $options['class'] ?? '';
        
        $showExpr = $isVar ? $id : "openModal === '$id'";
        $closeExpr = $isVar ? "$id = false" : "openModal = null";

        $maxW = match($size) {
            'sm'   => 'max-w-sm',
            'lg'   => 'max-w-2xl',
            'xl'   => 'max-w-4xl',
            'full' => 'max-w-5xl',
            default => 'max-w-lg',
        };

        $iconHtml = $icon ? "<div class=\"w-9 h-9 rounded-btn bg-brand-50 text-brand-600 flex items-center justify-center flex-shrink-0\">" . ui_icon($icon, 'text-lg') . "</div>" : '';
        $subHtml = $subtitle ? "<p class=\"text-xs text-slate-500 mt-0.5\">" . sanitize($subtitle) . "</p>" : '';

        $footerHtml = '';
        if ($footer) {
            $footerHtml = "<div class=\"px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-2.5 rounded-b-modal\">$footer</div>";
        }

        return "
        <div 
            x-show=\"$showExpr\" 
            x-cloak 
            @keydown.escape.window=\"$closeExpr\" 
            class=\"fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto\"
            role=\"dialog\" 
            aria-modal=\"true\">
            
            <!-- Translucent Scrim / Backdrop -->
            <div 
                x-show=\"$showExpr\" 
                x-transition:enter=\"transition ease-out duration-200\" 
                x-transition:enter-start=\"opacity-0\" 
                x-transition:enter-end=\"opacity-100\" 
                x-transition:leave=\"transition ease-in duration-150\" 
                x-transition:leave-start=\"opacity-100\" 
                x-transition:leave-end=\"opacity-0\" 
                @click=\"$closeExpr\" 
                class=\"fixed inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity\"></div>

            <!-- Modal Surface Card -->
            <div 
                x-show=\"$showExpr\" 
                x-transition:enter=\"transition ease-out duration-200\" 
                x-transition:enter-start=\"opacity-0 scale-95 translate-y-2\" 
                x-transition:enter-end=\"opacity-100 scale-100 translate-y-0\" 
                x-transition:leave=\"transition ease-in duration-150\" 
                x-transition:leave-start=\"opacity-100 scale-100 translate-y-0\" 
                x-transition:leave-end=\"opacity-0 scale-95 translate-y-2\" 
                class=\"relative w-full $maxW bg-white rounded-modal border border-slate-200/90 overflow-hidden z-10 transition-all $extraCls\">
                
                <!-- Header -->
                <div class=\"px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-4\">
                    <div class=\"flex items-center gap-3 min-w-0\">
                        $iconHtml
                        <div class=\"min-w-0\">
                            <h3 class=\"text-base font-bold text-slate-900 tracking-tight leading-snug\">" . sanitize($title) . "</h3>
                            $subHtml
                        </div>
                    </div>
                    <button 
                        type=\"button\" 
                        @click=\"$closeExpr\" 
                        class=\"text-slate-400 hover:text-slate-700 p-1.5 rounded-btn hover:bg-slate-100 transition apple-tap -mr-1\" 
                        aria-label=\"Close\">
                        " . ui_icon('x', 'text-base') . "
                    </button>
                </div>

                <!-- Body -->
                <div class=\"p-6 text-slate-700 text-sm leading-relaxed max-h-[75vh] overflow-y-auto\">
                    $content
                </div>

                <!-- Footer -->
                $footerHtml
            </div>
        </div>";
    }
}
