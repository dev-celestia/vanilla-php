<?php
/**
 * Icon Component Primitive (ui_icon)
 *
 * Renders Phosphor Icons (https://phosphoricons.com/) with weight & class support
 * and automatic legacy/Lucide icon name normalization.
 */

if (!function_exists('ui_icon')) {
    function ui_icon(string $name, string $classes = '', string $weight = 'regular'): string {
        $prefix = match($weight) {
            'bold'    => 'ph-bold',
            'fill'    => 'ph-fill',
            'light'   => 'ph-light',
            'thin'    => 'ph-thin',
            'duotone' => 'ph-duotone',
            default   => 'ph',
        };

        // Normalize Lucide or legacy icon names to Phosphor icons
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
}
