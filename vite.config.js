import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/about.css',
                'resources/css/dashboard.css',
                'resources/css/metrics.css',
                'resources/css/result.css',
                'resources/css/urls.css',
                'resources/css/verify-email.css',
                'resources/css/custom-url.css',
                'resources/js/app.js',
                'resources/js/dropdown-header.js',
                'resources/js/delete-modal.js',
                'resources/js/qr-code-generator.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
