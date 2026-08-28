<?php
/**
 * Toggle / Switch Component Primitive (ui_toggle)
 *
 * Tactile iOS-style toggle switch for boolean settings, filters, and forms.
 * Features:
 * - Fluid spring transition on thumb
 * - Instant active pointer-down feedback
 * - Label and supporting description text
 */

if (!function_exists('ui_toggle')) {
    function ui_toggle(string $name, string $label, bool $checked = false, array $options = []): string {
        $id         = $options['id'] ?? 'toggle_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
        $helper     = $options['helper'] ?? null;
        $attrs      = $options['attrs'] ?? '';
        $disabled   = !empty($options['disabled']);
        $chkAttr    = $checked ? 'checked' : '';
        $disAttr    = $disabled ? 'disabled' : '';
        $val        = $options['value'] ?? '1';
        $size       = $options['size'] ?? 'md'; // sm, md

        $helperHtml = $helper ? "<p class=\"text-[11px] text-slate-500 mt-0.5 leading-normal\">" . sanitize($helper) . "</p>" : '';

        $switchDimensions = ($size === 'sm') 
            ? 'w-9 h-5 after:h-4 after:w-4 after:top-[2px] after:left-[2px]' 
            : 'w-11 h-6 after:h-5 after:w-5 after:top-[2px] after:left-[2px]';

        $disCls = $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer';

        return "
        <label for=\"$id\" class=\"inline-flex items-start gap-3 select-none group $disCls\">
            <div class=\"relative inline-flex items-center mt-0.5 flex-shrink-0\">
                <input type=\"checkbox\" id=\"$id\" name=\"$name\" value=\"$val\" $chkAttr $disAttr class=\"sr-only peer\" $attrs>
                <div class=\"$switchDimensions bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:bg-white after:border-slate-300 after:border after:rounded-full after:transition-all after:duration-200 peer-checked:bg-brand-600 apple-tap\"></div>
            </div>
            <div class=\"text-xs sm:text-sm\">
                <span class=\"font-semibold text-slate-800 group-hover:text-brand-600 transition-colors tracking-tight\">" . sanitize($label) . "</span>
                $helperHtml
            </div>
        </label>";
    }
}
