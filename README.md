# StudioPro Visual Page Builder

A professional, high-performance drag-and-drop visual page building engine with integrated e-commerce features, dynamic CMS data bindings, and static page export capabilities.

Built using **Vue 3 + Vite** for the visual frontend canvas and **Laravel 13** backed by **Supabase PostgreSQL** for the robust administrative API.

---

## 🚀 Key Features

* **Advanced Visual Editor:** Full-featured GrapesJS integration customized with tailwind utility classes, custom templates, and layout sections.
* **Component Isolation Mode:** Dedicated layout styling workspace for individual symbol layers.
* **CMS Collections:** Dynamic schema definitions and drag-and-drop data queries previewed live in the canvas.
* **Commerce Blocks:** Native integration of product feeds, price tags, and cart workflows.
* **AI Page Generation:** Contextual copy rewriting, SEO metadata optimization, and page layout layout generation.
* **Static Export:** One-click static package packaging (clean HTML/CSS/JS export) for CDNs.

---

## 🛠️ Tech Stack

* **Frontend:** Vue 3 (Composition API), Pinia (State Management), Vue Router, TailwindCSS, GrapesJS, Axios.
* **Backend:** Laravel 13, PHP 8.3+, Sanctum (Token Auth), HTTP Client (SSRF validation).
* **Database:** Supabase PostgreSQL (Stateful API connection via connection pooling).

---

## 💻 Local Setup & Development

### 1. Prerequisites
* PHP 8.3 or higher
* Composer
* Node.js (v20+)
* PostgreSQL / SQLite

### 2. Backend Installation
1. Navigate to the backend directory:
   ```bash
   cd backend
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Set up the environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Adjust `.env` connection parameters to point to your Supabase instance:
   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
   DB_PORT=6543
   DB_DATABASE=postgres
   DB_USERNAME=postgres.your-project-id
   DB_PASSWORD=your-password
   ```
5. Run migrations and database seeding:
   ```bash
   php artisan migrate --seed
   ```
6. Start the local server:
   ```bash
   php artisan serve
   ```

### 3. Frontend Installation
1. Navigate to the frontend directory:
   ```bash
   cd ../frontend
   ```
2. Install packages:
   ```bash
   npm install
   ```
3. Start the Vite development server:
   ```bash
   npm run dev
   ```

---

## 📖 Deployment Guides

For complete step-by-step production deployment instructions, refer to our [Deployment Plan](DEPLOYMENT.md).
