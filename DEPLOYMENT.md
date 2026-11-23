# Deploy the full workspace

## Hostless Free

[Hostless](https://hostless.cloud/) documents a card-free, always-on free allowance. The repository's `hostless.yaml` sets one 256 MiB Docker replica with 0.1 vCPU and an HTTP health check at `/up`.

Connect the StudioPro GitHub repository and select the deployment branch. Use the repository root as the build context and `backend/Dockerfile` as the Dockerfile. Set `APP_URL` to the assigned HTTPS origin and add the production and Neon variables listed below as Hostless runtime variables.

Check `/up` and `/api/health` on the live URL, then verify workspace login, uploads, publishing, and ZIP export. Hostless builds and runs the container remotely. Docker Desktop is only needed for local testing.

## Northflank Sandbox

[Northflank Sandbox](https://northflank.com/pricing) provides two always-on services and two jobs on its free plan. One Docker service runs Vue, Laravel, authenticated APIs, exports, and published pages. Neon provides Postgres and object storage.

Northflank blocked CLI sign-in for this team pending payment-card verification. Its pricing page advertises card-free Sandbox access, while its [billing documentation](https://northflank.com/docs/v1/application/billing/pricing-on-northflank) says a payment method is required to create resources.

## Workspace service

1. Push the reviewed branch to GitHub. Create a [combined service](https://northflank.com/docs/v1/application/getting-started/build-and-deploy-your-code) from that branch. Set the Dockerfile to `backend/Dockerfile` and use the repository root as the build context.
2. Add public HTTP port `80` and use the generated HTTPS hostname. In runtime variables, set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` to that HTTPS origin, `DB_CONNECTION=pgsql`, `SESSION_DRIVER=database`, `SESSION_SECURE_COOKIE=true`, and `CACHE_STORE=database`. Supply a stable `APP_KEY`. Leave `STUDIO_PUBLIC_URL` unset to serve share links on the same origin.
3. Set the pooled Neon URL as `DATABASE_URL`. Set `NEON_STORAGE_ENDPOINT`, `NEON_STORAGE_ACCESS_KEY_ID`, `NEON_STORAGE_SECRET_ACCESS_KEY`, and `NEON_STORAGE_REGION` in runtime secrets. The image defaults to the `studio-media` and `studio-published` Neon buckets.
4. Configure `GET /up` as the recurring health check so an idle Neon compute can suspend. Run `sh /usr/local/bin/studio-migrate` as a one-off job with `DATABASE_URL_UNPOOLED` if the branch needs migrations. Run `php artisan studio:provision owner@example.test` in the service shell and enter the owner password interactively.
5. Open `/welcome` for the portfolio, `/demo` for the browser demo, and `/login` for the private workspace. Verify `/api/health` once. Sign in, apply a template, download a ZIP, upload an image, publish, and verify the share link while signed out. Unpublish and verify that it returns HTTP 404.

One service covers the editor, templates, CMS, inventory, version history, uploads, publishing, and export. Checkout remains a local simulation. Live payments need a production integration.

## Optional AI

Set `STUDIO_AI_ENABLED=true` and `GEMINI_API_KEY` on the workspace service for page generation, copy rewrite, and SEO suggestions.

For Stitch, set `STUDIO_STITCH_ENABLED=true`, `STITCH_PROJECT_ID=1798865299197471638`, and `STITCH_API_KEY` with a rotated key in runtime secrets. Laravel starts a [background process](https://laravel.com/docs/13.x/queues#deferred-dispatching) for each generation request. This lets the Neon compute suspend between uses.

Stitch creates screens in the configured project. The editor imports HTML and inline styles when Google makes the download available and links back to the Stitch project in either case. Import strips scripts and external stylesheets, so designs that depend on those may need styling adjustments.

## Existing Cloudflare portfolio

[Cloudflare Pages](https://studiopro-pagebuilder.pages.dev/) serves the public portfolio and existing share links. Keep it running during the full workspace migration.

```sh
npm ci --prefix frontend
npm run build:portfolio
cd frontend
npx --yes wrangler@4.166.0 pages deploy ./dist --project-name studiopro-pagebuilder --branch main
```

Run the deploy command from `frontend` so Wrangler includes the Pages Function. The Wrangler configuration points that Function to the public Neon bucket. Set `STUDIO_PORTFOLIO_URL` to the deployed origin to run the public browser suite against it.

## Prepare Neon

`neon.ts` declares `studio-media` as private and `studio-published` as public read. Apply changes with `neon config plan` and `neon deploy` from the linked Neon workspace. Use [Neon's object storage setup](https://neon.com/docs/storage/quickstart) to obtain branch credentials.

Laravel reads `DATABASE_URL` for pooled traffic and `DATABASE_URL_UNPOOLED` for migrations. Existing `DB_URL` and `DB_DIRECT_URL` overrides take priority. Use a restricted Postgres role for runtime traffic and a migration role for schema changes.
