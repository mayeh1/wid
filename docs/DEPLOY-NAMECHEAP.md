# Deploying to Namecheap Shared Hosting (cPanel)

This is the Namecheap-specific counterpart to the general deployment guide in the main `README.md`.
Shared hosting has a few constraints that change the approach from a VPS:

1. **No persistent background process** — you can't run `php artisan queue:work` as a long-lived daemon
   (no Supervisor/systemd on shared plans). We use a cron-based worker instead.
2. **No Node.js build tooling on most shared plans** — frontend assets (`public/build/`) are built
   **locally**, then uploaded to the server rather than built in place.

This guide uses **cPanel's Git™ Version Control feature + its browser-based Terminal app** — everything
runs through your existing cPanel login session in the browser. No SSH keys, no separate terminal client.

## 1. One-time server setup (cPanel UI)

1. **PHP version** — cPanel → *MultiPHP Manager* → select the domain → set to **PHP 8.2** (or newer).
2. **PHP extensions** — cPanel → *Select PHP Version* → *Extensions* tab → ensure `intl`, `mbstring`,
   `curl`, `gd`, `fileinfo`, `mysqli`, `pdo_mysql`, `openssl`, `zip`, `bcmath` are all checked.
3. **MySQL database** — cPanel → *MySQL® Databases* → create a database (e.g. `yourcpaneluser_wid`), a
   database user with a strong password, and add that user to the database with **All Privileges**. Write
   the database name, username, and password down — you'll need them in step 4.

## 2. Clone the repo via cPanel's Git Version Control

1. cPanel → *Git™ Version Control* → **Create**.
2. **Clone URL**: `https://github.com/mayeh1/wid.git`
3. **Repository Path**: something *outside* `public_html`, e.g. `/home/yourcpaneluser/wid-app` — this
   matters because Laravel's actual web entry point is the `public/` subfolder, not the project root (see
   step 3).
4. **Repository Name**: whatever you like (e.g. `wid`).
5. Click **Create**. cPanel clones the repo for you — no SSH needed for a public repo like this one.

To pull future updates later, come back to this same *Git™ Version Control* page, open the repository, and
click **Pull or Deploy → Update from Remote**, then **Deploy HEAD Commit**.

## 3. Point the domain at `public/`

Shared hosting usually serves straight from `public_html/`, but Laravel's entry point is `public/`. In
cPanel → *Domains*, edit `womenindevelopmentempire.org`'s **Document Root** to point directly at:

```
/home/yourcpaneluser/wid-app/public
```

(matching whatever repository path you chose in step 2). This is the cleanest fix — if your plan doesn't
let you change the document root for the main domain, use a symlink instead: in cPanel's *File Manager*,
inside `public_html`, create a symlink named after nothing extra — i.e. delete the placeholder
`public_html` contents and symlink `public_html` itself to `wid-app/public` (File Manager doesn't do
symlinks directly; the Terminal app from step 4 can: `ln -s ~/wid-app/public ~/public_html`, after first
emptying `public_html`).

## 4. Open cPanel's Terminal and configure the app

cPanel → *Advanced* → **Terminal**. This opens a browser-based shell authenticated by your cPanel login —
no key setup required. Run:

```bash
cd ~/wid-app

composer install --optimize-autoloader --no-dev

cp .env.example .env
php artisan key:generate
```

Edit `.env` next — either `nano .env` right there in the Terminal, or cPanel's *File Manager* → navigate to
`wid-app` → enable "Show Hidden Files" → edit `.env` in the built-in code editor. Set:

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

Back in the Terminal, run migrations (no `--seed` in production — that creates demo content and a
well-known dev password):

```bash
php artisan migrate --force
php artisan storage:link

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Create your real admin account instead of using the seeded dev one — first seed just the roles (no demo
content), then create yourself as Super Admin:

```bash
php artisan db:seed --class=RolePermissionSeeder --force

php artisan tinker
>>> $u = App\Models\User::create(['name' => 'Your Name', 'email' => 'you@womenindevelopmentempire.org', 'password' => bcrypt('a-strong-password')]);
>>> $u->assignRole('Super Admin');
>>> exit
```

## 5. Upload the built frontend assets

Node isn't available on the server, so build locally and upload via File Manager:

```bash
# On your local machine, from the project root:
npm run build
```

Then in cPanel → *File Manager*, navigate to `wid-app/public`, and upload the resulting `build/` folder
(zip it locally first — `Compress-Archive public\build build.zip` on Windows PowerShell — then use File
Manager's **Upload**, followed by **Extract** once it's on the server; this is much faster than uploading
hundreds of individual files one by one).

Repeat this after every future change that touches CSS/JS.

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

1. **Local**: `npm run build`, then zip and upload `public/build/` via File Manager as in step 5.
2. **cPanel → Git™ Version Control**: open the repo → **Pull or Deploy** → **Update from Remote** →
   **Deploy HEAD Commit**.
3. **cPanel → Terminal**:
   ```bash
   cd ~/wid-app
   composer install --optimize-autoloader --no-dev
   php artisan migrate --force
   php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```

## If you get SSH access working later

Everything above also works over SSH if you ever want it — `git clone`/`git pull` instead of the cPanel Git
UI, and `scp` instead of File Manager uploads for `public/build/`. Nothing here is SSH-specific by
necessity; the browser-based path is just the lower-friction default.
