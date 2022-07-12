import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import path from "path";

export default defineConfig({
    plugins: [
        react({
            //exclude: [
            //    /resources\/js\/.*\.jsx?$/,
            //    /resources\/js\/hocs\/.*\.jsx?$/,
            //],
            //babel: {
            //    presets: [
            //        //['@babel/preset-env', {modules: false}],
            //        ['@babel/preset-env', {modules: false}],
            //        ['@babel/preset-react', {runtime: 'automatic'}],
            //    ],
            //},
        }),
        laravel([
            'resources/sass/app.scss',
            'resources/js/index.jsx',
        ]),
    ],
    resolve: {
        alias: {
            // 'react': path.resolve(__dirname, 'node_modules/react/'),
            // '@areas': path.resolve(__dirname, 'resources/js/areas/'),
            //'@components': path.resolve(__dirname, 'resources/js/components/'),
            // '@utils': path.resolve(__dirname, 'resources/js/utils/'),
        },
    },
});
