import { defineConfig } from '@playwright/test'

const deployedURL = process.env.STUDIO_PORTFOLIO_URL
const baseURL = deployedURL || 'http://127.0.0.1:5180'

export default defineConfig({
  testDir: './tests/e2e',
  testMatch: 'publicDemo.spec.js',
  use: { baseURL },
  webServer: deployedURL ? undefined : {
    command: 'npm run dev -- --host 127.0.0.1 --port 5180 --strictPort',
    env: { VITE_PUBLIC_DEMO: 'true' },
    url: baseURL,
  },
})
