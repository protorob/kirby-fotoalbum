import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ mode }) => ({
  plugins: [tailwindcss()],
  build: {
    watch: mode === 'development' ? {
      include: ['src/**', 'site/templates/**/*.php', 'site/snippets/**/*.php'],
    } : null,
    outDir: 'assets',
    emptyOutDir: false,
    rollupOptions: {
      input: 'src/main.js',
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: '[ext]/[name].[ext]',
      },
    },
  },
}))
