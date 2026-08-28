<?php
/**
 * Design System Primitive Components Library
 * 
 * Provides expressive, lightweight UI primitives following Apple Design principles:
 * - Fluid tactile pointer feedback (instant press response, active scale)
 * - Zero drop-shadows (flat surfaces, crisp hairline borders, glass translucent layers)
 * - Dynamic tokenized border radius & primary color palette
 * - Accessible, responsive, and zero unnecessary overhead (Ponytail architecture)
 */

require_once __DIR__ . '/format.php';

/**
 * --------------------------------------------------------------------------
 * 0. Phosphor Icon Primitive (ui_icon)
 * --------------------------------------------------------------------------
 * Renders a Phosphor Icon (https://phosphoricons.com/) with weight & class support.
 *
 * @param string $name     Icon name (e.g. 'shopping-bag', 'magnifying-glass', 'heart')
 * @param string $classes  Tailwind or CSS classes (e.g. 'w-4 h-4 text-brand-600')
 * @param string $weight   'regular' | 'bold' | 'fill' | 'light' | 'thin' | 'duotone'
 */
function ui_icon(string $name, string $classes = '', string $weight = 'regular'): string {
    $prefix = match($weight) {
        'bold'    => 'ph-bold',
        'fill'    => 'ph-fill',
        'light'   => 'ph-light',
        'thin'    => 'ph-thin',
        'duotone' => 'ph-duotone',
        default   => 'ph',
    };

    // Normalize Lucide or legacy icon names to Phosphor icons (https://phosphoricons.com)
    $iconMap = [
        'search'           => 'magnifying-glass',
        'search-plus'      => 'magnifying-glass-plus',
        'package-search'   => 'package',
        'package-check'    => 'package',
        'shopping-bag'     => 'shopping-bag',
        'shopping-cart'    => 'shopping-cart',
        'trash-2'          => 'trash',
        'trash'            => 'trash',
        'edit-2'           => 'pencil-simple',
        'edit'             => 'pencil-simple',
        'check-circle-2'   => 'check-circle',
        'check-circle'     => 'check-circle',
        'alert-circle'     => 'warning-circle',
        'alert-triangle'   => 'warning',
        'arrow-up-down'    => 'arrows-down-up',
        'message-circle'   => 'chat-circle-dots',
        'message-square'   => 'chat-teardrop-text',
        'chevron-right'    => 'caret-right',
        'chevron-left'     => 'caret-left',
        'chevron-down'     => 'caret-down',
        'chevron-up'       => 'caret-up',
        'external-link'    => 'arrow-square-out',
        'layout-dashboard' => 'squares-four',
        'settings'         => 'gear',
        'log-out'          => 'sign-out',
        'log-in'           => 'sign-in',
        'logout'           => 'sign-out',
        'login'            => 'sign-in',
        'menu'             => 'list',
        'sliders'          => 'sliders-horizontal',
        'sparkles'         => 'sparkle',
        'dollar-sign'      => 'currency-dollar',
        'tags'             => 'tag',
        'award'            => 'medal',
        'mail'             => 'envelope-simple',
        'trending-up'      => 'trend-up',
        'trending-down'    => 'trend-down',
        'instagram'        => 'instagram-logo',
        'facebook'         => 'facebook-logo',
        'whatsapp'         => 'whatsapp-logo',
        'phone-call'       => 'phone-call',
    ];

    $cleanName = str_starts_with($name, 'ph-') ? substr($name, 3) : $name;
    $finalName = $iconMap[$cleanName] ?? $cleanName;

    $clsAttr = trim($prefix . ' ph-' . $finalName . ' ' . $classes);
    return '<i class="' . htmlspecialchars($clsAttr, ENT_QUOTES, 'UTF-8') . '"></i>';
}

/**
 * --------------------------------------------------------------------------
 * 1. Button Primitive (ui_button / ui_btn)
 * --------------------------------------------------------------------------
 * Renders an Apple-feel button or link with tactile press animation and tokens.
 *
 * @param string $label    Button text or HTML content
 * @param array  $options  [
 *   'variant'   => 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'subtle' | 'white',
 *   'size'      => 'xs' | 'sm' | 'md' | 'lg',
 *   'type'      => 'button' | 'submit' | 'reset',
 *   'href'      => string (if provided, renders as <a>),
 *   'target'    => '_blank' | null,
 *   'icon'      => 'shopping-bag' (Phosphor icon name placed before text),
 *   'iconRight' => 'arrow-right' (Phosphor icon name placed after text),
 *   'class'     => additional CSS classes,
 *   'attrs'     => additional raw attributes like 'x-on:click="..."',
 *   'disabled'  => bool,
 *   'rounded'   => 'btn' | 'full' | null (defaults to design token 'btn'),
 *   'id'        => string,
 *   'name'      => string,
 *   'value'     => string,
 * ]
 */
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

    // Visual variants (strictly zero shadow, clean flat borders, translucent layers)
    $variantStyles = [
        'primary' => 'bg-brand-600 hover:bg-brand-700 text-white border border-brand-500/20 active:bg-brand-800',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200/80 active:bg-slate-300',
        'outline' => 'bg-transparent hover:bg-brand-50 text-brand-600 border border-brand-300 hover:border-brand-500 active:bg-brand-100',
        'ghost' => 'bg-transparent hover:bg-slate-100 text-slate-600 hover:text-slate-900 border border-transparent active:bg-slate-200',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white border border-rose-500/20 active:bg-rose-800',
        'subtle' => 'bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200/80 active:bg-brand-200',
        'white' => 'bg-white hover:bg-slate-50 text-slate-900 border border-slate-200 active:bg-slate-100',
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

// Shorthand alias
function ui_btn(string $label, array $options = []): string {
    return ui_button($label, $options);
}

/**
 * --------------------------------------------------------------------------
 * 2. Form Input Primitives (ui_input, ui_textarea, ui_select, ui_toggle)
 * --------------------------------------------------------------------------
 */
function ui_input(string $name, array $options = []): string {
    $type        = $options['type'] ?? 'text';
    $label       = $options['label'] ?? null;
    $value       = $options['value'] ?? '';
    $placeholder = $options['placeholder'] ?? '';
    $required    = !empty($options['required']);
    $disabled    = !empty($options['disabled']);
    $icon        = $options['icon'] ?? null;
    $helper      = $options['helper'] ?? null;
    $error       = $options['error'] ?? null;
    $extraCls    = $options['class'] ?? '';
    $attrs       = $options['attrs'] ?? '';
    $id          = $options['id'] ?? 'input_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);

    $borderCls = !empty($error) ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200/90 focus:border-brand-500 focus:ring-brand-500/20';
    $paddingCls = $icon ? 'pl-10 pr-3.5 py-2.5' : 'px-3.5 py-2.5';
    
    $reqStar = $required ? '<span class="text-rose-500 ml-0.5">*</span>' : '';
    $labelHtml = $label ? "<label for=\"$id\" class=\"block text-xs font-bold text-slate-700 mb-1.5 tracking-tight\">" . sanitize($label) . "$reqStar</label>" : '';
    
    $iconHtml = $icon ? "<div class=\"absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none flex items-center\">" . ui_icon($icon, 'text-base') . "</div>" : '';
    
    $helperHtml = $helper && !$error ? "<p class=\"mt-1.5 text-[11px] text-slate-500\">" . sanitize($helper) . "</p>" : '';
    $errorHtml = $error ? "<p class=\"mt-1.5 text-[11px] text-rose-600 font-semibold flex items-center gap-1.5\">" . ui_icon('warning-circle', 'text-xs') . sanitize($error) . "</p>" : '';

    $reqAttr = $required ? 'required' : '';
    $disAttr = $disabled ? 'disabled' : '';

    return "
    <div class=\"w-full\">
        $labelHtml
        <div class=\"relative\">
            $iconHtml
            <input 
                type=\"$type\" 
                id=\"$id\" 
                name=\"$name\" 
                value=\"" . sanitize($value) . "\" 
                placeholder=\"" . sanitize($placeholder) . "\" 
                class=\"w-full text-xs sm:text-sm rounded-input bg-white text-slate-900 border $borderCls $paddingCls placeholder:text-slate-400 focus:outline-none focus:ring-2 transition duration-150 $extraCls\" 
                $reqAttr 
                $disAttr 
                $attrs
            />
        </div>
        $helperHtml
        $errorHtml
    </div>";
}

function ui_textarea(string $name, array $options = []): string {
    $label       = $options['label'] ?? null;
    $value       = $options['value'] ?? '';
    $placeholder = $options['placeholder'] ?? '';
    $rows        = $options['rows'] ?? 3;
    $required    = !empty($options['required']);
    $disabled    = !empty($options['disabled']);
    $helper      = $options['helper'] ?? null;
    $error       = $options['error'] ?? null;
    $extraCls    = $options['class'] ?? '';
    $attrs       = $options['attrs'] ?? '';
    $id          = $options['id'] ?? 'textarea_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);

    $borderCls = !empty($error) ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200/90 focus:border-brand-500 focus:ring-brand-500/20';
    $reqStar = $required ? '<span class="text-rose-500 ml-0.5">*</span>' : '';
    $labelHtml = $label ? "<label for=\"$id\" class=\"block text-xs font-bold text-slate-700 mb-1.5 tracking-tight\">" . sanitize($label) . "$reqStar</label>" : '';
    $helperHtml = $helper && !$error ? "<p class=\"mt-1.5 text-[11px] text-slate-500\">" . sanitize($helper) . "</p>" : '';
    $errorHtml = $error ? "<p class=\"mt-1.5 text-[11px] text-rose-600 font-semibold flex items-center gap-1.5\">" . ui_icon('warning-circle', 'text-xs') . sanitize($error) . "</p>" : '';

    $reqAttr = $required ? 'required' : '';
    $disAttr = $disabled ? 'disabled' : '';

    return "
    <div class=\"w-full\">
        $labelHtml
        <textarea 
            id=\"$id\" 
            name=\"$name\" 
            rows=\"$rows\" 
            placeholder=\"" . sanitize($placeholder) . "\" 
            class=\"w-full text-xs sm:text-sm rounded-input bg-white text-slate-900 border $borderCls px-3.5 py-2.5 placeholder:text-slate-400 focus:outline-none focus:ring-2 transition duration-150 $extraCls\" 
            $reqAttr 
            $disAttr 
            $attrs>" . sanitize($value) . "</textarea>
        $helperHtml
        $errorHtml
    </div>";
}

function ui_select(string $name, array $items = [], array $options = []): string {
    $label       = $options['label'] ?? null;
    $selected    = $options['selected'] ?? '';
    $required    = !empty($options['required']);
    $disabled    = !empty($options['disabled']);
    $helper      = $options['helper'] ?? null;
    $error       = $options['error'] ?? null;
    $extraCls    = $options['class'] ?? '';
    $attrs       = $options['attrs'] ?? '';
    $id          = $options['id'] ?? 'select_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);

    $borderCls = !empty($error) ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200/90 focus:border-brand-500 focus:ring-brand-500/20';
    $reqStar = $required ? '<span class="text-rose-500 ml-0.5">*</span>' : '';
    $labelHtml = $label ? "<label for=\"$id\" class=\"block text-xs font-bold text-slate-700 mb-1.5 tracking-tight\">" . sanitize($label) . "$reqStar</label>" : '';
    
    $optionsHtml = '';
    foreach ($items as $val => $text) {
        $isSelected = ((string)$val === (string)$selected) ? 'selected' : '';
        $optionsHtml .= "<option value=\"" . sanitize((string)$val) . "\" $isSelected>" . sanitize($text) . "</option>\n";
    }

    $helperHtml = $helper && !$error ? "<p class=\"mt-1.5 text-[11px] text-slate-500\">" . sanitize($helper) . "</p>" : '';
    $errorHtml = $error ? "<p class=\"mt-1.5 text-[11px] text-rose-600 font-semibold flex items-center gap-1.5\">" . ui_icon('warning-circle', 'text-xs') . sanitize($error) . "</p>" : '';

    $reqAttr = $required ? 'required' : '';
    $disAttr = $disabled ? 'disabled' : '';

    $caretIcon = ui_icon('caret-down', 'text-sm');

    return "
    <div class=\"w-full\">
        $labelHtml
        <div class=\"relative\">
            <select 
                id=\"$id\" 
                name=\"$name\" 
                class=\"w-full text-xs sm:text-sm rounded-input bg-white text-slate-900 border $borderCls pl-3.5 pr-10 py-2.5 appearance-none focus:outline-none focus:ring-2 transition duration-150 $extraCls\" 
                $reqAttr 
                $disAttr 
                $attrs>
                $optionsHtml
            </select>
            <div class=\"absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none flex items-center\">
                $caretIcon
            </div>
        </div>
        $helperHtml
        $errorHtml
    </div>";
}

function ui_toggle(string $name, string $label, bool $checked = false, array $options = []): string {
    $id       = $options['id'] ?? 'toggle_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    $helper   = $options['helper'] ?? null;
    $attrs    = $options['attrs'] ?? '';
    $chkAttr  = $checked ? 'checked' : '';
    $val      = $options['value'] ?? '1';

    $helperHtml = $helper ? "<p class=\"text-[11px] text-slate-500 mt-0.5\">" . sanitize($helper) . "</p>" : '';

    return "
    <label for=\"$id\" class=\"flex items-start gap-3 cursor-pointer select-none group\">
        <div class=\"relative inline-flex items-center mt-0.5\">
            <input type=\"checkbox\" id=\"$id\" name=\"$name\" value=\"$val\" $chkAttr class=\"sr-only peer\" $attrs>
            <div class=\"w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all duration-200 peer-checked:bg-brand-600\"></div>
        </div>
        <div class=\"text-xs\">
            <span class=\"font-bold text-slate-800 group-hover:text-brand-600 transition\">" . sanitize($label) . "</span>
            $helperHtml
        </div>
    </label>";
}

/**
 * --------------------------------------------------------------------------
 * 3. Surface & Card Primitive (ui_card)
 * --------------------------------------------------------------------------
 */
function ui_card(string $content, array $options = []): string {
    $title     = $options['title'] ?? null;
    $subtitle  = $options['subtitle'] ?? null;
    $icon      = $options['icon'] ?? null;
    $headerAction = $options['headerAction'] ?? null;
    $footer    = $options['footer'] ?? null;
    $glass     = !empty($options['glass']); // translucent Apple material
    $extraCls  = $options['class'] ?? '';
    $padding   = $options['padding'] ?? 'p-5 sm:p-6';

    $bgCls = $glass 
        ? 'bg-white/85 backdrop-blur-md border border-slate-200/70' 
        : 'bg-white border border-slate-200/80';

    $headerHtml = '';
    if ($title || $icon || $headerAction) {
        $iconHtml = $icon ? "<div class=\"w-8 h-8 rounded-btn bg-brand-50 text-brand-600 flex items-center justify-center\">" . ui_icon($icon, 'text-base') . "</div>" : '';
        $titleHtml = $title ? "<h3 class=\"text-sm sm:text-base font-bold text-slate-900 tracking-tight\">" . sanitize($title) . "</h3>" : '';
        $subHtml = $subtitle ? "<p class=\"text-xs text-slate-500 mt-0.5\">" . sanitize($subtitle) . "</p>" : '';
        $actionHtml = $headerAction ? "<div>$headerAction</div>" : '';

        $headerHtml = "
        <div class=\"px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-4\">
            <div class=\"flex items-center gap-3\">
                $iconHtml
                <div>
                    $titleHtml
                    $subHtml
                </div>
            </div>
            $actionHtml
        </div>";
    }

    $footerHtml = '';
    if ($footer) {
        $footerHtml = "<div class=\"px-5 sm:px-6 py-3.5 bg-slate-50/70 border-t border-slate-100 rounded-b-card\">$footer</div>";
    }

    return "
    <div class=\"rounded-card $bgCls overflow-hidden $extraCls\">
        $headerHtml
        <div class=\"$padding\">
            $content
        </div>
        $footerHtml
    </div>";
}

/**
 * --------------------------------------------------------------------------
 * 4. Badge & Status Chip Primitive (ui_badge)
 * --------------------------------------------------------------------------
 */
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

/**
 * --------------------------------------------------------------------------
 * 5. Alert & Notice Primitive (ui_alert)
 * --------------------------------------------------------------------------
 */
function ui_alert(string $message, string $variant = 'success', array $options = []): string {
    $title       = $options['title'] ?? null;
    $dismissible = !empty($options['dismissible']);
    $extraCls    = $options['class'] ?? '';

    $config = match($variant) {
        'success' => ['bg' => 'bg-emerald-50 border-emerald-200/90 text-emerald-900', 'icon' => 'check-circle', 'iconCol' => 'text-emerald-600'],
        'danger', 'error' => ['bg' => 'bg-rose-50 border-rose-200/90 text-rose-900', 'icon' => 'warning-circle', 'iconCol' => 'text-rose-600'],
        'warning' => ['bg' => 'bg-amber-50 border-amber-200/90 text-amber-900', 'icon' => 'warning', 'iconCol' => 'text-amber-600'],
        'info'    => ['bg' => 'bg-sky-50 border-sky-200/90 text-sky-900', 'icon' => 'info', 'iconCol' => 'text-sky-600'],
        default   => ['bg' => 'bg-brand-50 border-brand-200/90 text-brand-900', 'icon' => 'info', 'iconCol' => 'text-brand-600'],
    };

    $titleHtml = $title ? "<h4 class=\"font-bold text-xs sm:text-sm mb-0.5 tracking-tight\">" . sanitize($title) . "</h4>" : '';
    $dismissHtml = $dismissible 
        ? "<button type=\"button\" @click=\"showAlert = false\" class=\"text-slate-400 hover:text-slate-700 p-1 rounded-btn transition\">" . ui_icon('x', 'text-base') . "</button>" 
        : '';
    
    $xData = $dismissible ? 'x-data="{ showAlert: true }" x-show="showAlert" x-transition' : '';
    $mainIcon = ui_icon($config['icon'], 'text-lg ' . $config['iconCol'] . ' flex-shrink-0 mt-0.5');

    return "
    <div $xData class=\"p-4 rounded-card border {$config['bg']} flex items-start justify-between gap-3 text-xs sm:text-sm $extraCls\">
        <div class=\"flex items-start gap-3\">
            $mainIcon
            <div class=\"space-y-0.5\">
                $titleHtml
                <div class=\"text-xs leading-relaxed opacity-90\">$message</div>
            </div>
        </div>
        $dismissHtml
    </div>";
}

/**
 * --------------------------------------------------------------------------
 * 6. Avatar & Icon Box Primitives
 * --------------------------------------------------------------------------
 */
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

/**
 * --------------------------------------------------------------------------
 * 7. Stat Card Primitive (ui_stat_card)
 * --------------------------------------------------------------------------
 */
function ui_stat_card(string $title, string|int $value, array $options = []): string {
    $icon      = $options['icon'] ?? null;
    $subtitle  = $options['subtitle'] ?? null;
    $trend     = $options['trend'] ?? null; // e.g. '+12%'
    $trendType = $options['trendType'] ?? 'up'; // 'up' | 'down'
    $extraCls  = $options['class'] ?? '';

    $iconHtml = $icon ? ui_icon_box($icon, 'brand', ['size' => 'md']) : '';
    
    $trendHtml = '';
    if ($trend) {
        $tCol = $trendType === 'up' ? 'text-emerald-600 bg-emerald-50 border-emerald-200/80' : 'text-rose-600 bg-rose-50 border-rose-200/80';
        $tIcon = $trendType === 'up' ? 'trend-up' : 'trend-down';
        $tIconHtml = ui_icon($tIcon, 'text-xs');
        $trendHtml = "<span class=\"inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-badge border $tCol\">$tIconHtml$trend</span>";
    }

    $subHtml = $subtitle ? "<p class=\"text-xs text-slate-500 mt-1\">" . sanitize($subtitle) . "</p>" : '';

    return "
    <div class=\"rounded-card bg-white border border-slate-200/80 p-5 sm:p-6 transition hover:border-brand-300 $extraCls\">
        <div class=\"flex items-center justify-between gap-3\">
            <span class=\"text-xs font-bold text-slate-500 uppercase tracking-wider\">" . sanitize($title) . "</span>
            $iconHtml
        </div>
        <div class=\"mt-3 flex items-baseline gap-3\">
            <span class=\"text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight\">$value</span>
            $trendHtml
        </div>
        $subHtml
    </div>";
}

/**
 * --------------------------------------------------------------------------
 * 8. Empty State Primitive (ui_empty_state)
 * --------------------------------------------------------------------------
 */
function ui_empty_state(string $title, string $description = '', array $options = []): string {
    $icon       = $options['icon'] ?? 'package';
    $buttonText = $options['buttonText'] ?? null;
    $buttonHref = $options['buttonHref'] ?? null;
    $buttonIcon = $options['buttonIcon'] ?? null;
    $extraCls   = $options['class'] ?? '';
    $actionHtml = $options['actionHtml'] ?? '';

    $btnHtml = '';
    if ($buttonText && $buttonHref) {
        $btnHtml = '<div class="mt-6">' . ui_button($buttonText, [
            'variant' => 'primary',
            'size'    => 'sm',
            'href'    => $buttonHref,
            'icon'    => $buttonIcon,
        ]) . '</div>';
    } elseif ($actionHtml) {
        $btnHtml = '<div class="mt-6">' . $actionHtml . '</div>';
    }

    $iconHtml = ui_icon($icon, 'text-3xl');

    return "
    <div class=\"bg-white rounded-card border border-slate-200/80 p-12 sm:p-16 text-center max-w-xl mx-auto my-6 $extraCls\">
        <div class=\"w-16 h-16 rounded-card bg-slate-100 border border-slate-200/80 text-slate-400 flex items-center justify-center mx-auto mb-4\">
            $iconHtml
        </div>
        <h3 class=\"text-base sm:text-lg font-bold text-slate-900 tracking-tight\">" . sanitize($title) . "</h3>
        <p class=\"text-xs text-slate-500 max-w-md mx-auto mt-1 leading-relaxed\">" . sanitize($description) . "</p>
        $btnHtml
    </div>";
}

/**
 * --------------------------------------------------------------------------
 * 9. Breadcrumb Navigation Primitive (ui_breadcrumb)
 * --------------------------------------------------------------------------
 * @param array $items Array of ['label' => '...', 'href' => '...'] (href is optional for active item)
 */
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

/**
 * --------------------------------------------------------------------------
 * 10. Product Card Primitive (ui_product_card)
 * --------------------------------------------------------------------------
 */
function ui_product_card(array $product, array $options = []): string {
    $hasPromo = !empty($product['promo_price']) && $product['promo_price'] < $product['price'];
    $currentPrice = $hasPromo ? (float)$product['promo_price'] : (float)$product['price'];
    $discountPct = $hasPromo ? round((($product['price'] - $product['promo_price']) / $product['price']) * 100) : 0;
    $isOutOfStock = ((int)($product['stock'] ?? 0)) <= 0;
    $imgUrl = upload_url($product['image'] ?? '');
    $prodUrl = base_url('product.php?id=' . $product['id']);
    $extraCls = $options['class'] ?? '';
    $categoryName = sanitize($product['category_name'] ?? 'Umum');
    $prodName = sanitize($product['name'] ?? 'Produk');
    $escapedName = addslashes($prodName);

    $promoBadge = $hasPromo 
        ? '<span class="px-2.5 py-1 rounded-badge bg-rose-600 text-white text-[11px] font-extrabold border border-rose-500/20">-' . $discountPct . '%</span>' 
        : '';
    
    $featuredBadge = !empty($product['is_featured']) 
        ? '<span class="px-2.5 py-1 rounded-badge bg-amber-500 text-white text-[10px] font-bold border border-amber-400/20 flex items-center gap-1">⭐ Pilihan</span>' 
        : '';

    $stockOverlay = $isOutOfStock 
        ? '<div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center"><span class="px-3 py-1.5 rounded-badge bg-slate-900 border border-slate-700 text-white text-xs font-bold">Stok Habis</span></div>' 
        : '';

    $promoStrike = $hasPromo 
        ? '<span class="text-xs font-medium text-slate-400 line-through">' . format_rupiah($product['price']) . '</span>' 
        : '';

    $cartBtnDisabled = $isOutOfStock ? 'disabled' : '';
    $cartBtnClass = $isOutOfStock 
        ? 'bg-slate-200 text-slate-400 cursor-not-allowed border border-slate-300' 
        : 'bg-brand-600 hover:bg-brand-700 text-white border border-brand-500/20';

    $cartIcon = ui_icon('shopping-cart', 'text-sm');

    return "
    <div class=\"group bg-white rounded-card border border-slate-200/80 hover:border-brand-400 transition-colors duration-150 flex flex-col overflow-hidden $extraCls\">
        <div class=\"relative aspect-square overflow-hidden bg-slate-100\">
            <a href=\"$prodUrl\" class=\"block w-full h-full\">
                <img src=\"$imgUrl\" alt=\"$prodName\" loading=\"lazy\" class=\"w-full h-full object-cover group-hover:scale-105 transition-transform duration-300\">
            </a>
            <div class=\"absolute top-3 left-3 flex flex-col gap-1.5\">
                $promoBadge
                $featuredBadge
            </div>
            $stockOverlay
        </div>

        <div class=\"p-5 flex-1 flex flex-col justify-between\">
            <div>
                <div class=\"flex items-center justify-between text-[11px] text-slate-400 mb-1\">
                    <span>$categoryName</span>
                    <span class=\"font-medium " . ($isOutOfStock ? 'text-rose-500' : 'text-slate-500') . "\">
                        Stok: " . (int)$product['stock'] . "
                    </span>
                </div>
                <h3 class=\"font-bold text-sm text-slate-900 line-clamp-2 group-hover:text-brand-600 transition leading-snug tracking-tight\">
                    <a href=\"$prodUrl\">$prodName</a>
                </h3>
            </div>

            <div class=\"mt-4 pt-3 border-t border-slate-100\">
                <div class=\"mb-3\">
                    <div class=\"flex items-baseline gap-2\">
                        <span class=\"text-base font-extrabold text-brand-600 tracking-tight\">
                            " . format_rupiah($currentPrice) . "
                        </span>
                        $promoStrike
                    </div>
                </div>

                <div class=\"grid grid-cols-2 gap-2\">
                    <a href=\"$prodUrl\" class=\"py-2.5 px-3 rounded-btn bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/80 text-xs font-bold text-center transition apple-tap\">
                        Detail
                    </a>

                    <button 
                        type=\"button\" 
                        $cartBtnDisabled 
                        @click=\"\$store.cart.addItem({
                            id: {$product['id']},
                            name: '$escapedName',
                            price: $currentPrice,
                            image: '$imgUrl',
                            stock: " . (int)$product['stock'] . "
                        }, 1)\" 
                        class=\"py-2.5 px-3 rounded-btn $cartBtnClass text-xs font-bold text-center transition apple-tap flex items-center justify-center gap-1.5\">
                        $cartIcon
                        <span>+ Keranjang</span>
                    </button>
                </div>
            </div>
        </div>
    </div>";
}
