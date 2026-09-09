import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from "path";
import { cpSync, mkdirSync, existsSync, rmSync } from "fs";

/** TinyMCE plugins used by admin content editor (from app.blade.php toolbar) */
const TINYMCE_PLUGINS = [
    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
    'insertdatetime', 'media', 'table', 'help', 'wordcount',
];

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/web-vitals.js',
            ],
            refresh: true,
        }),

        // Plugin to copy TinyMCE (trimmed: core, skins, themes, icons, models, required plugins only)
        {
            name: "copy-assets",
            closeBundle: {
                order: "post",
                async handler() {
                    try {
                        const tmceSource = resolve("node_modules/tinymce");
                        const tmceDest = resolve("public/plugins/tinymce");
                        const copyOpts = { recursive: true, force: true, preserveTimestamps: true };

                        mkdirSync(tmceDest, { recursive: true });

                        cpSync(resolve(tmceSource, "tinymce.min.js"), resolve(tmceDest, "tinymce.min.js"), copyOpts);
                        cpSync(resolve(tmceSource, "skins"), resolve(tmceDest, "skins"), copyOpts);
                        cpSync(resolve(tmceSource, "themes"), resolve(tmceDest, "themes"), copyOpts);
                        cpSync(resolve(tmceSource, "icons"), resolve(tmceDest, "icons"), copyOpts);
                        if (existsSync(resolve(tmceSource, "models"))) {
                            cpSync(resolve(tmceSource, "models"), resolve(tmceDest, "models"), copyOpts);
                        }

                        const pluginsDest = resolve(tmceDest, "plugins");
                        if (existsSync(pluginsDest)) rmSync(pluginsDest, { recursive: true });
                        mkdirSync(pluginsDest, { recursive: true });
                        for (const p of TINYMCE_PLUGINS) {
                            const src = resolve(tmceSource, "plugins", p);
                            if (existsSync(src)) {
                                cpSync(src, resolve(pluginsDest, p), copyOpts);
                            }
                        }

                        console.log("✅ TinyMCE assets (trimmed) copied to public/plugins/tinymce");
                    } catch (error) {
                        console.error("❌ Error copying assets:", error);
                    }
                },
            },
        },
    ],
});
