# FILE_UPLOADS Security Report

## Status: MEDIUM

## Findings

### 1. File type validation uses Laravel's file validation (MEDIUM)

**File:** `backend/app/Http/Controllers/AssetController.php:16`

```php
'file' => 'required|file|image|max:10240',
```

Laravel's `image` validation rule checks the MIME type using the file's magic bytes (via Symfony's MIME type guesser), not just the file extension. This correctly validates file types.

However, the `file` rule doesn't restrict which image types are allowed — it accepts ALL image types (JPEG, PNG, GIF, WebP, BMP, etc.). Some image types could potentially be used for polyglot attacks.

### 2. File renaming uses `uniqid()` not UUIDs (MEDIUM)

```php
$filename = uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
```

- `uniqid()` is time-based and predictable (not a true UUID)
- The original filename is appended (with spaces replaced) — this leaks the original filename
- A more secure approach would use `Str::uuid()` or `Str::random(40)` with the original extension

### 3. Files stored on Supabase Storage (PASS)

The primary storage backend is Supabase Storage, which is a separate domain from the application:
```php
$supabaseUrl . '/storage/v1/object/public/' . $bucket . '/' . $uploadPath;
```

Storing files on a separate domain is correct for security (prevents same-origin attacks). If Supabase is unavailable, files fall back to the local `public` disk:
```php
$path = $file->store('uploads', 'public');
```

The local fallback stores files under `storage/app/public/uploads/`, which is symlinked to `public/storage/uploads/`. Files stored locally are same-origin.

### 4. Size limit enforced server-side (PASS)

```php
'max:10240' // 10MB max
```

A 10MB size limit is enforced server-side via Laravel validation.

### 5. Delete endpoint doesn't validate file path

The delete endpoint constructs the storage path from the database-recorded URL:
```php
$parts = explode('/' . $bucket . '/', $asset->path);
```

If an attacker could manipulate the `path` value in the database, this could delete arbitrary objects from Supabase Storage. However, the path is set at upload time and protected by ownership checks.

## What's at risk

- Predictable filenames could make uploaded files enumerable
- Local storage fallback serves files same-origin (XSS risk if a user uploads an SVG with script content)
- The `image` validation rule may accept SVG files (vectors) which are not checked by magic bytes in the same way as raster images

## What's already secure

- File type validated by MIME/magic bytes (not just extension)
- Server-side size limit of 10MB
- Primary storage on separate domain (Supabase Storage)
- Ownership checks on upload and delete
- Supabase Storage RLS can restrict access in production

## Recommendations

1. **MEDIUM**: Use `Str::uuid()` instead of `uniqid()` for file naming
2. **MEDIUM**: Add SVG validation (if SVGs are allowed) or block SVG uploads explicitly
3. **LOW**: Restrict allowed image MIME types to only what's needed (e.g., `image/jpeg,image/png,image/webp`)
