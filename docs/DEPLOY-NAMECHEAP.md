# Deploying to Namecheap Shared Hosting (cPanel)

This is the Namecheap-specific counterpart to the general deployment guide in the main `README.md`.
Shared hosting has two constraints that change the approach from a VPS:

1. **No persistent background process** — you can't run `php artisan queue:work` as a long-lived daemon
   (no Supervisor/systemd on shared plans). We use a cron-based worker instead.
2. **No Node.js build tooling on most shared plans** — frontend assets (`public/build/`) are built
   **locally**, then uploaded to the server rather than built in place.

You said you have SSH/Terminal access (Stellar Plus or Business plan), so this guide uses that. Everything
here runs from **your own terminal session** — nothing here requires giving anyone your cPanel password.

## 1. One-time server setup (cPanel UI)

1. **PHP version** — cPanel → *MultiPHP Manager* → select the domain → set to **PHP 8.2** (or newer).
2. **PHP extensions** — cPanel → *Select PHP Version* → *Extensions* tab → ensure `intl`, `mbstring`,
   `curl`, `gd`, `fileinfo`, `mysqli`, `pdo_mysql`, `openssl`, `zip`, `bcmath` are all checked.
3. **MySQL database** — cPanel → *MySQL® Databases* → create a database (e.g. `yourcpaneluser_wid`), a
   database user with a strong password, and add that user to the database with **All Privileges**.
4. **Domain document root** — confirm which directory `womenindevelopmentempire.org` points to (cPanel →
   *Domains*). Laravel's public entry point (`public/index.php`) must be the thing the web server serves
   from that document root — see step 5 below for the two common ways to arrange that.

## 2. Clone the repo over SSH

```bash
ssh yourcpaneluser@your-server-hostname   # from Namecheap's welcome email or cPanel → SSH Access
cd ~
git clone https://github.com/<your-org>/wid-website.git wid-app
cd wid-app
```

## 3. Point the document root at `public/`

Shared hosting usually serves straight from `public_html/`, but Laravel's entry point is `public/`. Two
options, in order of preference:

**Option A — subdomain/addon domain document root (cleanest):** In cPanel → *Domains*, edit
`womenindevelopmentempire.org`'s document root to point directly at `~/wid-app/public`. This is the
correct long-term setup if your plan allows changing it.

**Option B — symlink trick (if the document root can't be changed):**
```bash
# Move everything except public/ above the web root, then symlink public/ into public_html
mv ~/wid-app ~/wid-app-source
mv ~/wid-app-source/public ~/public_html_new
ln -s ~/wid-app-source ~/public_html_new/app
```
Then edit `~/public_html_new/index.php` so its `require` paths point at `__DIR__.'/app/...'` instead of
`__DIR__.'/../...'`. This is fiddly — Option A is strongly preferred if at all possible.

## 4. Install dependencies and configure

```bash
cd ~/wid-app   # (or wherever step 3 left your app root)

composer install --optimize-autoloader --no-dev

cp .env.example .env
php artisan key:generate
```

Edit `.env` (via `nano .env` or cPanel's File Manager) and set:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://womenindevelopmentempire.org
APP_PRODUCTION_URL=https://womenindevelopmentempire.org

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=yourcpaneluser_wid
DB_USERNAME=yourcpaneluser_wid
DB_PASSWORD=<the password you set in cPanel MySQL Databases>

MAIL_MAILER=smtp
MAIL_HOST=mail.womenindevelopmentempire.org   # or your mail provider's SMTP host
MAIL_PORT=587
MAIL_USERNAME=hello@womenindevelopmentempire.org
MAIL_PASSWORD=<mailbox password, create via cPanel → Email Accounts>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@womenindevelopmentempire.org
MAIL_FROM_NAME="Women in Development"

QUEUE_CONNECTION=database
```

Then run migrations (no `--seed` in production — that creates demo content and a well-known password):

```bash
php artisan migrate --force
php artisan storage:link

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Create your real admin account instead of using the seeded dev one:

```bash
php artisan tinker
>>> $u = App\Models\User::create(['name' => 'Your Name', 'email' => 'you@womenindevelopmentempire.org', 'password' => bcrypt('a-strong-password')]);
>>> $u->assignRole('Super Admin');
>>> exit
```

(This requires roles to exist first — run `php artisan db:seed --class=RolePermissionSeeder --force` once,
which only creates roles/permissions, no demo content or dev accounts.)

## 5. Upload the built frontend assets

Node isn't available on the server, so build locally and upload:

```bash
# On your local machine, from the project root:
npm run build

# Then upload public/build/ to the server. From your local machine:
scp -r public/build yourcpaneluser@your-server-hostname:~/wid-app/public/build
```

Repeat this `npm run build` + `scp` step after every future change that touches CSS/JS. If your Namecheap
plan happens to include cPanel's *Setup Node.js App* feature, you can instead run `npm install && npm run
build` directly over SSH inside that Node environment — check cPanel for a "Node.js" icon to know if you
have it.

## 6. Queue worker via cron (instead of a daemon)

cPanel → *Cron Jobs* → add a job that runs every minute:

```
* * * * * cd /home/yourcpaneluser/wid-app && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

`--stop-when-empty` makes each run process whatever's queued (donation receipt emails, etc.) and exit,
rather than trying to stay resident — which fits a cron-based model instead of a persistent worker.

## 7. Scheduler via cron

Add a second cron job (this drives the daily sitemap regeneration and backup run/clean/monitor cycle
already configured in `routes/console.php`):

```
* * * * * cd /home/yourcpaneluser/wid-app && php artisan schedule:run >> /dev/null 2>&1
```

## 8. SSL

cPanel → *SSL/TLS Status* → run AutoSSL for the domain (Namecheap shared hosting includes free AutoSSL
certificates). Once issued, add to `.env`:

```env
SESSION_SECURE_COOKIE=true
```

## 9. Backups destination

`config/backup.php` defaults to storing backups on local disk, which isn't durable if the shared hosting
account itself is ever compromised or lost. Since shared hosting won't have credentials for S3 configured
by default, either point `config/backup.php` at an external destination (S3-compatible storage — Namecheap
doesn't provide S3, but Backblaze B2 or AWS S3 both work with the same Flysystem driver already installed),
or at minimum periodically download the `storage/app/backups` directory somewhere off-server.

## Redeploying after future changes

```bash
# Local: build assets
npm run build

# Server (SSH):
cd ~/wid-app
git pull
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache

# Local: re-upload built assets
scp -r public/build yourcpaneluser@your-server-hostname:~/wid-app/public/build
```
