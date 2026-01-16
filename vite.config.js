import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/admin/users-filters.js",
                "resources/js/admin/news-editor.js",
                "resources/js/admin/widget-editor.js",
            ],
            refresh: true,
        }),
    ],
});
