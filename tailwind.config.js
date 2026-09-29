/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    safelist: [
        'grid-cols-7',
        'bg-primary', 'text-on-primary',
        'bg-secondary', 'text-on-secondary',
        'bg-secondary-fixed', 'text-on-secondary-fixed',
        'opacity-30', 'cursor-not-allowed',
        'cursor-pointer', 'select-none',
        'hover:bg-secondary', 'hover:text-on-secondary',
        'transition-colors',
    ],
    theme: {
        extend: {
            colors: { "on-primary": "#ffffff", "primary": "#000000", "on-primary-fixed-variant": "#474746", "surface-container-highest": "#e5e2dd", "on-error": "#ffffff", "on-secondary-fixed": "#291802", "inverse-on-surface": "#f3f0eb", "secondary": "#735a3c", "surface-bright": "#fcf9f4", "on-tertiary-container": "#85847f", "on-tertiary": "#ffffff", "on-secondary-container": "#785e3f", "on-tertiary-fixed-variant": "#474743", "on-primary-fixed": "#1c1b1b", "on-secondary-fixed-variant": "#5a4226", "surface-container-high": "#ebe8e3", "inverse-primary": "#c8c6c5", "on-primary-container": "#858383", "error-container": "#ffdad6", "surface-container-lowest": "#ffffff", "tertiary-fixed": "#e5e2dc", "inverse-surface": "#31302d", "tertiary-container": "#1c1c18", "on-secondary": "#ffffff", "on-surface": "#1c1c19", "surface-tint": "#5f5e5e", "surface": "#fcf9f4", "primary-container": "#1c1b1b", "error": "#ba1a1a", "on-tertiary-fixed": "#1c1c18", "outline": "#747878", "tertiary-fixed-dim": "#c9c6c1", "primary-fixed": "#e5e2e1", "background": "#fcf9f4", "tertiary": "#000000", "secondary-fixed-dim": "#e3c19c", "secondary-fixed": "#ffddb8", "surface-container-low": "#f6f3ee", "primary-fixed-dim": "#c8c6c5", "surface-variant": "#e5e2dd", "surface-dim": "#dcdad5", "on-error-container": "#93000a", "surface-container": "#f0ede9", "outline-variant": "#c4c7c7", "on-surface-variant": "#444748", "secondary-container": "#fddab3", "on-background": "#1c1c19" },
            borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
            spacing: { "margin": "4rem", "gutter": "2rem", "space-md": "1.5rem", "space-xs": "0.375rem", "space-sm": "0.75rem", "margin-mobile": "1.5rem", "gutter-mobile": "1rem", "space-lg": "2.5rem", "space-xl": "4.5rem" },
            fontFamily: { "headline-lg": ["Playfair Display"], "display-lg": ["Playfair Display"], "body-sm": ["Manrope"], "label-md": ["Manrope"], "display-lg-mobile": ["Playfair Display"], "title-md": ["Manrope"], "body-md": ["Manrope"], "label-sm": ["Manrope"], "body-lg": ["Manrope"], "display-md": ["Playfair Display"], "headline-sm": ["Playfair Display"], "headline-md": ["Playfair Display"], "display-md-mobile": ["Playfair Display"] },
            fontSize: { "headline-lg": ["2.25rem", { "lineHeight": "2.75rem", "letterSpacing": "-0.01em", "fontWeight": "400" }], "display-lg": ["4.5rem", { "lineHeight": "5rem", "letterSpacing": "-0.02em", "fontWeight": "400" }], "body-sm": ["0.8125rem", { "lineHeight": "1.375rem", "letterSpacing": "0.015em", "fontWeight": "400" }], "label-md": ["0.75rem", { "lineHeight": "1rem", "letterSpacing": "0.18em", "fontWeight": "600" }], "display-lg-mobile": ["2.75rem", { "lineHeight": "3.25rem", "letterSpacing": "-0.01em", "fontWeight": "400" }], "title-md": ["1.125rem", { "lineHeight": "1.625rem", "letterSpacing": "0.01em", "fontWeight": "500" }], "body-md": ["0.9375rem", { "lineHeight": "1.625rem", "letterSpacing": "0.01em", "fontWeight": "400" }], "label-sm": ["0.6875rem", { "lineHeight": "0.875rem", "letterSpacing": "0.22em", "fontWeight": "600" }], "body-lg": ["1.125rem", { "lineHeight": "1.875rem", "letterSpacing": "0.01em", "fontWeight": "300" }], "display-md": ["3.25rem", { "lineHeight": "3.75rem", "letterSpacing": "-0.015em", "fontWeight": "400" }], "headline-sm": ["1.375rem", { "lineHeight": "1.875rem", "letterSpacing": "0", "fontWeight": "500" }], "headline-md": ["1.75rem", { "lineHeight": "2.25rem", "letterSpacing": "0", "fontWeight": "400" }], "display-md-mobile": ["2.25rem", { "lineHeight": "2.75rem", "letterSpacing": "-0.01em", "fontWeight": "400" }] }
        }
    },
    plugins: [],
}
