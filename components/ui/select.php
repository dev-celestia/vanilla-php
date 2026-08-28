<?php
/**
 * Select Component Primitive (ui_select)
 *
 * Custom styled dropdown select element with chevron icon.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_select')) {
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
}
