# DATABASE_ACCESS Security Report

## Status: LOW (architecture-specific)

## Architecture Context

This project uses a **Laravel backend** connecting to **Supabase PostgreSQL** via **direct database connection** (PostgreSQL credentials), NOT through the Supabase JavaScript/API client. This is an important architectural distinction:

- **Direct PostgreSQL connection** (via `config/database.php`): Uses `DB_USERNAME=postgres` and `DB_PASSWORD` to connect directly. This bypasses Supabase RLS entirely — RLS only applies to Supabase's API gateway.
- **Supabase Storage API** (in `AssetController.php`): Uses `SUPABASE_ANON_KEY` to upload/delete files via HTTP. This IS subject to Supabase Storage RLS policies.

## Findings

### 1. Database migrations — all properly scoped with foreign keys (PASS)

All migrations use Eloquent's schema builder with foreign key constraints and cascading deletes:

```php
// Example from page_builder_tables
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->foreignId('project_id')->constrained()->cascadeOnDelete();
```

Tables with foreign key constraints verified:
- `projects` → `user_id` references `users`
- `pages` → `project_id` references `projects`
- `assets` → `project_id` references `projects`
- `products` → `project_id` references `projects`
- `orders` → `project_id` references `projects`
- `order_items` → `order_id` references `orders`, `product_id` references `products`
- `cms_collections` → `project_id` references `projects`
- `cms_items` → `collection_id` references `cms_collections`
- `page_versions` → `page_id` references `pages`
- `form_submissions` → `project_id` references `projects`

### 2. No Supabase RLS policies in code (N/A — architecture-specific)

The checklist asks about RLS policies, but this application connects to Supabase PostgreSQL via **direct database connection** (Laravel Eloquent), NOT through the Supabase API/anonymous client. RLS is only enforced by Supabase's API layer; direct PostgreSQL connections bypass RLS.

**Who can access the database:**
- The `postgres` user (full superuser access — configured in `.env`)
- Any compromised application code on the server

**How access is controlled:**
- Authentication/authorization is handled by Laravel's auth middleware (Sanctum tokens)
- Resource ownership is validated in each controller
- No direct database access from the client-side (browser)

### 3. Supabase Storage — RLS policies need verification (MEDIUM)

**File:** `backend/app/Http/Controllers/AssetController.php`

The `SUPABASE_ANON_KEY` is used to upload and delete files from Supabase Storage:

```php
// Upload
Http::withHeaders([
    'Authorization' => 'Bearer ' . $supabaseKey,
    'apikey' => $supabaseKey,
])->put($supabaseUrl . '/storage/v1/object/' . $bucket . '/' . $uploadPath);

// Delete
Http::withHeaders([
    'Authorization' => 'Bearer ' . $supabaseKey,
    'apikey' => $supabaseKey,
])->delete($supabaseUrl . '/storage/v1/object/' . $bucket, [
    'prefixes' => [$storagePath]
]);
```

The anon key is designed to be public. However, if the Supabase Storage bucket `studiopro-assets` does NOT have RLS policies configured, then anyone with the anon key could:
- Upload arbitrary files
- Delete existing files
- List all files in the bucket

The RLS configuration for this bucket must be verified in the Supabase dashboard.

### 4. Database config — SSL mode set to "prefer" (LOW)

**File:** `backend/config/database.php`

```php
'sslmode' => env('DB_SSLMODE', 'prefer'),
```

The default SSL mode is `prefer`, which means the connection will use SSL if the server supports it, but will fall back to an unencrypted connection. For production, this should be `require` to ensure all data in transit is encrypted.

### 5. SQLite fallback — no concerns (PASS)

The config has a SQLite fallback for local development, which is a standard pattern.

## What's at risk

- **Supabase Storage bucket**: If RLS is not configured on the `studiopro-assets` bucket, anyone with the anon key can upload/delete files
- **SSL downgrade**: With `sslmode=prefer`, a man-in-the-middle attack could force an unencrypted database connection
- **Database credentials in `.env`**: Full superuser access if `.env` is compromised

## What's already secure

- All migrations use foreign key constraints with cascading deletes (referential integrity)
- Laravel Eloquent ORM provides parameterized queries (SQL injection protection)
- No direct database access from frontend/client-side code
- Auth middleware controls access to data at the application layer
- Ownership checks in controllers validate resource access

## Recommendations

1. **MEDIUM**: Verify Supabase Storage RLS policies in the Supabase dashboard for the `studiopro-assets` bucket
2. **LOW**: Set `DB_SSLMODE=require` in `.env` for production to enforce encrypted database connections
3. **LOW**: Consider adding RLS policies to Supabase tables if the architecture is ever changed to use Supabase's API-based access (e.g., for direct client-side queries)
