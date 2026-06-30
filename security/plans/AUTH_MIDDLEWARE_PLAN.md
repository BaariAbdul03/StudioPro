# AUTH_MIDDLEWARE Fix Plan

## Changes

- `backend/routes/api.php` — Move `/checkout/session` inside the `auth:sanctum` middleware group
- `backend/routes/web.php` — Add `auth:sanctum` middleware to checkout simulation routes
- `backend/app/Http/Controllers/CheckoutController.php` — Add ownership check for project_id on checkout/session

## New files

None.

## Verification goals

After implementation, ALL of these must be true:

- [ ] `POST /checkout/session` returns 401 when called without a valid token
- [ ] `POST /checkout/simulate/pay` returns 401 when called without a valid token
- [ ] `POST /checkout/simulate/cancel` returns 401 when called without a valid token
- [ ] Authenticated users can create checkout sessions for their own projects
- [ ] Checkout session creation verifies the user owns the project

## Manual verification (for the human)

- Test checkout flow end-to-end after the changes to ensure legitimate users can complete purchases
- Verify the simulate/cancel and simulate/pay behavior still works when authenticated
