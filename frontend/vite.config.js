import { createHash } from 'node:crypto'
import { readFileSync } from 'node:fs'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

const canvasVersion = createHash('sha256')
for (const file of ['public/canvas.css', 'src/data/templates.js', 'build-template-css.mjs']) {
  canvasVersion.update(readFileSync(new URL('./' + file, import.meta.url)))
}

export default defineConfig({
  plugins: [vue()],
  define: { __STUDIO_CANVAS_VERSION__: JSON.stringify(canvasVersion.digest('hex').slice(0, 12)) },
  server: {
    proxy: Object.fromEntries(['/api', '/sanctum', '/checkout', '^/p/', '/studio-published'].map(path => [
      path, { target: process.env.STUDIO_TEST_BACKEND_ORIGIN || 'http://127.0.0.1:8000' }
    ]))
  }
})
