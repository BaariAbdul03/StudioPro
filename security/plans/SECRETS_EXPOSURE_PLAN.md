# SECRETS_EXPOSURE Fix Plan

## Changes

- `backend/.env.example` — Replace real Supabase project IDs with placeholders
- `.gitignore` (root) — Add `.env` and `.env.*` patterns

## New files

None.

## Verification goals

After implementation, ALL of these must be true:

- [ ] `git ls-files .env` returns nothing
- [ ] `grep -rn 'dvtzpeasiunzerlfnkan' backend/.env.example` returns nothing
- [ ] `.gitignore` contains `.env` pattern
- [ ] `git check-ignore some-test.env` confirms a test `.env` file is ignored

## Manual verification (for the human)

- Rotate the GEMINI_API_KEY and STITCH_API_KEY in the Supabase/Stitch dashboards since they have been exposed on this machine's `.env` file
- Ensure no backup copies of `.env` exist outside the git-ignored path
