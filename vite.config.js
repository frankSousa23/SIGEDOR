import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            publicDirectory: 'public',
            buildDirectory: 'build'
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js'
        },
    },
    build: {
        manifest: 'manifest.json',
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            external: ['axios'],
        }
    },
    base: '/build/',
});
