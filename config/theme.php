<?php
/**
 * Design System Tokens & Theme Configuration
 * 
 * Defines centralized design tokens (colors, border-radius, typography, transitions)
 * following Apple Design principles and Ponytail lightweight architecture.
 */

// ponytail: single source of truth for color palettes (50-950)
function get_theme_color_palettes(): array {
    return [
        'emerald' => [
            'name'     => 'Emerald Green (Default)',
            'bg_class' => 'bg-emerald-600',
            '50'  => '#ecfdf5',
            '100' => '#d1fae5',
            '200' => '#a7f3d0',
            '300' => '#6ee7b7',
            '400' => '#34d399',
            '500' => '#10b981',
            '600' => '#059669',
            '700' => '#047857',
            '800' => '#065f46',
            '900' => '#064e3b',
            '950' => '#022c22',
        ],
        'blue' => [
            'name'     => 'Apple Classic Blue',
            'bg_class' => 'bg-blue-600',
            '50'  => '#eff6ff',
            '100' => '#dbeafe',
            '200' => '#bfdbfe',
            '300' => '#93c5fd',
            '400' => '#60a5fa',
            '500' => '#3b82f6',
            '600' => '#2563eb',
            '700' => '#1d4ed8',
            '800' => '#1e40af',
            '900' => '#1e3a8a',
            '950' => '#172554',
        ],
        'indigo' => [
            'name'     => 'Modern Indigo',
            'bg_class' => 'bg-indigo-600',
            '50'  => '#eef2ff',
            '100' => '#e0e7ff',
            '200' => '#c7d2fe',
            '300' => '#a5b4fc',
            '400' => '#818cf8',
            '500' => '#6366f1',
            '600' => '#4f46e5',
            '700' => '#4338ca',
            '800' => '#3730a3',
            '900' => '#312e81',
            '950' => '#1e1b4b',
        ],
        'violet' => [
            'name'     => 'Electric Violet',
            'bg_class' => 'bg-violet-600',
            '50'  => '#f5f3ff',
            '100' => '#ede9fe',
            '200' => '#ddd6fe',
            '300' => '#c4b5fd',
            '400' => '#a78bfa',
            '500' => '#8b5cf6',
            '600' => '#7c3aed',
            '700' => '#6d28d9',
            '800' => '#5b21b6',
            '900' => '#4c1d95',
            '950' => '#2e1065',
        ],
        'rose' => [
            'name'     => 'Vibrant Rose',
            'bg_class' => 'bg-rose-600',
            '50'  => '#fff1f2',
            '100' => '#ffe4e6',
            '200' => '#fecdd3',
            '300' => '#fda4af',
            '400' => '#fb7185',
            '500' => '#f43f5e',
            '600' => '#e11d48',
            '700' => '#be123c',
            '800' => '#9f1239',
            '900' => '#881337',
            '950' => '#4c0519',
        ],
        'amber' => [
            'name'     => 'Warm Amber',
            'bg_class' => 'bg-amber-600',
            '50'  => '#fffbeb',
            '100' => '#fef3c7',
            '200' => '#fde68a',
            '300' => '#fcd34d',
            '400' => '#fbbf24',
            '500' => '#f59e0b',
            '600' => '#d97706',
            '700' => '#b45309',
            '800' => '#92400e',
            '900' => '#78350f',
            '950' => '#451a03',
        ],
        'teal' => [
            'name'     => 'Nordic Teal',
            'bg_class' => 'bg-teal-600',
            '50'  => '#f0fdfa',
            '100' => '#ccfbf1',
            '200' => '#99f6e4',
            '300' => '#5eead4',
            '400' => '#2dd4bf',
            '500' => '#14b8a6',
            '600' => '#0d9488',
            '700' => '#0f766e',
            '800' => '#115e59',
            '900' => '#134e4a',
            '950' => '#042f2e',
        ],
        'slate' => [
            'name'     => 'Minimal Slate / Dark',
            'bg_class' => 'bg-slate-600',
            '50'  => '#f8fafc',
            '100' => '#f1f5f9',
            '200' => '#e2e8f0',
            '300' => '#cbd5e1',
            '400' => '#94a3b8',
            '500' => '#64748b',
            '600' => '#475569',
            '700' => '#334155',
            '800' => '#1e293b',
            '900' => '#0f172a',
            '950' => '#020617',
        ]
    ];
}

// ponytail: single source of truth for global corner radius scale
function get_theme_radius_presets(): array {
    return [
        'sharp' => [
            'name'   => 'Sharp (0px)',
            'description' => 'Clean square edges for dense technical interfaces',
            'btn'    => '0px',
            'card'   => '0px',
            'input'  => '0px',
            'badge'  => '0px',
            'modal'  => '0px',
            'avatar' => '0px',
            'tailwind' => 'rounded-none'
        ],
        'subtle' => [
            'name'   => 'Subtle (6px)',
            'description' => 'Crisp slight rounding for modern corporate feel',
            'btn'    => '6px',
            'card'   => '8px',
            'input'  => '6px',
            'badge'  => '4px',
            'modal'  => '10px',
            'avatar' => '6px',
            'tailwind' => 'rounded-md'
        ],
        'standard' => [
            'name'   => 'Standard Apple (12px)',
            'description' => 'Natural curvature matching Apple Human Interface Guidelines',
            'btn'    => '12px',
            'card'   => '16px',
            'input'  => '12px',
            'badge'  => '8px',
            'modal'  => '20px',
            'avatar' => '12px',
            'tailwind' => 'rounded-xl'
        ],
        'soft' => [
            'name'   => 'Soft (16px)',
            'description' => 'Friendly, approachable organic rounding',
            'btn'    => '16px',
            'card'   => '24px',
            'input'  => '16px',
            'badge'  => '10px',
            'modal'  => '28px',
            'avatar' => '16px',
            'tailwind' => 'rounded-2xl'
        ],
        'round' => [
            'name'   => 'Extra Round (24px)',
            'description' => 'Bold contemporary curved aesthetic',
            'btn'    => '24px',
            'card'   => '32px',
            'input'  => '24px',
            'badge'  => '14px',
            'modal'  => '36px',
            'avatar' => '24px',
            'tailwind' => 'rounded-3xl'
        ],
        'pill' => [
            'name'   => 'Pill Full (9999px)',
            'description' => 'Fully rounded capsular style',
            'btn'    => '9999px',
            'card'   => '24px',
            'input'  => '9999px',
            'badge'  => '9999px',
            'modal'  => '28px',
            'avatar' => '9999px',
            'tailwind' => 'rounded-full'
        ]
    ];
}

// Get active theme configuration based on store settings
function get_active_theme(): array {
    $settings = function_exists('get_settings') ? get_settings() : [];
    
    $colorKey = $settings['theme_primary_color'] ?? 'emerald';
    $radiusKey = $settings['theme_radius'] ?? 'standard';

    $palettes = get_theme_color_palettes();
    $radiusPresets = get_theme_radius_presets();

    $activePalette = $palettes[$colorKey] ?? $palettes['emerald'];
    $activeRadius = $radiusPresets[$radiusKey] ?? $radiusPresets['standard'];

    return [
        'color_key'     => $colorKey,
        'palette'       => $activePalette,
        'radius_key'    => $radiusKey,
        'radius'        => $activeRadius,
    ];
}

/**
 * Render Theme Styles (CSS Variables + Total Shadow Removal + Tailwind Config Injection)
 * Embeds cleanly in <head> for both Storefront and Admin panel.
 */
function render_theme_head(): void {
    $theme = get_active_theme();
    $p = $theme['palette'];
    $r = $theme['radius'];
    $colorKey = $theme['color_key'];
    ?>
    <!-- Design System Token Variables -->
    <style id="ds-tokens">
        :root {
            /* Primary Brand Color Tokens */
            --color-brand-50: <?= $p['50'] ?>;
            --color-brand-100: <?= $p['100'] ?>;
            --color-brand-200: <?= $p['200'] ?>;
            --color-brand-300: <?= $p['300'] ?>;
            --color-brand-400: <?= $p['400'] ?>;
            --color-brand-500: <?= $p['500'] ?>;
            --color-brand-600: <?= $p['600'] ?>;
            --color-brand-700: <?= $p['700'] ?>;
            --color-brand-800: <?= $p['800'] ?>;
            --color-brand-900: <?= $p['900'] ?>;
            --color-brand-950: <?= $p['950'] ?>;

            /* Global Radius Tokens */
            --radius-btn: <?= $r['btn'] ?>;
            --radius-card: <?= $r['card'] ?>;
            --radius-input: <?= $r['input'] ?>;
            --radius-badge: <?= $r['badge'] ?>;
            --radius-modal: <?= $r['modal'] ?>;
            --radius-avatar: <?= $r['avatar'] ?>;

            /* Apple Design Fluid Motion */
            --ease-apple: cubic-bezier(0.16, 1, 0.3, 1);
            --ease-bounce: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Apple Design: ABSOLUTE ZERO SHADOWS & GLOBAL RESET */
        *, *::before, *::after {
            box-shadow: none !important;
            text-shadow: none !important;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Custom subtle scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Token-based radius utility helper classes */
        .btn-theme-radius    { border-radius: var(--radius-btn) !important; }
        .card-theme-radius   { border-radius: var(--radius-card) !important; }
        .input-theme-radius  { border-radius: var(--radius-input) !important; }
        .badge-theme-radius  { border-radius: var(--radius-badge) !important; }
        .modal-theme-radius  { border-radius: var(--radius-modal) !important; }
        .avatar-theme-radius { border-radius: var(--radius-avatar) !important; }

        /* Apple Tactile Pointer Feedback */
        .apple-tap {
            transition: transform 120ms var(--ease-apple), opacity 120ms ease;
            user-select: none;
            -webkit-user-select: none;
        }
        .apple-tap:active {
            transform: scale(0.975);
            opacity: 0.92;
        }
        .apple-card-hover {
            transition: border-color 150ms ease, background-color 150ms ease, transform 150ms var(--ease-apple);
        }
        .apple-card-hover:hover {
            border-color: var(--color-brand-400);
        }

        /* Reduced Motion Accessibility */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
            .apple-tap:active {
                transform: none !important;
            }
        }
    </style>

    <!-- Tailwind Dynamic Token Configuration -->
    <script>
        tailwind = window.tailwind || {};
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Geist"', '-apple-system', 'BlinkMacSystemFont', 'system-ui', 'sans-serif'],
                    },
                    borderRadius: {
                        'btn': 'var(--radius-btn)',
                        'card': 'var(--radius-card)',
                        'input': 'var(--radius-input)',
                        'badge': 'var(--radius-badge)',
                        'modal': 'var(--radius-modal)',
                        'avatar': 'var(--radius-avatar)',
                    },
                    colors: {
                        brand: {
                            50: '<?= $p['50'] ?>',
                            100: '<?= $p['100'] ?>',
                            200: '<?= $p['200'] ?>',
                            300: '<?= $p['300'] ?>',
                            400: '<?= $p['400'] ?>',
                            500: '<?= $p['500'] ?>',
                            600: '<?= $p['600'] ?>',
                            700: '<?= $p['700'] ?>',
                            800: '<?= $p['800'] ?>',
                            900: '<?= $p['900'] ?>',
                            950: '<?= $p['950'] ?>',
                        }
                    }
                }
            }
        };
    </script>
    <?php
}
