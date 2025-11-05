import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { dirname } from "path";
import { fileURLToPath } from "url";

const host = "nazmart.test";

export default defineConfig({
    root: dirname(fileURLToPath(import.meta.url)),
    // Ensure dynamic chunks load from /build/assets/... in production
    base: '/build/',
    build: {
        outDir: 'public/build',
        manifest: true,
        emptyOutDir: true,
        rollupOptions: {
            output: {
                // Ensure all asset paths use the base path
                assetFileNames: 'assets/[name]-[hash][extname]',
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
            },
        },
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
            publicDirectory: 'public',
            buildDirectory: 'build',
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
