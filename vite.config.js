import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/manager/style.scss",
                "resources/css/manager/_base.scss",
                "resources/css/manager/_static_styles.scss",
                "resources/css/manager/_variables.scss",
                "resources/css/manager/attributes-index.scss",
                "resources/css/manager/categories-index.scss",
                "resources/css/manager/categories-create.scss",
                "resources/css/manager/colors-index.scss",
                "resources/css/manager/gallery-index.scss",
                "resources/css/manager/home-slider.scss",
                "resources/css/manager/gallery-index.scss",
                "resources/css/manager/products-create.scss",
                "resources/css/customer/style.scss",
                "resources/css/customer/dual-range.scss",
                "resources/css/customer/errors.scss",
                "resources/css/auth/login.scss",
                "resources/css/auth/style.scss",
                "resources/css/app.css",
                "resources/css/print.css",
                "resources/js/app.js",
                "resources/js/customer/slider.js",
                "resources/js/main.ts",
                "resources/js/manager/menu.ts",
                "resources/js/customer/dual-range.js",
                "resources/js/customer/filter-panel.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: [
            {
                find: "@",
                replacement: "/resources",
            },
        ],
    },
    // server: {
    //     host: "0.0.0.0",
    //     port: 5173,
    //     strictPort: true,
    //     hmr: {
    //         host: "192.168.200.174",
    //         protocol: "ws",
    //     },
    // },
});
