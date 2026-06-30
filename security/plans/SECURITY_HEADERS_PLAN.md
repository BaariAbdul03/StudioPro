# SECURITY_HEADERS Fix Plan

## Changes

- `backend/app/Http/Middleware/SecurityHeadersMiddleware.php` — New middleware to set all 5 security headers
- Register middleware in bootstrap or via route config

## New files

- `backend/app/Http/Middleware/SecurityHeadersMiddleware.php`

## Verification goals

After implementation, ALL of these must be true:

- [ ] All 5 security headers present on every API response
- [ ] Headers set via a single global middleware
- [ ] CSP header allows necessary resources (Google Fonts, Supabase, etc.)
