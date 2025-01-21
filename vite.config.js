import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(), // Agregado soporte para Vue.js si se usa en el proyecto
    ],
    server: {
        watch: {
            usePolling: true, // Mejora la detección de cambios en entornos de desarrollo
        },
        host: '127.0.0.1',
        port: 5173,
    },
    resolve: {
        alias: {
            '@': '/resources/js',  // Simplifica importaciones en Vue/JS
        },
    },
});
