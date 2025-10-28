import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import flowbite from "flowbite/plugin";

export default defineConfig({
    plugins: [
        flowbite(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
