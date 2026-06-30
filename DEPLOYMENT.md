# StudioPro Production Deployment Plan

This guide outlines the step-by-step procedure to push the repository to GitHub, deploy the frontend SPA, and deploy the Laravel API backend connected to Supabase PostgreSQL.

---

## 📦 Step 0: Push Project to GitHub

1. **Initialize and Commit Code:**
   Open a terminal in the project root (`d:\PageBuilder`) and execute:
   ```bash
   git init
   git add .
   git commit -m "feat: initial commit with supabase pgsql integration and error handling"
   ```

2. **Add Remote and Push:**
   ```bash
   git remote add origin https://github.com/BaariAbdul03/StudioPro.git
   git branch -M main
   git push -u origin main
   ```

---

## 🎨 Step 1: Frontend Deployment (Vue/Vite SPA)

The frontend is a Static Single Page Application (SPA). It should be deployed to static hosting providers (Netlify, Vercel, or Cloudflare Pages) which offer CDN caching and automatic builds from Git.

### Vercel / Netlify Configuration
To prevent `404 Not Found` errors when directly visiting router paths (like `/editor/1/1`), rewrite configurations are included in the repository:
* **Vercel:** Handled via `frontend/vercel.json`
* **Netlify:** Handled via `frontend/public/_redirects`

### Steps
1. Connect your GitHub repository to Vercel/Netlify.
2. Select the `frontend` subdirectory as the root folder.
3. Configure the build settings:
   * **Build Command:** `npm run build`
   * **Output Directory:** `dist`
4. Set the following **Environment Variable** in the hosting dashboard:
   * `VITE_API_BASE_URL`: `https://your-api-domain.com/api` (URL of your live backend API)
5. Deploy.

---

## ⚙️ Step 2: Backend Deployment (Laravel 13 REST API)

Deploy the backend to a PaaS provider (Render, Fly.io, Railway) or VPS (using Laravel Forge).

### 1. Environment Configuration (`.env`)
Configure the production environment variables in your server's settings panel. Do not commit these values to source control.

```dotenv
APP_NAME="StudioPro"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:z1mbawrkjfMNY+dWld9SGBSL2E2LZ/QMuebk8mCUP50= # Replace with generated key
APP_URL=https://your-api-domain.com

# Supabase Postgres Connection
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com # Connection pooler hostname (IPv4)
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.dvtzpeasiunzerlfnkan
DB_PASSWORD=your_supabase_password

# CORS Settings (frontend client origin)
CORS_ALLOWED_ORIGINS=https://your-frontend-domain.vercel.app

# Stateless Session & Sanctum
SESSION_DRIVER=cookie
SESSION_DOMAIN=.yourdomain.com
SANCTUM_STATEFUL_DOMAINS=your-frontend-domain.vercel.app
```

### 2. Deployment Script commands
Run these commands on the server during the build and release phases:

```bash
# 1. Install optimized production dependencies
composer install --no-dev --optimize-autoloader

# 2. Cache configuration, routes, events, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 3. Establish public storage link
php artisan storage:link

# 4. Migrate database schemas (runs safely in production)
php artisan migrate --force
```

### 3. Queue Worker & Cron
If background queues are utilized (e.g. AI generator tasks):
* Run a supervisor queue process: `php artisan queue:work`
* Set up a crontab entry for scheduled tasks:
  ```bash
  * * * * * cd /path/to-your-backend && php artisan schedule:run >> /dev/null 2>&1
  ```

---

## 🔒 Step 3: Security Hardening

Before opening up access to the public:
1. **Force HTTPS:** Handled automatically in `AppServiceProvider.php` when `APP_ENV=production`.
2. **Strict Security Headers:** Ensure the global middleware applies secure HTTP headers (e.g. `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`).
3. **Disable Debug Mode:** Verify `APP_DEBUG=false` is set on your host server environment variables.
