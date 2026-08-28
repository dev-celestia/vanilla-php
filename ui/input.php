<?php
/**
 * Input Component Primitive (ui_input)
 *
 * Form text/email/password/number input with label, icons, and error states.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_input')) {
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
}
