import { defineConfig } from '@playwright/test'
import { randomBytes } from 'node:crypto'

const password = process.env.STUDIO_BROWSER_PASSWORD || randomBytes(24).toString('base64url')
process.env.STUDIO_BROWSER_PASSWORD = password
const backendOrigin = 'http://127.0.0.1:8180'

export default defineConfig({
  testDir: './tests/e2e',
  testMatch: 'editor.spec.js',
  workers: 1,
  use: { baseURL: 'http://127.0.0.1:5178' },
  webServer: [
    {
      command: 'node tests/e2e/start-php.mjs',
      url: backendOrigin + '/api/health',
      env: { STUDIO_BROWSER_PASSWORD: password, STUDIO_TEST_BACKEND_ORIGIN: backendOrigin },
      timeout: 60000
    },
    {
      command: 'npm run dev -- --host 127.0.0.1 --port 5178 --strictPort',
      url: 'http://127.0.0.1:5178',
      env: { STUDIO_TEST_BACKEND_ORIGIN: backendOrigin },
      timeout: 60000
    }
  ]
})
