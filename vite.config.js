import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
    build: {
        outDir: 'public/build',
        rollupOptions: {
            input: {
                main: resolve(__dirname, 'assets/main.js'),
                style: resolve(__dirname, 'assets/main.css')
            },
            output: {
                entryFileNames: 'js/[name].js',
                chunkFileNames: 'js/[name].js',
                assetFileNames: 'css/[name].[ext]'
            }
        }
    }
});
