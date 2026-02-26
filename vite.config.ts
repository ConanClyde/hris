import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig(({ command }) => ({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        command === 'serve'
            ? wayfinder({
                  formVariants: true,
              })
            : null,
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ].filter((p) => p !== null),
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    fullcalendar: [
                        '@fullcalendar/core',
                        '@fullcalendar/daygrid',
                        '@fullcalendar/timegrid',
                        '@fullcalendar/list',
                        '@fullcalendar/interaction',
                        '@fullcalendar/multimonth',
                    ],
                    realtime: ['@laravel/echo-vue', 'laravel-echo', 'pusher-js'],
                    icons: ['lucide-vue-next'],
                },
            },
        },
    },
}));
