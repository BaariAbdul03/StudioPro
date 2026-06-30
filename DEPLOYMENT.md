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

To prevent cold starts and sleeping containers (a major limitation of Render's free tier), the recommended strategy is to deploy the backend to a cheap Virtual Private Server (VPS) starting at $4–$5/month (e.g., Hetzner Cloud or DigitalOcean) managed by **Coolify** (a free, self-hosted open-source Heroku alternative).

### Why VPS + Coolify?
* **Always-On:** The container never sleeps. Zero cold-start delays.
* **Low Cost:** Host the API backend, Postgres database, and Redis cache all on a single $4/month VPS.
* **Zero Complexity:** Coolify handles automatic Git-push deployments, Let's Encrypt SSL certificates, and Docker container routing automatically.

### 1. Backend Dockerfile Configuration
The application is pre-configured with a production-ready [backend/Dockerfile](file:///d:/PageBuilder/backend/Dockerfile) utilizing **FrankenPHP**.
* **FrankenPHP** is a modern PHP server (written in Go) that runs your Laravel API at extreme speed inside a single container.
* No need to set up Nginx, PHP-FPM, or Unix sockets separately.

### 2. Coolify Setup Steps
1. Provision a VPS (e.g. Hetzner CX22 or DigitalOcean basic Droplet).
2. Install Coolify by running this command on the server via SSH:
   ```bash
   curl -fsSL https://coolify.io/install.sh | bash
   ```
3. Open the Coolify dashboard in your browser.
4. Add a new **Resource** -> **Application** and link it to your GitHub repository: `https://github.com/BaariAbdul03/StudioPro.git`
5. Select the `backend` directory as the build root, and choose **Dockerfile** as the build pack (Coolify will automatically build the `Dockerfile` we created).
6. Set the application domain (e.g., `api.yourdomain.com`). Coolify will configure the proxy and SSL certificate.

### 3. Environment Configuration (`.env`)
Configure these production environment variables in the Coolify dashboard under the **Environment Variables** tab:

```dotenv
APP_NAME="StudioPro"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:z1mbawrkjfMNY+dWld9SGBSL2E2LZ/QMuebk8mCUP50= # Replace with new generated key
APP_URL=https://api.yourdomain.com

# Database Connection (Supabase PgSQL)
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

### 4. Build and Release Execution
When you trigger a deployment in Coolify, the following commands should be run during the container start phase (handled automatically inside the container or via the Post-deployment script settings in Coolify):
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan migrate --force
```

---

## 🔒 Step 3: Security Hardening

Before opening up access to the public:
1. **Force HTTPS:** Handled automatically in `AppServiceProvider.php` when `APP_ENV=production`.
2. **Strict Security Headers:** Ensure the global middleware applies secure HTTP headers (e.g. `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`).
3. **Disable Debug Mode:** Verify `APP_DEBUG=false` is set on your host server environment variables.
