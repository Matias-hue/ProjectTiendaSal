import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/sass/app.scss',
                'resources/js/app.js',      
                'resources/js/bootstrap.js',
                'resources/js/inventario.js',
                'resources/js/pedidos.js',
                'resources/js/registro.js',
                'resources/js/resumen.js',
                'resources/js/ubicacion.js',
                'resources/js/usuarios.js',        
            ],
            refresh: true,
        }),
    ],
});
