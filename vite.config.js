import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import inject from '@rollup/plugin-inject';

export default defineConfig({
    plugins: [
    inject({
        $: 'jquery',
        jQuery: 'jquery',
        }),
      laravel({
            input: [
                'resources/css/public.css',
                'resources/js/public.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
            ],
            refresh: true,
        })
    ],
    build: {
        sourcemap: true, // Générer les sourcemaps pour le débogage
    },
    server: {
        port: 3000, // Assurez-vous que le port correspond à celui que vous utiliserez dans le débogueur
    },
});
