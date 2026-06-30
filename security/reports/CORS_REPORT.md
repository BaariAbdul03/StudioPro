# CORS Security Report

## Status: MEDIUM

## Findings

### 1. Explicit allowlist used (PASS)

**File:** `backend/app/Http/Middleware/AllowLocalSpaCors.php`

```php
private const ALLOWED_ORIGINS = [
    'http://127.0.0.1:5173',
    'http://localhost:5173',
];
```

The middleware uses an explicit allowlist — NOT a wildcard `*`. This is correct.

### 2. No wildcard with credentials (PASS)

```php
$response->headers->set('Access-Control-Allow-Credentials', 'true');
```

`credentials: true` is NOT combined with a wildcard origin. It's combined with specific origins. This is correct.

### 3. Only development origins configured (HIGH)

The allowed origins are only `localhost:5173` and `127.0.0.1:5173` — the Vite dev server. In production, the application needs to add the production domain(s) to the allowlist. Without doing so, CORS will fail for legitimate production requests.

### 4. No framework CORS config used

Laravel has a built-in CORS configuration (`config/cors.php`), but this project uses a custom middleware instead. The custom middleware is simpler but lacks features like:
- Dynamic origin reflection (only allowlist-based)
- Per-route CORS configuration

## What's at risk

- **Production CORS failures**: If the production domain is not added to `ALLOWED_ORIGINS`, the SPA won't be able to make API calls from the production frontend
- **No risk of wildcard abuse**: The explicit allowlist prevents the most dangerous CORS misconfigurations

## What's already secure

- Explicit origin allowlist (no `*`)
- `credentials: true` used only with specific origins
- `Vary: Origin` header set to prevent cache poisoning

## Recommendations

1. **HIGH**: Add production domains to the `ALLOWED_ORIGINS` array when deploying
2. **LOW**: Consider using environment variables for allowed origins instead of hardcoded constants
