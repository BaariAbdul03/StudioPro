# RATE_LIMITING Security Report

## Status: HIGH

## Findings

### 1. No rate limiting on auth endpoints (HIGH)

**Login endpoint:** `POST /api/login` — No rate limiting. An attacker can brute-force passwords without restriction.

**Register endpoint:** `POST /api/register` — No rate limiting. An attacker can create unlimited accounts.

**Checkout endpoints:** — No rate limiting. An attacker can create unlimited checkout sessions.

### 2. Auth throttle config exists but not applied (MEDIUM)

**File:** `backend/config/auth.php:100`
```php
'throttle' => 60,
```

This setting is for Laravel's default password reset throttle, NOT for login/register endpoints. It limits password reset attempts to 60 per minute.

### 3. No rate limiter registered in AppServiceProvider

**File:** `backend/app/Providers/AppServiceProvider.php`

The `boot()` method is empty. No rate limiters are registered via `RateLimiter::for()`.

## What's at risk

- **Brute-force password attack**: Unlimited login attempts
- **Account creation spam**: Unlimited user registrations
- **Resource exhaustion**: Unlimited checkout sessions or form submissions

## What's already secure

- Laravel's framework supports rate limiting via the `throttle` middleware — it just needs to be applied

## Recommendations

1. **HIGH**: Apply Laravel's built-in `throttle` middleware to auth routes:
   - `Route::post('/login', ...)->middleware('throttle:10,15')` (10 attempts per 15 minutes)
   - `Route::post('/register', ...)->middleware('throttle:3,60')` (3 registrations per hour)
   - `Route::post('/checkout/session', ...)->middleware('throttle:20,1')`
