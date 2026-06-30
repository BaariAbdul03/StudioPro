# SECURITY_HEADERS Security Report

## Status: CRITICAL

## Findings

### 1. No security headers middleware exists

The only middleware in the application (`AllowLocalSpaCors.php`) only sets CORS headers. There is NO middleware that sets these required security headers:

| Header | Present? |
|--------|----------|
| `Content-Security-Policy` | ❌ Missing |
| `Strict-Transport-Security` | ❌ Missing |
| `X-Frame-Options` | ❌ Missing |
| `X-Content-Type-Options` | ❌ Missing |
| `Referrer-Policy` | ❌ Missing |

### 2. No header configuration in any framework config

There is no configuration file, middleware, or `.htaccess`/`nginx.conf` that sets these headers.

## What's at risk

- **Clickjacking**: Without `X-Frame-Options: DENY`, the application can be embedded in an iframe on an attacker's site
- **MIME-type sniffing**: Without `X-Content-Type-Options: nosniff`, browsers may interpret files as different MIME types, enabling XSS
- **Protocol downgrade**: Without `Strict-Transport-Security`, an attacker can downgrade HTTPS to HTTP
- **XSS via inline scripts**: Without `Content-Security-Policy`, inline scripts can execute unchecked
- **Referrer leakage**: Without `Referrer-Policy`, the full URL (including tokens in query params if any) could be sent to third-party sites

## What's already secure

- Nothing — all five headers are completely absent

## Recommendations

1. **CRITICAL**: Create a global middleware (e.g., `SecurityHeadersMiddleware`) that sets all five headers on every response
2. **CRITICAL**: Register the middleware in the HTTP kernel for all routes
