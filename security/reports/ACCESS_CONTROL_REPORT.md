# ACCESS_CONTROL Security Report

## Status: CRITICAL

## Architecture Note

Laravel's route model binding automatically resolves `Model $model` from URL parameters. This means nested resource controllers receive the `Project` object loaded directly from the database, without any ownership check. An authenticated user can access resources of **any other user's project** by guessing/spoofing the project ID.

## Findings

### 1. CRITICAL: No project ownership check in most nested controllers

**Affected controllers:**
- `PageController` — ALL methods (index, store, show, update, destroy)
- `ProductController` — ALL methods (index, store, update, destroy)
- `CmsCollectionController` — ALL methods (index, store, update, destroy)
- `CmsItemController` — ALL methods (index, store, update, destroy)
- `PageVersionController` — ALL methods (index, store, restore)
- `PageExportController` — download()
- `PageVersionController` — ALL methods
- `FormSubmissionController` — index()
- `AiController` — ALL methods (generatePage, rewriteCopy, seo)

All of these accept `Project $project` via route model binding but **never verify** `$project->user_id === auth()->id()`.

Example vulnerability in `PageController::index`:
```php
public function index(Project $project)
{
    return response()->json($project->pages); // No ownership check!
}
```

An attacker can: `GET /api/projects/2/pages` (where project 2 belongs to another user) and see all of their pages.

### 2. AssetController correctly checks ownership (PASS)

```php
// AssetController
abort_unless($project->user_id === auth()->id(), 403);
```
This is the ONLY nested controller that correctly verifies project ownership.

### 3. ProjectController correctly checks ownership on show/update/destroy (PASS)

```php
abort_unless($project->user_id === auth()->id(), 403);
```
But note: `index()` is safe because it uses `$request->user()->projects()` (auto-scoped).

### 4. Cross-resource ownership checks exist but don't verify top-level ownership

Some controllers verify **parent-child relationships** (e.g., `$page->project_id === $project->id`) but never verify the user owns the top-level `$project`. If the attacker passes a valid project+page pair from the same project, they can access it even if they don't own the project.

## What's at risk

An attacker with a valid account can:
- List all pages, products, CMS collections of ANY project in the system
- View page content, export pages, restore page versions
- Create new pages, products, CMS items in any project
- Modify pages, products, CMS items in any project
- Delete pages, products, CMS items from any project
- Generate AI content for any project
- View and create form submissions for any project

## What's already secure

- `ProjectController` correctly checks ownership on individual resource operations (show/update/destroy)
- `AssetController` correctly checks ownership on all operations
- All routes are behind `auth:sanctum` (unauthenticated users cannot hit these endpoints)
- Frontend never exposes API calls that would intentionally cross project boundaries

## Recommendations

1. **CRITICAL**: Add `abort_unless($project->user_id === auth()->id(), 403)` to EVERY controller method that accepts a `Project $project` parameter — this affects:
   - `PageController` (index, store, show, update, destroy)
   - `ProductController` (index, store, update, destroy)
   - `CmsCollectionController` (index, store, update, destroy)
   - `CmsItemController` (index, store, update, destroy)
   - `PageVersionController` (index, store, restore)
   - `PageExportController` (download)
   - `FormSubmissionController` (index)
   - `AiController` (generatePage, rewriteCopy, seo)

2. **MEDIUM**: Consider extracting this ownership check into a reusable trait or policy (e.g., `ProjectOwnershipTrait`) to avoid repetition
