<?php
/**
 * Select Component Primitive (ui_select)
 *
 * Custom styled dropdown select with custom Apple caret, optgroups support, and focus rings.
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
        $placeholder = $options['placeholder'] ?? null;
        $size        = $options['size'] ?? 'md';
        $id          = $options['id'] ?? 'select_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);

        $borderCls = !empty($error) 
            ? 'border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20' 
            : 'border-slate-200/90 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20';
        
        $sizeCls = match($size) {
            'sm' => 'text-xs py-1.5 pl-3 pr-8',
            'lg' => 'text-sm sm:text-base py-3 sm:py-3.5 pl-4 pr-11',
            default => 'text-xs sm:text-sm py-2.5 pl-3.5 pr-10',
        };

        $reqStar = $required ? '<span class="text-rose-500 ml-0.5" aria-hidden="true">*</span>' : '';
        $labelHtml = $label ? "<label for=\"$id\" class=\"block text-xs font-semibold text-slate-700 mb-1.5 tracking-tight select-none\">" . sanitize($label) . "$reqStar</label>" : '';
        
        $optionsHtml = '';
        if ($placeholder) {
            $optionsHtml .= "<option value=\"\" " . ($selected === '' ? 'selected' : '') . " disabled>" . sanitize($placeholder) . "</option>\n";
        }

        foreach ($items as $val => $text) {
            if (is_array($text)) {
                // Optgroup support
                $optionsHtml .= "<optgroup label=\"" . sanitize((string)$val) . "\">\n";
                foreach ($text as $subVal => $subText) {
                    $isSelected = ((string)$subVal === (string)$selected) ? 'selected' : '';
                    $optionsHtml .= "  <option value=\"" . sanitize((string)$subVal) . "\" $isSelected>" . sanitize((string)$subText) . "</option>\n";
                }
                $optionsHtml .= "</optgroup>\n";
            } else {
                $isSelected = ((string)$val === (string)$selected) ? 'selected' : '';
                $optionsHtml .= "<option value=\"" . sanitize((string)$val) . "\" $isSelected>" . sanitize((string)$text) . "</option>\n";
            }
        }

        $helperHtml = $helper && !$error ? "<p class=\"mt-1.5 text-[11px] text-slate-500 leading-normal\">" . sanitize($helper) . "</p>" : '';
        $errorHtml = $error ? "<p class=\"mt-1.5 text-[11px] text-rose-600 font-semibold flex items-center gap-1.5\">" . ui_icon('warning-circle', 'text-xs flex-shrink-0') . sanitize($error) . "</p>" : '';

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
                    class=\"w-full rounded-input bg-white text-slate-900 border $borderCls $sizeCls appearance-none focus:outline-none transition-all duration-150 cursor-pointer $extraCls\" 
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

