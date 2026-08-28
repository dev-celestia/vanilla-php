<?php
/**
 * Button Component Primitive (ui_button / ui_btn)
 *
 * Renders an Apple-feel button or link with tactile press animation and tokens.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_button')) {
    function ui_button(string $label, array $options = []): string {
        $variant   = $options['variant'] ?? 'primary';
        $size      = $options['size'] ?? 'md';
        $href      = $options['href'] ?? null;
        $type      = $options['type'] ?? 'button';
        $icon      = $options['icon'] ?? null;
        $iconRight = $options['iconRight'] ?? null;
        $extraCls  = $options['class'] ?? '';
        $attrs     = $options['attrs'] ?? '';
        $disabled  = !empty($options['disabled']);
        $rounded   = $options['rounded'] ?? 'btn'; // matches rounded-btn from tokens
        $id        = !empty($options['id']) ? 'id="' . sanitize($options['id']) . '"' : '';
        $name      = !empty($options['name']) ? 'name="' . sanitize($options['name']) . '"' : '';
        $value     = !empty($options['value']) ? 'value="' . sanitize($options['value']) . '"' : '';
        $target    = !empty($options['target']) ? 'target="' . sanitize($options['target']) . '" rel="noopener noreferrer"' : '';

        // Base structural styling + Apple pointer-down fluid scale
        $baseCls = "inline-flex items-center justify-center font-bold select-none cursor-pointer transition-all duration-150 ease-out apple-tap focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2";
        
        // Radius class based on token
        $radiusCls = $rounded === 'full' ? 'rounded-full' : 'rounded-btn';

        // Size variants
        $sizeStyles = [
            'xs' => 'text-[11px] px-2.5 py-1.5 gap-1.5 tracking-tight',
            'sm' => 'text-xs px-3.5 py-2 gap-2 tracking-tight',
            'md' => 'text-xs sm:text-sm px-4 sm:px-5 py-2.5 sm:py-3 gap-2.5 tracking-tight',
            'lg' => 'text-sm sm:text-base px-6 py-3.5 sm:py-4 gap-3 tracking-tight',
        ];
        $sizeCls = $sizeStyles[$size] ?? $sizeStyles['md'];

        // Visual variants (zero shadow, clean flat borders, translucent layers)
        $variantStyles = [
            'primary'   => 'bg-brand-600 hover:bg-brand-700 text-white border border-brand-500/20 active:bg-brand-800',
            'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200/80 active:bg-slate-300',
            'outline'   => 'bg-transparent hover:bg-brand-50 text-brand-600 border border-brand-300 hover:border-brand-500 active:bg-brand-100',
            'ghost'     => 'bg-transparent hover:bg-slate-100 text-slate-600 hover:text-slate-900 border border-transparent active:bg-slate-200',
            'danger'    => 'bg-rose-600 hover:bg-rose-700 text-white border border-rose-500/20 active:bg-rose-800',
            'subtle'    => 'bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200/80 active:bg-brand-200',
            'white'     => 'bg-white hover:bg-slate-50 text-slate-900 border border-slate-200 active:bg-slate-100',
        ];
        $variantCls = $variantStyles[$variant] ?? $variantStyles['primary'];

        if ($disabled) {
            $baseCls .= " opacity-50 cursor-not-allowed pointer-events-none";
        }

        $allClasses = trim("$baseCls $radiusCls $sizeCls $variantCls $extraCls");

        // Icon rendering using Phosphor Icons
        $iconHtml = '';
        if ($icon) {
            $iconSize = in_array($size, ['xs', 'sm']) ? 'text-xs' : 'text-sm';
            $iconHtml = ui_icon($icon, $iconSize . ' flex-shrink-0');
        }

        $iconRightHtml = '';
        if ($iconRight) {
            $iconSize = in_array($size, ['xs', 'sm']) ? 'text-xs' : 'text-sm';
            $iconRightHtml = ui_icon($iconRight, $iconSize . ' flex-shrink-0');
        }

        if ($href) {
            return "<a href=\"" . sanitize($href) . "\" class=\"$allClasses\" $target $attrs $id>$iconHtml<span>$label</span>$iconRightHtml</a>";
        }

        $disAttr = $disabled ? 'disabled' : '';
        return "<button type=\"$type\" class=\"$allClasses\" $disAttr $name $value $attrs $id>$iconHtml<span>$label</span>$iconRightHtml</button>";
    }
}

if (!function_exists('ui_btn')) {
    // Shorthand alias
    function ui_btn(string $label, array $options = []): string {
        return ui_button($label, $options);
    }
}
