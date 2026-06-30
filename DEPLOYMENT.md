# StudioPro Production Deployment Plan

This guide outlines two strategies for deploying the StudioPro Page Builder:
* **Option A (Recommended for Free Tier):** 100% Free Single-Service Deployment on Render. Frontend and backend are built together in a single container.
* **Option B (Recommended for Production):** Split-service deployment using Vercel (Frontend) and a cheap VPS + Coolify (Backend) to prevent container sleeping.

---

## 📦 Step 0: Push Project to GitHub
Initialize your Git repository and push your changes to GitHub first:
```bash
git init
git add .
git commit -m "feat: initial commit with single service docker build"
git remote add origin https://github.com/BaariAbdul03/StudioPro.git
git branch -M main
git push -u origin main
```

---

## 🆓 Option A: 100% Free Single-Service Deployment (Render)

This strategy compiles the Vue/Vite frontend and embeds it inside the Laravel directory. FrankenPHP serves both the static frontend assets and Laravel API endpoints inside a single container on Render's free plan.

### Benefits
* **Cost:** $0/month. No paid accounts needed.
* **No CORS Complexities:** Frontend and backend run on the exact same domain.
* **Unified Sleep Cycle:** When the container sleeps, the first request wakes up both the frontend and API simultaneously (avoiding broken UI states).

### Steps

1. **Create a Web Service on Render:**
   * Go to [Render Dashboard](https://dashboard.render.com/) and click **New** -> **Web Service**.
   * Link your GitHub repository.
   * **Language:** `Docker`
   * **Dockerfile Path:** `backend/Dockerfile`
   * **Build Context:** `.` (Ensure the build context is set to the repository root so the Dockerfile can access both `frontend/` and `backend/` directories).

2. **Configure Environment Variables:**
   Under the **Environment** tab in Render, add the following variables:
   ```dotenv
   APP_NAME="StudioPro"
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:z1mbawrkjfMNY+dWld9SGBSL2E2LZ/QMuebk8mCUP50= # Generate with: php artisan key:generate
   APP_URL=https://your-render-subdomain.onrender.com

   # Supabase Connection
   DB_CONNECTION=pgsql
   DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
   DB_PORT=6543
   DB_DATABASE=postgres
   DB_USERNAME=postgres.dvtzpeasiunzerlfnkan
   DB_PASSWORD=your_supabase_password

   # Stateless Sessions
   SESSION_DRIVER=cookie
   ```

3. **Deploy:**
   Click **Create Web Service**. Render will run the multi-stage build, compile the Vue frontend, configure FrankenPHP, run migrations, and serve the application at `https://your-render-subdomain.onrender.com`.

---

## ⚡ Option B: High-Performance Deployment (Vercel + VPS/Coolify)

Use this option to keep the backend permanently awake (no 50-second cold starts) on a cheap VPS ($4-$5/month on Hetzner or DigitalOcean) managed via Coolify.

### 🎨 Part 1: Frontend (Vercel / Netlify)
Deploy static files to edge CDNs.
1. Connect your repository to Vercel/Netlify.
2. Set **Root Directory** to `frontend`.
3. **Build Command:** `npm run build`, **Output Directory:** `dist`.
4. Set env variable: `VITE_API_BASE_URL=https://api.yourdomain.com/api`
5. Deploy. (SPA routing fallback is pre-configured via `vercel.json` and `_redirects`).

### ⚙️ Part 2: Backend (VPS + Coolify)
1. Buy a VPS ($4/month CX22 on Hetzner Cloud).
2. Install Coolify via SSH:
   ```bash
   curl -fsSL https://coolify.io/install.sh | bash
   ```
3. Open Coolify web portal -> click **New Resource** -> **Application** -> link your repository.
4. Set **Build Context** to `.` and **Dockerfile Path** to `backend/Dockerfile`.
5. In **Environment Variables**, configure `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, and your Supabase PostgreSQL credentials.
6. Under Settings, set the domain (e.g. `api.yourdomain.com`). Coolify will configure reverse proxy and SSL automatically.
7. Deploy.
