<?php
/**
 * Textarea Component Primitive (ui_textarea)
 *
 * Multiline text input with character support, helper notes, and validation errors.
 */

require_once __DIR__ . '/icon.php';

if (!function_exists('ui_textarea')) {
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
}
