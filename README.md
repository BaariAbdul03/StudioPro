# StudioPro

A visual page builder for a single-client workspace. Start from one of 20 templates, edit in GrapesJS, isolate a component, compare three viewport widths, and publish a public URL or download a static ZIP. The portfolio includes a finished sample visitors can edit in their browser.

## Local setup

Requirements are PHP 8.4.28 or newer in the 8.4/8.5 series, Composer 2, and Node 24. Enable PHP's PDO SQLite, fileinfo, GD, and ZIP extensions for local development and tests. Use PDO PostgreSQL for Neon.

1. From `backend`, run `composer install`. Copy `.env.example` to `.env` if it is missing, then run `php artisan key:generate` for the new file. With an existing Supabase environment file, set `DB_CONNECTION=sqlite`, clear `DB_URL`, and use the local filesystem settings in the example.
2. For SQLite, run `php artisan migrate`. For Neon, use the setup below. Run `php artisan studio:provision owner@example.test` to create an owner and sample using a private password. Start Laravel with `php artisan serve --no-reload` so that uploads can use the system temporary directory on Windows.
3. From `frontend`, run `npm ci --ignore-scripts` and `npm run dev`. Open `http://127.0.0.1:5173` to sign in. Open `/demo` to edit the sample within your browser tab.

The Vite development server proxies private API requests to Laravel at `127.0.0.1:8000`. Use `npm run build:portfolio` for Cloudflare Pages. See [Deploy StudioPro](DEPLOYMENT.md) for Northflank and Neon configuration.

The public landing page and FAQ are available at `/welcome` in local development. The site information pages live at `/about`, `/privacy`, and `/terms`.

## Neon Postgres

Run `neon deploy` from the repository root, then `neon env pull --file backend/.env --service postgres` so Laravel can load the generated variables. Use `DB_CONNECTION=pgsql`. Laravel uses `DATABASE_URL` for pooled traffic and `DATABASE_URL_UNPOOLED` for migrations. Existing `DB_URL` and `DB_DIRECT_URL` overrides take priority. Neon connections use `verify-full` and need a trusted CA bundle through `DB_SSLROOTCERT` or PHP’s CA configuration.

From `backend`, run `php artisan migrate --database=pgsql_direct --force` to apply the workspace schema. Run `npm run build` at the repository root to build the frontend. For local HTTP development, use `APP_ENV=local`, `APP_URL=http://127.0.0.1:8000`, and `SESSION_SECURE_COOKIE=false` in the Laravel process environment.

## Local AI tools

Sign in and open a project to generate a draft, rewrite selected copy, or suggest SEO metadata. Set `GEMINI_API_KEY` in the backend environment to use Gemini. The built-in templates and text rules work without provider credentials. The editor shows which produced a result.

To enable Stitch locally, run `npm ci --ignore-scripts --prefix backend` from the root. Set `STUDIO_STITCH_ENABLED=true`, `STITCH_PROJECT_ID`, `STITCH_API_KEY`, and `CACHE_STORE=database` in the backend environment. On Windows, also set `DB_QUEUE_RETRY_AFTER=180` and run `php artisan queue:work database --queue=stitch --timeout=158 --tries=1` from `backend` alongside the app. On Linux, Laravel starts a background process for each request.

## Checks

- Backend from `backend` with `php artisan test`.
- Frontend from `frontend` with `npm run test:unit` and `npm run build`.
- Browser tests from `frontend` after `npx playwright install chromium`. Run `npm run test:e2e` for the private workflow and `npm run test:e2e:public` for the portfolio. The private suite starts a PHP server on port 8180 and uses a SQLite fixture. Set `PHP_BINARY` if PHP is outside your PATH.
