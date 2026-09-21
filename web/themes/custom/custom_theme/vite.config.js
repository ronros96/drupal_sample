import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: 'dist',
    emptyOutDir: true,

    lib: {
      entry: 'js/custom_theme.js',
      formats: ['iife'],
      name: 'swiper',
      fileName: () => 'custom_theme.js',
    },

    rollupOptions: {
      output: {
        inlineDynamicImports: true,
      },
    },
  },
});