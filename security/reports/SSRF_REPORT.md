# SSRF Security Report

## Status: MEDIUM

## Findings

### 1. Deploy webhook URL fetching (MEDIUM)

**File:** `backend/app/Http/Controllers/PageController.php:65`

```php
\Illuminate\Support\Facades\Http::timeout(5)->post($project->settings['deploy_webhook'], [...]);
```

The `deploy_webhook` value comes from `$project->settings`, which is user-configurable (stored in the project settings). An attacker who controls a project's settings could set the webhook URL to an internal IP address (e.g., `http://169.254.169.254/` for cloud metadata) or other sensitive internal endpoints.

**No SSRF protection exists:**
- No IP range blocking (private/internal IPs not blocked)
- No DNS resolution check before request
- No URL scheme validation beyond what Laravel's HTTP client provides
- The webhook URL is accepted as-is from the user's project settings

### 2. Gemini API calls (PASS)

**File:** `backend/app/Http/Controllers/AiController.php:180`

The Gemini API calls go to a fixed URL (`https://generativelanguage.googleapis.com/...`). The URL is NOT user-supplied. Only the request body content comes from user input. This is safe from SSRF.

### 3. Supabase Storage calls (PASS)

**File:** `backend/app/Http/Controllers/AssetController.php`

Supabase Storage API calls use the configured `SUPABASE_URL` from `.env`. Not user-supplied. Safe from SSRF.

### 4. Asset upload file reads (PASS)

**File:** `backend/app/Http/Controllers/AssetController.php:42`

`file_get_contents($file->getRealPath())` reads from the uploaded file's real path. This is a local file path, not a URL. Safe.

### 5. Static export file reads (PASS)

**File:** `backend/app/Http/Controllers/PageExportController.php:104`

`file_get_contents($canvasCssPath)` reads a known local file path. Not user-supplied.

## What's at risk

An attacker who controls a project's `deploy_webhook` setting can make the server:
- Send requests to internal cloud metadata endpoints (e.g., AWS `169.254.169.254`)
- Probe internal network services (database, Redis, etc.)
- Exfiltrate data by sending requests to attacker-controlled endpoints with internal data in the body

## What's already secure

- All other HTTP requests go to fixed, pre-configured URLs
- The webhook URL is stored in the database (requires authenticated access to modify)

## Recommendations

1. **MEDIUM**: Add SSRF validation to the deploy webhook URL fetching in `PageController::update()`:
   - Validate the URL scheme is `https://` only
   - Block private IP ranges (127.0.0.0/8, 10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16, 169.254.0.0/16, ::1)
   - Resolve the hostname and verify the IP before making the request
