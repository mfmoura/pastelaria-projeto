import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
            buildDirectory: 'build',
        }),
        vue(),
        VitePWA({
            registerType: 'autoUpdate',
            includeAssets: ['favicon.ico', 'robots.txt', 'icons/*'],
            manifest: {
                name: 'Pastelaria App',
                short_name: 'Pastelaria',
                start_url: '/',
                display: 'standalone',
                background_color: '#ffffff',
                theme_color: '#fbbf24',
                icons: [
                    { src: '/icons/icon-192x192.png', sizes: '192x192', type: 'image/png' },
                    { src: '/icons/icon-512x512.png', sizes: '512x512', type: 'image/png' }
                ]
            }
        }),
    ],
    resolve: {
        alias: { '@': path.resolve(__dirname, 'resources/js') }
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: { host: 'localhost' },
        watch: { usePolling: true }
    },
    build: {
        outDir: 'public/build',
        manifest: true,
        emptyOutDir: true
    },
    test: {
        globals: true,
        environment: 'jsdom',
        setupFiles: ['resources/js/tests/setup.js'],
        coverage: {
            reporter: ['text', 'lcov'],
            all: true,
            include: ['resources/js/**/*.vue', 'resources/js/**/*.js'],
            exclude: ['node_modules/']
        }
    }
});
