<?php
/**
 * Toggle / Switch Component Primitive (ui_toggle)
 *
 * Tactile iOS-style toggle switch for boolean settings and form options.
 */

if (!function_exists('ui_toggle')) {
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
}
