# Glamhouse Deployment Checklist

## Before Deploy

- Point the web host for `book.clemtrix.com` at the Laravel `public` directory.
- Copy `.env.production.example` to `.env` on the server, then fill in `APP_KEY`, database, mail, and notification secrets.
- Confirm `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://book.clemtrix.com`, and `APP_FORCE_HTTPS=true`.
- Confirm `SESSION_DOMAIN=book.clemtrix.com` and `SESSION_SECURE_COOKIE=true`.
- If the host uses a reverse proxy or load balancer for HTTPS, set `TRUSTED_PROXIES` to the proxy IPs, or to `*` only when the proxy strips untrusted forwarded headers.
- Confirm `AUTH_ALLOW_REGISTRATION=false` unless customer accounts are intentionally open.
- Run `php artisan migrate --force`.
- Run `php artisan storage:link` if the public storage link is missing.
- Run `php artisan optimize:clear` after changing config, routes, or views.
- Run `npm ci` and `npm run build` for frontend assets.

## Admin Security

- Create or update the admin with `php artisan admin:setup`.
- Sign in to `/admin/security` and enable two-factor authentication.
- Store recovery codes somewhere secure.
- Review `/admin/audit-logs` after launch to confirm admin actions are being recorded.

## Marketing And SEO

- Add real social channels in `/admin/social-links`; leave inactive links unpublished.
- Confirm Business Settings include phone, email, location, opening hours, maps URL, and price range.
- Add current portfolio items and testimonials, then mark the best ones as featured.
- Check `/sitemap.xml`, `/robots.txt`, page titles, meta descriptions, and JSON-LD output.

## Backups

- Confirm `GLAMHOUSE_BACKUPS_ENABLED=true`.
- Confirm the server scheduler runs `php artisan schedule:run` every minute.
- Run `php artisan backup:run` once manually after deployment.
- Move the generated `storage/app/backups/glamhouse-backup-*.zip` archive to off-server storage.
- Confirm retention with `GLAMHOUSE_BACKUPS_RETENTION_DAYS`.

## Smoke Test

- Visit `/`, `/services`, `/portfolio`, `/faq`, `/contact`, and `/booking`.
- Submit a test booking and confirm the signed PDF link works.
- Record a test payment from admin and confirm the payment appears in reports.
- Upload one media asset and one portfolio image; verify WebP/thumbnail generation in admin.
