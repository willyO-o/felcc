import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1', // Usa IPv4 en lugar de IPv6
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: 'localhost',
        },
    },
});
