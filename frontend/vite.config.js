import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: Object.fromEntries(['/api', '/sanctum', '/checkout', '^/p/', '/studio-published'].map(path => [
      path, { target: process.env.STUDIO_TEST_BACKEND_ORIGIN || 'http://127.0.0.1:8000' }
    ]))
  }
})
