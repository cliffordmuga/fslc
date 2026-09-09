// ============================================================================
// FILE: tailwind.config.js – Sharp / Grok-style Design System (2026)
// ============================================================================

import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./app/helpers.php",
    ],

    safelist: [
        "line-clamp-1",
        "line-clamp-2",
        "bg-primary-100",
    ],

    darkMode: "class",

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                mono: ["JetBrains Mono", "Fira Code", ...defaultTheme.fontFamily.mono],
            },

            colors: {
                // Brand scale — CSS vars enable CMS / theme overrides; hex fallbacks for build
                brand: {
                    DEFAULT: "var(--color-brand, #0ea5e9)",
                    hover: "var(--color-brand-hover, #0284c7)",
                    muted: "var(--color-brand-muted, #e0f2fe)",
                    strong: "var(--color-brand-strong, #0369a1)",
                },
                primary: {
                    50:  "var(--color-primary-50, #f0f9ff)",
                    100: "var(--color-primary-100, #e0f2fe)",
                    200: "var(--color-primary-200, #bae6fd)",
                    300: "var(--color-primary-300, #7dd3fc)",
                    400: "var(--color-primary-400, #38bdf8)",
                    500: "var(--color-primary-500, var(--color-brand, #0ea5e9))",
                    600: "var(--color-primary-600, var(--color-brand-hover, #0284c7))",
                    700: "var(--color-primary-700, var(--color-brand-strong, #0369a1))",
                    800: "var(--color-primary-800, #075985)",
                    900: "var(--color-primary-900, #0c4a6e)",
                },
                neutral: {
                    50:  "var(--neutral-50, #fafafa)",
                    100: "var(--neutral-100, #f5f5f5)",
                    200: "var(--neutral-200, #e5e5e5)",
                    300: "#d4d4d4",
                    400: "#a3a3a3",
                    500: "#737373",
                    600: "#525252",
                    700: "#404040",
                    800: "#262626",
                    900: "var(--neutral-900, #171717)",
                },
                coral: {
                    500: "#f43f5e",
                    600: "#e11d48",
                },
            },

            // Radii from tokens — change --radius-* in tokens.css to modernize globally
            borderRadius: {
                none:    "var(--radius-none, 0)",
                sm:      "var(--radius-button, 0)",
                DEFAULT: "var(--radius-card, 0)",
                md:      "var(--radius-card, 0)",
                lg:      "var(--radius-card, 0)",
                xl:      "var(--radius-card, 0)",
                "2xl":   "var(--radius-card, 0)",
                "3xl":   "var(--radius-card, 0)",
                full:    "9999px",
            },

            // ── Flatter, more structural shadow scale ─────────────────────────
            boxShadow: {
                sm:       "0 1px 2px 0 rgb(0 0 0 / 0.08)",
                DEFAULT:  "0 1px 3px 0 rgb(0 0 0 / 0.10), 0 1px 2px -1px rgb(0 0 0 / 0.08)",
                md:       "0 2px 6px -1px rgb(0 0 0 / 0.10), 0 1px 4px -2px rgb(0 0 0 / 0.06)",
                lg:       "0 4px 12px -3px rgb(0 0 0 / 0.12), 0 2px 6px -4px rgb(0 0 0 / 0.08)",
                // keep named aliases used in views
                soft:     "0 1px 3px 0 rgb(0 0 0 / 0.08)",
                medium:   "0 2px 6px -1px rgb(0 0 0 / 0.10), 0 1px 4px -2px rgb(0 0 0 / 0.06)",
                elevated: "0 4px 12px -3px rgb(0 0 0 / 0.12), 0 2px 6px -4px rgb(0 0 0 / 0.08)",
            },

            animation: {
                "fade-in":              "fadeIn 0.5s ease-out forwards",
                float:                  "float 6s ease-in-out infinite",
                "float-slow":           "float 8s ease-in-out infinite",
                "float-fast":           "float 4s ease-in-out infinite",
                "pulse-slow":           "pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite",
                "bounce-slow":          "bounce 2s infinite",
                "slide-in-from-top-2":  "slideInFromTop 0.5s ease-out",
                "slide-in-from-bottom-4": "slideInFromBottom 0.6s ease-out",
            },

            keyframes: {
                fadeIn: {
                    "0%":   { opacity: "0", transform: "translateY(12px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                float: {
                    "0%, 100%": { transform: "translateY(0px)" },
                    "50%":      { transform: "translateY(-12px)" },
                },
                slideInFromTop: {
                    "0%":   { opacity: "0", transform: "translateY(-8px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                slideInFromBottom: {
                    "0%":   { opacity: "0", transform: "translateY(8px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
            },

            backdropBlur: {
                xs: "2px",
            },

            backgroundImage: {
                "grid-pattern":
                    "linear-gradient(rgba(255,255,255,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.03) 1px,transparent 1px)",
                "hero-grid":
                    "linear-gradient(rgba(255,255,255,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.03) 1px,transparent 1px)",
            },

            backgroundSize: {
                "hero-grid": "64px 64px",
            },

            spacing: {
                safe: "max(1rem, env(safe-area-inset-bottom))",
            },

            // Compact section spacing scale
            padding: {
                section:    "2.5rem",       // py-section  → 40px
                "section-lg": "4rem",       // lg:py-section-lg → 64px
            },
        },
    },

    plugins: [forms, typography],
};
