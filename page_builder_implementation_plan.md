# Page Builder App — Portfolio-Grade Implementation Plan

> **Stack:** Laravel 11+ (API Backend) · Vue 3 + Vite (Standalone SPA) · GrapesJS v0.23 (Visual Editor Core) · PostgreSQL (Database)
> **Inspiration:** Shopify Theme Editor · Wix Studio · Webflow Designer
> **Design Theme:** Pro-grade Minimalist (Dark Mode, Geist typeface, Electric Blue/Royal Purple accents)

---

## Foundation Framework Recommendation

> [!IMPORTANT]
> **GrapesJS Core (v0.23.2)** is the recommended open-source engine. Instead of licensing the commercial GrapesJS Studio SDK, we will build a custom Vue 3 shell around the headless GrapesJS core. This matches the exact specifications of the Stitch design system, saves cost, and showcases advanced frontend engineering skills for your portfolio.

### Visual Editor Stack Decisions

| Component | Choice | Reason |
|---|---|---|
| **Editor Core** | Headless GrapesJS (v0.23.2) | Provides HTML/CSS parser, page components tree, drag-and-drop, and styling engine. |
| **Bespoke UI Shell** | Vue 3 + Tailwind/Custom CSS | Disables default GrapesJS panels in favor of Vue-bound controls styled to match the design. |
| **Code Editor** | Styled Custom Textareas | Integrated directly into the style panel and code embed blocks for custom styling/scripts, avoiding bundle size overhead. |
| **Rich Text Controls** | GrapesJS text editing + custom Vue controls | Keeps the editor bundle lightweight while preserving inline copy editing and AI assist workflows. |

---

## Design System Specifications (Stitch Integration)

We are building a highly refined, developer-centric UI styled after **Systematic Minimalism** to align with the Stitch designs.

### UI Tokens & Style Guide (Integrated from stitch-design.md)
*   **Colors & Layering:**
    *   `editor-bg`: Deep Slate/Black `#0B0E14` (Base body background)
    *   `panel-surface`: Dark Navy `#161B22` (Sidebars & Top Header)
    *   `canvas-bg`: Charcoal `#1F2937`
    *   `surface` family: e.g. `surface-container-highest` `#2d3449`
    *   `electric-blue`: Primary Accent `#2E62FF`
    *   `royal-purple`: Secondary Accent `#8B5CF6`
    *   `success-green`: `#10B981` / `warning-amber`: `#F59E0B` / `error-red`: `#EF4444`
    *   `outline-variant`: Borders `#434656`
*   **Typography:**
    *   **Geist:** Used for headlines, body, and labels.
    *   **JetBrains Mono:** Used for `mono-label` elements.
*   **Spacing & Layout:**
    *   `toolbar-height`: 48px
    *   `panel-width`: 280px (for Left & Right sidebars)
    *   Scale: `xs` (4px), `sm` (8px), `md` (12px), `lg` (16px), `xl` (24px)
*   **Shapes & Scrollbar:** 
    *   Small border radius `0.125rem` up to `0.75rem`
    *   Custom Pro-grade scrollbar: 6px width, `#161B22` track, `#334155` thumb (hover `#2E62FF`).

---

## Pure SPA Architecture Overview

```mermaid
graph TB
    subgraph "Frontend — Vue 3 SPA (Vite)"
        A[Vue Router & App Shell] --> B[Project Dashboard]
        A --> C[Visual Editor Workspace]
        C --> D[Bespoke UI Sidebar Panels]
        D --> D1[Left: Layers, Pages & Blocks]
        D --> D2[Right: Style Manager & Settings]
        D --> D3[Top: Breakpoints, History & Deploy]
        C --> E[GrapesJS Headless Engine]
        E --> F[Sandboxed Canvas iframe]
    end

    subgraph "Backend — Laravel 11 API"
        G[Sanctum Auth Middleware] --> H[API Routes]
        H --> I[Project & Page Controllers]
        H --> J[E-Commerce & Checkout API]
        H --> K[Asset Manager Service]
        H --> L[AI Integration (Gemini 3.5)]
        I --> M[(PostgreSQL 16)]
        J --> M
        K --> N[(S3 / Local Storage)]
    end

    C <-->|JSON REST API + CSRF| G
    F -->|Export/Publish| O[Static ZIP Download / Webhook FTP Deploy]
```

---

## Phase 1 — Project Scaffolding & Core Infrastructure


### 1.1 Backend Setup (Laravel 11 API)
*   Initialize Laravel 11 in API mode.
*   Configure Laravel Sanctum to handle authentication for the Vue SPA via cookie-based session sharing.
*   Configure PostgreSQL, enabling the storage of raw page configurations inside `JSONB` columns.
*   Set up local disk and AWS S3 disk config for media asset uploads.

### 1.2 Frontend Setup (Vue 3 Standalone SPA)
*   Scaffold a standalone Vue 3 SPA with Vite in a separate `/frontend` directory or configured within Vite-Laravel plugin.
*   Install core libraries: `vue-router`, `pinia`, `axios`.
*   Set up GrapesJS core (`npm install grapesjs`) without any pre-packaged theme.
*   Initialize a Pinia workspace store to sync selected block elements, editor zoom levels, active pages, and changes.

### 1.3 Database Schema (Single-Tenant)

```sql
-- Single-tenant schema (no tenant_id, structured for simple, high-performance portfolio app)
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE projects (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES users(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    settings JSONB DEFAULT '{}', -- global project configurations (domain, global styles, tokens)
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE pages (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT REFERENCES projects(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    components JSONB DEFAULT '[]', -- GrapesJS components state JSON
    styles JSONB DEFAULT '[]',     -- GrapesJS styling state JSON
    html TEXT,                      -- Rendered static HTML
    css TEXT,                       -- Rendered static CSS
    meta JSONB DEFAULT '{}',       -- SEO meta, title, description, OG tags
    sort_order INT DEFAULT 0,
    is_published BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(project_id, slug)
);

CREATE TABLE assets (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT REFERENCES projects(id) ON DELETE CASCADE,
    filename VARCHAR(255) NOT NULL,
    path VARCHAR(500) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size BIGINT NOT NULL,
    dimensions JSONB, -- {width, height}
    created_at TIMESTAMP DEFAULT NOW()
);
```

### 1.4 Deliverables
*   Laravel backend configured with auth, database migrations, and file storage APIs.
*   Vue 3 SPA initialized with **Project Dashboard UI** (project grids, activity feed, sidebars).
*   GrapesJS engine booting headless inside Vue with data binding.
*   A page saves to and loads from the DB via API.

---

## Phase 2 — Custom Visual Editor UI (Stitch Inspired)


### 2.1 UI Customization
Disable default GrapesJS styling and build custom Vue 3 panel components mapping GrapesJS events:

```
┌──────────────────────────────────────────────────────────┐
│  [Logo]  [Pages | Assets | Settings]  [📱💻🖥️]  [Publish] │ ← Top Bar
├────────────┬─────────────────────────────┬───────────────┤
│            │                             │               │
│  LEFT SIDE │        CANVAS AREA          │  RIGHT SIDE   │
│  (280px)   │        (Level 0)            │  (280px)      │
│  ──────────│                             │ ───────────── │
│ ☰ Layers   │   ┌─────────────────────┐   │ 🎨 Style      │
│ ⊞ Blocks   │   │                     │   │ ⚡ Interact   │
│ ◈ Symbols  │   │  Active Element     │   │ ⚙️ Settings   │
│ 🖼️ Assets   │   │  [Electric Blue Box]│   │ ✔️ Audit      │
│ ⚙️ Config   │   └─────────────────────┘   │ ───────────── │
│  ──────────│                             │ 📐 Spacing    │
│ Navigator  │                             │ 🖼️ Layout     │
│ Tree View  │                             │ ✍️ Typography │
│            │                             │ ✨ Effects    │
└────────────┴─────────────────────────────┴───────────────┘
  [Breadcrumbs]                             [Zoom | Help]    ← Footer
```

### 2.2 Left Sidebar: Navigator & Library
*   **Tabs:** `Layers`, `Blocks`, `Symbols`, `Assets`, `Config`.
*   **Navigator Tree:** Render a reactive hierarchy of components bound to GrapesJS. Includes selection depth indicators with Electric Blue and Success Green accents.
*   **Blocks Library:** Draggable structural elements, text units, embeds, and e-commerce containers.

### 2.3 Right Sidebar: Element Properties Panel
*   **Property Tabs:** `Style`, `Interactions`, `Settings`, `Audit`.
*   **Spacing Grid:** Custom margin and padding 3x3 grid simulator.
*   **Layout Control:** Flex/Grid/Block toggles, direction arrows, and alignment grids.
*   **Typography:** Geist/JetBrains font selectors, weight, size, text-align.
*   **Effects:** Backgrounds, borders, opacity, blur settings.

### 2.4 Canvas Overlay UI
*   Selection box: 2px outline styled in `Electric Blue` `#2E62FF` with selection nodes.
*   Footer Breadcrumbs linking to selected element layers (e.g., `Body > Section > Container`).
*   Direct text editing inside the canvas frame utilizing standard editable hooks.

### 2.5 Deliverables
*   Fully operational 3-panel editor UI styled in the Pro-grade Minimalist theme.
*   Drag-and-drop mechanics from Vue blocks to canvas.
*   Interactive layers panel synced with selection state.
*   Visual CSS editor writing properties back to GrapesJS components.

---

## Phase 3 — E-Commerce Integration (Portfolio Priority)


> [!NOTE]
> Since this is a portfolio project, showing standard E-commerce block binding, dynamic item loading, and Stripe payment processing provides high technical value.

### 3.1 Database Schema Extension

```sql
CREATE TABLE products (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT REFERENCES projects(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    compare_at_price DECIMAL(10, 2),
    sku VARCHAR(100),
    inventory_quantity INT DEFAULT 0,
    images JSONB DEFAULT '[]', -- Array of image URLs/paths
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(project_id, slug)
);

CREATE TABLE product_variants (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT REFERENCES products(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    sku VARCHAR(100),
    inventory_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT REFERENCES projects(id) ON DELETE CASCADE,
    stripe_session_id VARCHAR(255) UNIQUE,
    customer_email VARCHAR(255) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    payment_status VARCHAR(50) DEFAULT 'pending', -- pending, paid, failed
    shipping_details JSONB,
    created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT REFERENCES orders(id) ON DELETE CASCADE,
    product_id BIGINT REFERENCES products(id),
    product_variant_id BIGINT REFERENCES product_variants(id),
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL
);
```

### 3.2 Commerce-Specific Blocks
*   **Product Catalog Block:** Renders a list of items queryable by category.
*   **Product Card Block:** Fully designable container holding elements bound to dynamic properties (`Product Image`, `Product Title`, `Price Tag`, `Compare Price`, `Add to Cart Button`).
*   **Shopping Cart Drawer Component:** An overlay widget injected into the canvas which lists added items, manages item counts, and exposes the Checkout button.
*   **Checkout Trigger:** Submits the cart to the Laravel API, generating a secure Stripe Checkout session.

### 3.3 Backend Processing APIs
*   `GET /api/projects/{slug}/products` (Public API used by published pages).
*   `POST /api/checkout/session` (Initiates Stripe Session and records draft order).
*   `POST /api/webhooks/stripe` (Webhook receiver confirming payment, updating inventory levels, and finalizing order).

### 3.4 Deliverables
*   Product catalog inventory manager in the dashboard.
*   Dynamic E-commerce blocks in the editor.
*   A fully functional cart drawer previewable inside the editor.
*   End-to-end sandbox Stripe payment flow.

---

## Phase 4 — CMS & Dynamic Template Engine


### 4.1 CMS Content Manager
*   **Collections:** A dynamic schema editor (similar to Webflow Collections) where users create content models (e.g. "Blog Posts", "Team Profiles", "Testimonials").
*   **Fields Support:** Rich text, media files, date selectors, references, switch toggles.

```sql
CREATE TABLE cms_collections (
    id BIGSERIAL PRIMARY KEY,
    project_id BIGINT REFERENCES projects(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    fields JSONB NOT NULL, -- [{name: 'Body', type: 'rich-text', key: 'body'}]
    created_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(project_id, slug)
);

CREATE TABLE cms_items (
    id BIGSERIAL PRIMARY KEY,
    collection_id BIGINT REFERENCES cms_collections(id) ON DELETE CASCADE,
    slug VARCHAR(255) NOT NULL,
    data JSONB NOT NULL, -- key-value content mapping fields
    status VARCHAR(50) DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW(),
    UNIQUE(collection_id, slug)
);
```

### 4.2 Dynamic Binding Engine
*   Create a GrapesJS block called **Collection List**.
*   When dropped, the user selects a CMS Collection in the settings panel.
*   Children elements are editable, and properties (e.g. Image Src, Text Content) can be bound to the fields of the collection using a custom dynamic binding popup menu (e.g. `{{ Item.Title }}`).
*   Inside the canvas, placeholders are replaced with actual mock data from the CMS item list.

### 4.3 Deliverables
*   Interactive CMS database schema manager.
*   Dynamic data bindings rendering on the canvas in real-time.
*   Dynamic route parsing (e.g., auto-routing `/blog/{slug}` to a single template layout).

---

## Phase 5 — Advanced Interactions & Code Embeds


### 5.1 Interactive Timelines & Animations
*   Add an **Interactions Panel** in the styling column.
*   **Triggers:** Hover, Click, Scroll-Into-View, Page Load.
*   **Actions:** Move, Scale, Rotate, Opacity, Blur.
*   **Timeline Editor:** Visual keyframe timeline where users adjust duration, delay, stagger, and easing curves (using cubic-bezier) for target elements. Translate these definitions into CSS transitions or lightweight Web Animation API (WAAPI) configurations.

### 5.2 Custom Script Injection
*   **Code Embed Block:** A block rendering a sandboxed iframe. Selection launches styled textarea custom code fields to write custom HTML, CSS, or JS scripts.
*   **Page Script settings:** Textareas for adding tracking pixels, custom styles, or scripts to `<head>` and `<body>` tags.

### 5.3 Forms & SEO Panels
*   **Interactive Form Builder:** Drag-and-drop input inputs, checkboxes, textareas, and submit buttons.
*   **SEO Preview Panel:** Live snippet widget demonstrating Google search results, Facebook OG card, and Twitter cards based on SEO titles and descriptions.

### 5.4 Deliverables
*   Webflow-like custom animations running natively on the canvas.
*   Styled textarea custom code editor integrated into the workspace.
*   SEO meta tagging panel.
*   Custom form submission handler routing form entries to database logs.

---

## Phase 6 — Version Rollbacks & Static Self-Hosting Exports


Since users will self-host their published pages, the platform must facilitate two core deployment paths:

### 6.1 Versioning System

```sql
CREATE TABLE page_versions (
    id BIGSERIAL PRIMARY KEY,
    page_id BIGINT REFERENCES pages(id) ON DELETE CASCADE,
    version_label VARCHAR(100),
    components JSONB NOT NULL,
    styles JSONB NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);
```

### 6.2 Deployment Workflows
1.  **Static HTML/CSS Export:**
    *   Compiles GrapesJS components to flat files.
    *   Inlines CSS, minifies JS, bundle assets locally.
    *   Creates a `.zip` containing:
        *   `index.html` (home page)
        *   `style.css` (global styles & page layout)
        *   `/assets` (minified images/media)
        *   `stripe-checkout.js` (lightweight client wrapper calling the Laravel backend checkout gateway)
2.  **Server Publishing (Webhooks / FTP Deployer):**
    *   Configure FTP/SFTP server credentials or S3 bucket endpoints in project settings.
    *   When clicking **Publish**, a background queued Laravel job compiles the files and automatically pushes them to the configured hosting server.
    *   Provide Webhook notification payloads to trigger third-party builds (e.g., Netlify, Vercel).

### 6.3 Deliverables
*   Commit history panel showing previous page versions.
*   "Download Static ZIP" feature generating clean, optimized bundles.
*   FTP/SFTP publishing agent built inside Laravel.

---

## Phase 7 — AI Integration (Gemini 3.5), Performance & Polish


> [!IMPORTANT]
> The older Gemini 2.0 and 1.5 versions are deprecated. We will integrate Google's latest **Gemini 3.5** models for state-of-the-art AI page generation, copywriting, and layout assistant.

### 7.1 Gemini Model Architecture

```
┌────────────────────────────────────────────────────────┐
│                        Gemini API                      │
├──────────────────────────┬─────────────────────────────┤
│      Gemini configured    │      Gemini 3.5 Flash       │
├──────────────────────────┼─────────────────────────────┤
│ - Complex Reasoning      │ - Ultra-Low Latency         │
│ - Structure Generation   │ - High-Speed Completion     │
│ - Prompts-to-Layouts     │ - Copywriting & Rephrasing  │
│ - Code Block Writing     │ - Image ALT Tagging         │
│                          │ - SEO Description Generator │
└──────────────────────────┴─────────────────────────────┘
```

### 7.2 AI Implementations
*   **AI Page Generation (Gemini configured model):**
    *   Input: "Create a modern landing page for an organic matcha powder shop with a hero, 3 feature grids, and a pricing table."
    *   Process: System provides a structured prompt containing the page builder's component schemas.
    *   Output: The model returns a structured JSON payload mapping GrapesJS components.
    *   Laravel processes and parses this, instantly loading the layout onto the workspace canvas.
*   **AI Copywriter (Gemini 3.5 Flash):**
    *   Select text -> Click "AI Assist" -> Inline dialog popup (Rewrite, Shorten, Professional Tone).
*   **AI SEO Autocomplete (Gemini 3.5 Flash):**
    *   Scans the generated page text content and auto-completes the page title, description, and Alt tags.

### 7.3 Performance Optimization & Testing
*   **Published Page Optimizations:**
    *   Automatic image conversion to WebP format.
    *   Minification of compiled HTML and CSS files.
    *   Critical CSS generation.
*   **Test Suite:**
    *   Backend: PHPUnit tests for the Stripe webhook, CMS dynamic API routes, and FTP publishing code.
    *   Frontend: Vitest unit testing for Vue Pinia stores, Playwright for E2E editor workspace testing (e.g. testing selection and drag-drop actions).

### 7.4 Deliverables
*   AI generation dialogs wired to Gemini 3.5 APIs.
*   Image post-processor converting uploads to optimized formats.
*   Comprehensive test coverage reports.
*   Complete interactive user portfolio presentation.

---

---

## Key Design & Tech Decisions Resolved

*   **SPA Architecture:** Standalone Vue 3 SPA talking to a Laravel 11 API using Sanctum token-based and cookie auth.
*   **Multi-Tenancy:** Disabled. Structured as a robust, single-user dashboard for managing multiple projects.
*   **Hosting Model:** Self-hosted by users. App provides optimized flat file ZIP bundles or publishes files directly over SFTP/FTP/S3 bucket webhooks.
*   **E-Commerce:** Prioritized. Moved to Phase 3 right after the visual editor core is finished.
*   **AI Integration:** Configured with the available Gemini model for layout structures and Gemini 3.5 Flash for copywriting/meta tags. In the current local environment, `gemini-3.5-flash` is the verified working model.
*   **UI Customization:** Open-source GrapesJS core container wrapper. Visual editor interface customized from scratch using the **Stitch Pro-grade Minimalist Dark Theme** parameters.
