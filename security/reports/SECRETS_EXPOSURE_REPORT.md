# SECRETS_EXPOSURE Security Report

## Status: HIGH

## Findings

### 1. `.env.example` contains real Supabase project identifiers

**File:** `backend/.env.example`

The `.env.example` file (which IS tracked by git and serves as a template) contains real, non-placeholder values:

```env
DB_HOST=db.dvtzpeasiunzerlfnkan.supabase.co
SUPABASE_URL=https://dvtzpeasiunzerlfnkan.supabase.co
SUPABASE_STORAGE_BUCKET=studiopro-assets
```

While `SUPABASE_URL` alone is not a secret (it's the project URL, visible in your browser when using Supabase dashboard), committing the real Supabase project hostname to a public-facing `.env.example` exposes your Supabase **project subdomain**, making it easier for attackers to target your specific Supabase instance. The project ID `dvtzpeasiunzerlfnkan` should be replaced with a placeholder like `your-project.supabase.co`.

### 2. `.env.example` contains real DB_HOST

The `DB_HOST` value `db.dvtzpeasiunzerlfnkan.supabase.co` exposes the database hostname. While the actual `DB_PASSWORD` is empty (placeholder), the real hostname helps attackers identify the database endpoint.

### 3. `.env` file is properly git-ignored (pass)

**File:** `backend/.gitignore` (line 3: `.env`)

Verified: `git check-ignore -v backend/.env` confirms `.env` is ignored via `backend/.gitignore:3:.env`.

### 4. No hardcoded API keys in source code (pass)

Searched for `sk_live_`, `sk_test_`, `AKIA`, private keys, and similar patterns across all PHP, JS, Vue, TS, and config files. No matches found in source files (only in vendor/node_modules).

### 5. `backend/.env` contains REAL secrets (not tracked by git)

The actual `backend/.env` file (not committed) contains live secrets:

| Secret | Value | Risk if leaked |
|--------|-------|----------------|
| `APP_KEY` | `base64:REDACTED` | Session forgery, encrypted data decryption |
| `GEMINI_API_KEY` | `AQ.Ab8RN...[REDACTED]` | Unauthorized Gemini API usage, billing charges |
| `STITCH_API_KEY` | `AQ.Ab8RN...[REDACTED]` | Unauthorized Stitch API usage |
| `SUPABASE_ANON_KEY` | `eyJhbGciOiJIUzI1NiIs...[REDACTED]` | Anonymous Supabase data access (RLS-reliant) |
| `SUPABASE_URL` | `https://dvtzpeasiunzerlfnkan.supabase.co` | Project endpoint exposure |

These are NOT committed because `backend/.env` is gitignored. However, **any developer with access to this machine can read these secrets.**

### 6. No VITE_ env vars holding secrets (pass)

The only `VITE_` env var in frontend source code is:
```js
apiBaseUrl: import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api',
```

`VITE_API_BASE_URL` is the API base URL — not a secret. No `VITE_` prefixed env vars contain API keys, tokens, or passwords.

### 7. Frontend Bearer token usage (acceptable pattern)

**File:** `frontend/src/main.js`
```js
options.headers.set('Authorization', `Bearer ${token}`);
```

The Bearer token comes from the authentication store (user's session token), not a hardcoded secret. This is standard practice.

### 8. No `.env*` pattern in root `.gitignore`

**File:** `.gitignore` (root)

The root `.gitignore` does **not** include `.env` or `.env.*` patterns. Only `backend/.gitignore` has `.env` protection. If a `.env` file is ever created in the **root directory** of the project, it would NOT be git-ignored.

## What's at risk

- An attacker who gains access to the `.env.example` can identify the exact Supabase project being used.
- If the root `.gitignore` ever changes or if someone creates a `.env` at the project root, secrets could be committed.
- If `.env.example` is pushed to a public repository, the Supabase project URL is exposed, enabling targeted attacks (DDoS, enumeration, SSRF attempts on the Supabase project).

## What's already secure

- `backend/.gitignore` properly ignores `.env`, `.env.backup`, and `.env.production`
- `git ls-files .env` returns nothing (no `.env` files are tracked)
- No `sk_live_`/`sk_test_` Stripe keys in any source file
- No AWS secret keys hardcoded in source files
- No hardcoded passwords in source code (app passwords come from `.env` which is gitignored)
- Frontend only uses `VITE_API_BASE_URL` which is a non-secret config value

## Recommendations

1. **HIGH**: Replace real Supabase project identifiers in `backend/.env.example` with placeholders
   - `SUPABASE_URL=https://dvtzpeasiunzerlfnkan.supabase.co` → `SUPABASE_URL=https://your-project.supabase.co`
   - `DB_HOST=db.dvtzpeasiunzerlfnkan.supabase.co` → `DB_HOST=`
   - `SUPABASE_STORAGE_BUCKET=studiopro-assets` → `SUPABASE_STORAGE_BUCKET=your-bucket`

2. **MEDIUM**: Add `.env` and `.env.*` patterns to the root `.gitignore` as a defense-in-depth measure

3. **LOW**: Add a comment in `.env.example` explaining that all values must be replaced with real credentials before use
