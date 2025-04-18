import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/pwa.css',
                'resources/js/app.js',
                'resources/js/pwa.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        // Исправляем CORS-проблемы в режиме разработки
        cors: {
            origin: '*'
        },
        hmr: {
            host: 'localhost'
        },
    },
    resolve: {
        alias: {
            // Добавляем алиасы для более удобного импорта
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~resources': path.resolve(__dirname, 'resources/'),
            '$': path.resolve(__dirname, 'node_modules/jquery'),
            'jquery': path.resolve(__dirname, 'node_modules/jquery'),
        }
    },
    // Правильная обработка статических ассетов
    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
        // Распаковка зависимостей для правильного выстраивания порядка загрузки
        rollupOptions: {
            output: {
                // Обеспечиваем, что jQuery загружается перед зависимыми скриптами
                manualChunks: {
                    vendor: ['jquery'],
                    bootstrap: ['bootstrap'],
                }
            }
        }
    },
    // Обеспечиваем совместимость с Laravel
    publicDir: 'fake_public_dir',
});
