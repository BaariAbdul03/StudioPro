# ACCESS_CONTROL Fix Plan

## Changes

Add `abort_unless($project->user_id === auth()->id(), 403)` to every controller method that accepts `Project $project`.

Affected files and methods:

- `backend/app/Http/Controllers/PageController.php` — Add check to all 5 methods (index, store, show, update, destroy)
- `backend/app/Http/Controllers/ProductController.php` — Add check to all 4 methods (index, store, update, destroy)
- `backend/app/Http/Controllers/CmsCollectionController.php` — Add check to all 4 methods (index, store, update, destroy)
- `backend/app/Http/Controllers/CmsItemController.php` — Add check to all 4 methods (index, store, update, destroy) — note it takes `Collection $collection`, so verify via `$collection->project->user_id === auth()->id()`
- `backend/app/Http/Controllers/PageVersionController.php` — Add check to all 3 methods (index, store, restore)
- `backend/app/Http/Controllers/PageExportController.php` — Add check to download()
- `backend/app/Http/Controllers/FormSubmissionController.php` — Add check to index()
- `backend/app/Http/Controllers/AiController.php` — Add check to all 3 methods (generatePage, rewriteCopy, seo)

## New files

None.

## Verification goals

After implementation, ALL of these must be true:

- [ ] Every route that accepts a resource ID verifies the authenticated user owns the top-level project
- [ ] Failing the ownership check returns 403 (not 404 or 500)
- [ ] Ownership check and auth check are separate (auth via middleware, ownership via in-handler check)

## Manual verification (for the human)

- Create two users and two projects (one each)
- Try accessing user B's project endpoints while logged in as user A
- All such requests should return 403
