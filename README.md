# Women in Development, Inc. — Website & CMS

A production-ready website and donor/volunteer management platform for **Women in Development, Inc.**, an
Indiana 501(c)(3) nonprofit ("Where Women Become Legends") empowering women and girls through employment
pathways, entrepreneurship, financial literacy, leadership development, mentorship, scholarships, and
humanitarian support.

Built on Laravel 12 with a Filament v3 admin panel, Livewire-driven public pages, and a pluggable payment
gateway architecture supporting both manual and API-based donation methods.

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+, MySQL/MariaDB
- **Frontend**: Blade, Tailwind CSS, Alpine.js, Livewire 3 + Volt
- **Admin**: Filament v3
- **Auth**: Laravel Breeze (Livewire stack) + custom two-factor authentication (Google2FA)
- **Authorization**: Spatie Laravel Permission
- **Media**: Spatie Media Library
- **Backups**: Spatie Laravel Backup
- **PDF**: barryvdh/laravel-dompdf (receipts, certificates, reports)
- **Exports**: Filament native exports (CSV/XLSX) + maatwebsite/excel
- **Payments**: Stripe, PayPal, Paystack, Flutterwave (fully wired) — see [Payment Methods](#payment-methods)
- **Activity logging**: Spatie Laravel Activitylog

## Local Installation

### Prerequisites

- PHP 8.2+ with the `intl`, `mbstring`, `curl`, `gd`, `fileinfo`, and `mysqli` extensions enabled
- Composer 2.x
- Node.js 18+ and npm
- MySQL/MariaDB 10.4+ (or any Laravel-supported database)

### Steps

```bash
git clone <repository-url> wid-website
cd wid-website

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), then create the
database and run migrations with seed data:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build   # or `npm run dev` for local development with hot reload
php artisan serve
```

The site is now available at `http://localhost:8000`, and the admin panel at `http://localhost:8000/admin`.

### Default Admin Credentials (development only)

```
Email:    admin@womenindevelopmentempire.org
Password: password
```

**Change this password immediately in any non-local environment.** The seeder also creates several demo
donor/volunteer user accounts (see `database/seeders/DemoActivitySeeder.php`) all sharing the same dev
password — these are for demoing the UI only and should not exist in production.

## Environment Configuration Reference

| Variable | Purpose |
|---|---|
| `APP_NAME` | Site name shown in emails, backups, and page titles |
| `APP_URL` | Base URL used to generate absolute links (set to your local dev URL) |
| `APP_PRODUCTION_URL` | Reference value for the live domain (womenindevelopmentempire.org) — informational, used when configuring hosting |
| `DB_*` | MySQL connection — the app is configured for MySQL, not SQLite |
| `MAIL_*` | Required for donation receipts, contact form notifications, and password resets to actually deliver in production |
| `QUEUE_CONNECTION` | Defaults to `database`; a queue worker must run in production (see below) — receipts, backups, and other jobs are queued |

**Payment gateway credentials are not set via `.env`.** Each gateway (Stripe, PayPal, Paystack, Flutterwave)
is configured per `PaymentMethod` record in **Admin → Donations → Payment Methods**, where the API keys are
stored **encrypted at rest** in the database. This lets an admin add, rotate, or disable payment methods
without a deploy. See each driver's docblock in `app/Payments/Drivers/` for the exact config keys it expects
(e.g. Stripe reads `secret_key`; PayPal reads `client_id` + `secret` + `sandbox`).

## Payment Methods

The donation system is built around a `PaymentGatewayDriver` contract (`app/Payments/PaymentGatewayDriver.php`)
so payment methods split into two kinds:

- **Manual methods** (Bank Transfer, CashApp, Zelle, Mobile Money, Crypto Wallet, or any custom method an
  admin adds) need **zero code** — an admin creates a `PaymentMethod` record with instructions text and a
  logo, and it's immediately usable on the donation form.
- **Gateway methods** (Stripe, PayPal, Paystack, Flutterwave) are backed by a driver class implementing
  `initiate()` (start checkout, return a redirect URL) and `verify()` (confirm payment completed). All four
  are fully implemented against their real APIs. Square and Authorize.net ship as structural stubs, since
  both require client-side card tokenization (Web Payments SDK / Accept.js) beyond a redirect flow — adding
  a new *gateway* (as opposed to a new *manual* method) does require writing a driver class, one time.

Stripe also has a webhook endpoint at `POST /webhooks/stripe` (CSRF-exempt) for robust server-to-server
donation confirmation independent of the donor's browser redirect.

## Roles & Permissions

Nine roles are seeded by `database/seeders/RolePermissionSeeder.php`: Super Admin, Admin, Editor, Content
Manager, Finance Manager, Volunteer Manager, Project Manager, Donor Manager, and Moderator — each scoped to
permissions across content, donations, payment methods, campaigns, volunteers, projects, donors, moderation,
users, settings, and SEO. Super Admin bypasses all permission checks via a `Gate::before` hook in
`AppServiceProvider`.

## Two-Factor Authentication

Users can enable TOTP-based 2FA from **Profile → Two-Factor Authentication** (QR code setup + 8 recovery
codes). When enabled, password authentication alone does **not** establish a session — the login flow
redirects to a challenge page requiring the authenticator code or a recovery code before `Auth::login()` is
called, matching the security posture of Laravel Fortify's implementation.

## Running Tests

```bash
php artisan test
```

The suite (52+ tests) covers: every admin resource page booting for an authenticated Super Admin, every
public page booting with both empty and seeded data, the full donation flow (manual + validation + anonymous
giving + campaign progress math + receipt access control) via Livewire testing, volunteer/membership
authenticated flows, and two-factor authentication (enable/confirm/disable/recovery codes/login challenge).

Tests run against an in-memory SQLite database (`phpunit.xml`), isolated from your local MySQL dev database.

## Production Deployment

This section covers a VPS-style deployment (root SSH, a process manager, Node available). Deploying to
**Namecheap shared hosting (cPanel)** specifically — no persistent worker process, no Node on the server —
has its own guide: [`docs/DEPLOY-NAMECHEAP.md`](docs/DEPLOY-NAMECHEAP.md).

### Server Requirements

- PHP 8.2+ with the same extensions as local development, plus `opcache` enabled
- MySQL/MariaDB
- A process manager for the queue worker (Supervisor, systemd, or your host's equivalent)
- Cron access for Laravel's scheduler

### Deployment Steps

```bash
composer install --optimize-autoloader --no-dev
npm ci && npm run build

php artisan migrate --force
php artisan storage:link

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Do **not** run `php artisan db:seed` in production — the seeders create demo content and a well-known
default admin password.

### Queue Worker

Donation receipts, backups, and other background jobs run through the `database` queue. Run a persistent
worker (Supervisor config example):

```ini
[program:wid-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/wid-website/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=2
```

### Scheduler

Add this single cron entry — Laravel's scheduler (configured in `routes/console.php`) handles the rest,
including the daily sitemap regeneration and the daily backup run/clean/monitor cycle:

```
* * * * * cd /path/to/wid-website && php artisan schedule:run >> /dev/null 2>&1
```

### Backups

`spatie/laravel-backup` is configured out of the box (reads `APP_NAME` from `.env`) and scheduled to run
daily at 01:30, with cleanup at 01:00 and a monitor check at 02:00. Review `config/backup.php` to point the
backup destination at your production disk (S3, etc.) before going live — the default destination is local
disk, which is not durable on most hosts.

### HTTPS & Environment

Set `APP_ENV=production`, `APP_DEBUG=false`, and `APP_URL` to `https://womenindevelopmentempire.org` in your
production `.env`. Configure `SESSION_SECURE_COOKIE=true` once HTTPS is confirmed working end-to-end.

## Architecture Notes

- **Payment Methods** (`app/Payments/`) — see [Payment Methods](#payment-methods) above.
- **Public layout** (`resources/views/layouts/public.blade.php`) carries brand colors (deep purple `#5B2C83`,
  gold `#D4AF37`), dark mode (Alpine + localStorage), OpenGraph/Schema.org SEO tags, cookie consent, and the
  newsletter signup form used across every public page.
- **Admin panel** (`app/Filament/`) is organized into navigation groups (Content, Programs & Projects,
  Donations, Volunteers & Members, Blog & News, Media, Engagement, Site Settings) with dashboard widgets for
  donation stats, a 12-month donations chart, recent donations, and campaign performance.
- **Activity log** — Donation (status/amount changes), User (role/profile changes), and PaymentMethod
  (credential/config changes) are audited via `spatie/laravel-activitylog`, viewable read-only in
  **Admin → Activity Log**.

## License

Proprietary — Women in Development, Inc. All rights reserved.
