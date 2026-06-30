# DATABASE_ACCESS Fix Plan

## Changes

- `backend/.env.example` — Add `DB_SSLMODE=require` for production database encryption
- No code changes needed for Supabase Storage RLS (requires manual verification in Supabase dashboard)

## New files

None.

## Verification goals

After implementation, ALL of these must be true:

- [ ] `DB_SSLMODE=require` is present in `.env.example`
- [ ] No direct Supabase API data queries bypassing Laravel auth (only Storage API used)

## Manual verification (for the human)

- Log into Supabase dashboard → Authentication → Policies: Verify that every table has RLS enabled
- Log into Supabase dashboard → Storage → `studiopro-assets` bucket: Verify RLS policies are configured to restrict uploads/reads to authenticated users only
- Test: Try uploading a file using only the anon key (without any user token) — it should fail if RLS policies are correctly configured
