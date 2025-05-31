import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// On utilise ici le package "glob" pour récupérer dynamiquement la liste des fichiers
import { globSync } from 'glob';

function findAllIndexJs() {
    // Cette fonction renvoie un tableau de chemins (chaînes) vers chaque fichier index.js trouvé
    // dans l’arborescence "resources/js/"
    return globSync('resources/js/**/index.js');
}

export default defineConfig({

    plugins: [
      laravel({
            input: [
                'resources/css/public.css',
                'resources/js/public.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
                ...findAllIndexJs(),
            ],
            refresh: true,
        }),
    ],
    resolve : {
        alias: {
            '$':'jQuery',
        }
    },
    optimizeDeps: {
        include: ['izimodal/js/iziModal.min.js'] // Pré-charger iziModal
    },
    build: {
        sourcemap: true, // Générer les sourcemaps pour le débogage
    },
    server: {
        port: 3000, // Assurez-vous que le port correspond à celui que vous utiliserez dans le débogueur
    },
});
