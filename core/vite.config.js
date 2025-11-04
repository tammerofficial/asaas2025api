import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { dirname } from "path";
import { fileURLToPath } from "url";

const host = "nazmart.test";

export default defineConfig({
    root: dirname(fileURLToPath(import.meta.url)),
    base: '/',
    build: {
        outDir: 'public/build',
        manifest: true,
        emptyOutDir: true,
    },
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/central-v1.css', 
                'resources/js/central/central-v1.js'
            ],
            refresh: true,
            detectTls: host,
        }),
    ],
    server: {
        host: host,
        hmr: {
            host: host,
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
