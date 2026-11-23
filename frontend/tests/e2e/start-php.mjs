import { randomBytes } from 'node:crypto'
import { spawn, spawnSync } from 'node:child_process'
import { mkdtempSync, rmSync, writeFileSync } from 'node:fs'
import { tmpdir } from 'node:os'
import { join } from 'node:path'
import { fileURLToPath } from 'node:url'

const working = mkdtempSync(join(tmpdir(), 'studio-browser-'))
const database = join(working, 'database.sqlite')
const php = process.env.PHP_BINARY || 'php'
const cwd = fileURLToPath(new URL('../../../backend/', import.meta.url))
const origin = new URL(process.env.STUDIO_TEST_BACKEND_ORIGIN || 'http://127.0.0.1:8000')
const env = {
  ...process.env,
  APP_ENV: 'testing',
  APP_KEY: 'base64:' + randomBytes(32).toString('base64'),
  APP_URL: origin.origin,
  APP_DEBUG: 'false',
  DB_CONNECTION: 'sqlite',
  DB_DATABASE: database,
  DB_URL: '',
  SESSION_DRIVER: 'database',
  SESSION_SECURE_COOKIE: 'false',
  SESSION_DOMAIN: '',
  CACHE_STORE: 'array',
  GEMINI_API_KEY: '',
  STUDIO_AI_ENABLED: 'true',
  STUDIO_STITCH_ENABLED: 'false',
  MEDIA_DISK_DRIVER: 'local',
  PUBLISHED_DISK_DRIVER: 'local',
  STUDIO_PUBLIC_URL: origin.origin,
  NEON_PUBLISHED_URL: '',
  PUBLISHED_LOCAL_ROOT: join(working, 'published')
}

try {
  writeFileSync(database, '')
  for (const args of [
    ['artisan', 'migrate', '--force'],
    ['tests/provision-browser.php'],
    ['artisan', 'studio:provision', 'owner@example.test']
  ]) {
    const result = spawnSync(php, args, { cwd, env, stdio: 'inherit' })
    if (result.error) throw result.error
    if (result.status !== 0) throw new Error('Could not initialize the browser fixture')
  }
  const server = spawn(php, ['-S', origin.host, '-t', 'public', 'public/index.php'], { cwd, env, stdio: 'inherit' })
  process.on('SIGTERM', () => server.kill())
  process.on('SIGINT', () => server.kill())
  await new Promise((resolve, reject) => {
    server.once('exit', resolve)
    server.once('error', reject)
  })
} finally {
  rmSync(working, { recursive: true, force: true })
}
