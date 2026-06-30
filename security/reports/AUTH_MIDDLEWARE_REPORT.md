# AUTH_MIDDLEWARE Security Report

## Status: HIGH

## Route Inventory

### PUBLIC routes (no auth middleware)

| Method | Path | Handler | Risk |
|--------|------|---------|------|
| POST | `/register` | AuthController@register | ✅ Correct — registration must be public |
| POST | `/login` | AuthController@login | ✅ Correct — login must be public |
| POST | `/checkout/session` | CheckoutController@session | ⚠️ **HIGH** — Creates orders for ANY project without auth |
| GET | `/checkout/simulate` | CheckoutController@simulateView | ⚠️ **MEDIUM** — Unauthenticated order lookup |
| POST | `/checkout/simulate/pay` | CheckoutController@simulatePay | ⚠️ **HIGH** — Modifies order status + deducts inventory without auth |
| POST | `/checkout/simulate/cancel` | CheckoutController@simulateCancel | ⚠️ **HIGH** — Modifies order status without auth |
| GET | `/` | welcome view | ✅ Correct — public landing page |

### PROTECTED routes (auth:sanctum middleware)

| Method | Path | Handler | Status |
|--------|------|---------|--------|
| GET | `/user` | Closure | ✅ Protected |
| PUT | `/user/profile` | AuthController@updateProfile | ✅ Protected |
| PUT | `/user/password` | AuthController@updatePassword | ✅ Protected |
| POST | `/logout` | AuthController@logout | ✅ Protected |
| GET/POST | `/projects` | ProjectController | ✅ Protected |
| GET/PUT/DELETE | `/projects/{project}` | ProjectController | ✅ Protected |
| GET/POST | `/projects/{project}/pages` | PageController | ✅ Protected |
| GET/PUT/DELETE | `/projects/{project}/pages/{page}` | PageController | ✅ Protected |
| GET/POST | `/projects/{project}/assets` | AssetController | ✅ Protected |
| DELETE | `/projects/{project}/assets/{asset}` | AssetController | ✅ Protected |
| GET/POST | `/projects/{project}/pages/{page}/versions` | PageVersionController | ✅ Protected |
| POST | `/projects/{project}/pages/{page}/versions/{version}/restore` | PageVersionController | ✅ Protected |
| GET | `/projects/{project}/pages/{page}/export` | PageExportController | ✅ Protected |
| POST | `/projects/{project}/ai/generate-page` | AiController | ✅ Protected |
| POST | `/projects/{project}/ai/copy` | AiController | ✅ Protected |
| POST | `/projects/{project}/ai/seo` | AiController | ✅ Protected |
| GET/POST | `/projects/{project}/products` | ProductController | ✅ Protected |
| GET/PUT/DELETE | `/projects/{project}/products/{product}` | ProductController | ✅ Protected |
| GET/POST | `/projects/{project}/collections` | CmsCollectionController | ✅ Protected |
| GET/PUT/DELETE | `/projects/{project}/collections/{collection}` | CmsCollectionController | ✅ Protected |
| GET/POST | `/collections/{collection}/items` | CmsItemController | ✅ Protected |
| GET/PUT/DELETE | `/collections/{collection}/items/{item}` | CmsItemController | ✅ Protected |
| GET | `/projects/{project}/form-submissions` | FormSubmissionController@index | ✅ Protected |
| POST | `/projects/{project}/form-submissions` | FormSubmissionController@store | ✅ Protected |

## Findings

### 1. All API resource routes properly protected (PASS)

All `apiResource` routes (projects, pages, assets, products, collections, items) are correctly nested inside the `auth:sanctum` middleware group. Auth runs BEFORE the handler.

### 2. Auth middleware runs before handler (PASS)

The `auth:sanctum` middleware is applied at the route group level, not inside controller methods. This means authentication is verified BEFORE any controller code executes. This is the correct pattern per the checklist.

### 3. Checkout endpoints are completely unprotected (CRITICAL)

**File:** `backend/routes/api.php` (line: `Route::post('/checkout/session', ...)`)
**File:** `backend/routes/web.php`

The checkout/session endpoint and all web-based checkout simulation endpoints have NO authentication:

```php
// api.php - unprotected
Route::post('/checkout/session', [CheckoutController::class, 'session']);

// web.php - unprotected
Route::post('/checkout/simulate/pay', [CheckoutController::class, 'simulatePay']);
Route::post('/checkout/simulate/cancel', [CheckoutController::class, 'simulateCancel']);
Route::get('/checkout/simulate', [CheckoutController::class, 'simulateView']);
```

An unauthenticated attacker can:
- Create orders for any project by guessing project IDs
- Mark any order as "paid" via `/checkout/simulate/pay`
- Deduct inventory from any product via `/checkout/simulate/pay`
- View order details via `/checkout/simulate?session_id=...`

### 4. No admin role check exists (MEDIUM)

There is no admin middleware or role-checking anywhere in the codebase. The `users` table has no `role` column in the migration. All authenticated users have the same level of access. While this may be intentional for a single-user/small-team tool, there's no mechanism to restrict admin functions.

### 5. Form submission endpoints return 401 correctly (PASS)

The `FormSubmissionController` is behind auth middleware. Unauthenticated requests to form submissions will return 401.

## What's at risk

- **Checkout abuse**: An attacker can repeatedly call `/checkout/simulate/pay` with valid session IDs to mark arbitrary orders as paid and drain inventory
- **Order data exposure**: Anyone who knows or guesses a session ID can view order details via `/checkout/simulate`
- **No admin/user separation**: If the app scales to multi-user, there's no way to enforce admin-only actions

## What's already secure

- Sanctum token auth is used correctly with `auth:sanctum` middleware
- Auth middleware is applied at the **route group level** (before handler execution)
- All resource (CRUD) endpoints are properly protected
- Frontend properly handles 401 responses by clearing tokens and redirecting to login
- Frontend auto-attaches Bearer tokens to all requests via fetch interceptor and Axios interceptor

## Recommendations

1. **CRITICAL**: Add `auth:sanctum` middleware to the checkout API endpoint (`/checkout/session`) — or at minimum verify the user owns the project before creating orders
2. **CRITICAL**: Add `auth:sanctum` middleware to `simulatePay` and `simulateCancel` web routes, or restrict them by IP/environment for development use only
3. **MEDIUM**: Add a `role` column to users table and admin middleware for future-proofing
