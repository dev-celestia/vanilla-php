<?php
/**
 * Button Component Primitive (ui_button / ui_btn / ui_button_group)
 *
 * Renders Apple Human Interface compliant buttons, links, and segmented button groups.
 * Features:
 * - Fluid pointer-down tactile feedback (.apple-tap)
 * - Translucent materials and crisp hairline borders
 * - Size-specific typography & optical padding
 * - Icon-only, loading state, and button group support
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_button')) {
    function ui_button(string $label, array $options = []): string {
        $variant   = $options['variant'] ?? 'primary';
        $size      = $options['size'] ?? 'md'; // xs, sm, md, lg, xl
        $href      = $options['href'] ?? null;
        $type      = $options['type'] ?? 'button';
        $icon      = $options['icon'] ?? null;
        $iconRight = $options['iconRight'] ?? null;
        $iconOnly  = !empty($options['iconOnly']);
        $loading   = !empty($options['loading']);
        $extraCls  = $options['class'] ?? '';
        $attrs     = $options['attrs'] ?? '';
        $disabled  = !empty($options['disabled']) || $loading;
        $rounded   = $options['rounded'] ?? 'btn'; // 'btn', 'full', 'card', 'none'
        $id        = !empty($options['id']) ? 'id="' . sanitize($options['id']) . '"' : '';
        $name      = !empty($options['name']) ? 'name="' . sanitize($options['name']) . '"' : '';
        $value     = !empty($options['value']) ? 'value="' . sanitize($options['value']) . '"' : '';
        $target    = !empty($options['target']) ? 'target="' . sanitize($options['target']) . '" rel="noopener noreferrer"' : '';

        // Base structural styling + Apple pointer-down fluid scale & instant response
        $baseCls = "inline-flex items-center justify-center font-semibold select-none cursor-pointer transition-all duration-150 ease-out apple-tap focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2";
        
        // Radius mapping
        $radiusCls = match($rounded) {
            'full', 'pill' => 'rounded-full',
            'none'         => 'rounded-none',
            'card'         => 'rounded-card',
            default        => 'rounded-btn',
        };

        // Size variants (with optical sizing & padding)
        if ($iconOnly) {
            $sizeStyles = [
                'xs' => 'w-7 h-7 text-xs',
                'sm' => 'w-8 h-8 text-xs',
                'md' => 'w-10 h-10 text-sm',
                'lg' => 'w-12 h-12 text-base',
                'xl' => 'w-14 h-14 text-lg',
            ];
        } else {
            $sizeStyles = [
                'xs' => 'text-[11px] px-2.5 py-1 gap-1.5 tracking-tight',
                'sm' => 'text-xs px-3.5 py-2 gap-2 tracking-tight',
                'md' => 'text-xs sm:text-sm px-4 sm:px-5 py-2.5 sm:py-2.5 gap-2 tracking-tight',
                'lg' => 'text-sm sm:text-base px-6 py-3 sm:py-3.5 gap-2.5 tracking-tight',
                'xl' => 'text-base sm:text-lg px-7 py-4 gap-3 tracking-tight font-semibold',
            ];
        }
        $sizeCls = $sizeStyles[$size] ?? $sizeStyles['md'];

        // Visual variants following Apple materials & depth
        $variantStyles = [
            'primary'   => 'bg-brand-600 hover:bg-brand-700 text-white border border-brand-500/20 active:bg-brand-800',
            'secondary' => 'bg-slate-100 hover:bg-slate-200/80 text-slate-800 border border-slate-200/80 active:bg-slate-300',
            'outline'   => 'bg-transparent hover:bg-brand-50/60 text-brand-600 border border-brand-300 hover:border-brand-500 active:bg-brand-100',
            'ghost'     => 'bg-transparent hover:bg-slate-100/80 text-slate-600 hover:text-slate-900 border border-transparent active:bg-slate-200',
            'danger'    => 'bg-rose-600 hover:bg-rose-700 text-white border border-rose-500/20 active:bg-rose-800',
            'subtle'    => 'bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200/80 active:bg-brand-200',
            'white'     => 'bg-white hover:bg-slate-50 text-slate-900 border border-slate-200 active:bg-slate-100',
            'glass'     => 'bg-white/70 hover:bg-white/90 backdrop-blur-md text-slate-900 border border-white/50 hover:border-slate-300/80',
            'dark'      => 'bg-slate-900 hover:bg-slate-800 text-white border border-slate-800 active:bg-slate-950',
        ];
        $variantCls = $variantStyles[$variant] ?? $variantStyles['primary'];

        if ($disabled) {
            $baseCls .= " opacity-50 cursor-not-allowed pointer-events-none";
        }

        $allClasses = trim("$baseCls $radiusCls $sizeCls $variantCls $extraCls");

        // Icon rendering
        $iconHtml = '';
        if ($loading) {
            $iconHtml = '<span class="inline-block animate-spin mr-1.5"><i class="ph ph-spinner text-sm"></i></span>';
        } elseif ($icon) {
            $iconSize = in_array($size, ['xs', 'sm']) ? 'text-xs' : 'text-sm';
            $iconHtml = ui_icon($icon, $iconSize . ' flex-shrink-0');
        }

        $iconRightHtml = '';
        if ($iconRight && !$loading) {
            $iconSize = in_array($size, ['xs', 'sm']) ? 'text-xs' : 'text-sm';
            $iconRightHtml = ui_icon($iconRight, $iconSize . ' flex-shrink-0');
        }

        $content = $iconOnly 
            ? "$iconHtml" 
            : "$iconHtml<span>$label</span>$iconRightHtml";

        if ($href) {
            return "<a href=\"" . sanitize($href) . "\" class=\"$allClasses\" $target $attrs $id>$content</a>";
        }

        $disAttr = $disabled ? 'disabled' : '';
        return "<button type=\"$type\" class=\"$allClasses\" $disAttr $name $value $attrs $id>$content</button>";
    }
}

if (!function_exists('ui_btn')) {
    function ui_btn(string $label, array $options = []): string {
        return ui_button($label, $options);
    }
}

/**
 * Button Group / Segmented Control Container
 */
if (!function_exists('ui_button_group')) {
    function ui_button_group(array $buttons = [], array $options = []): string {
        $extraCls = $options['class'] ?? '';
        $rounded  = $options['rounded'] ?? 'btn';
        $radiusCls = $rounded === 'full' ? 'rounded-full' : 'rounded-btn';

        $renderedButtons = [];
        $total = count($buttons);
        $i = 0;

        foreach ($buttons as $btn) {
            $i++;
            $label = $btn['label'] ?? '';
            $btnOpts = $btn['options'] ?? [];
            $btnOpts['rounded'] = 'none';
            
            // Adjust borders for connected segments
            $borderCls = ($i > 1) ? '-ml-[1px]' : '';
            $btnOpts['class'] = trim(($btnOpts['class'] ?? '') . ' ' . $borderCls);

            $renderedButtons[] = ui_button($label, $btnOpts);
        }

        $html = implode('', $renderedButtons);
        return "<div class=\"inline-flex items-center overflow-hidden $radiusCls border border-slate-200/80 $extraCls\" role=\"group\">$html</div>";
    }
}
