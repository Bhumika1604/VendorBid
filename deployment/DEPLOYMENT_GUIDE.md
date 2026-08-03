# VendorBid — Hostinger Deployment Guide

This guide walks through deploying VendorBid (a CodeIgniter 4 application)
to a Hostinger shared hosting account (Business/Premium plan or higher,
with SSH access enabled). The same steps apply to most cPanel/hPanel-style
shared hosts with minor path adjustments.

---

## 1. Prerequisites

- A Hostinger hosting plan with PHP 8.1+ enabled
- A MySQL database created in hPanel
- SSH access enabled (hPanel → Advanced → SSH Access) — recommended but not
  strictly required (an FTP-only path is included at the end)
- Composer installed locally (to build the `vendor/` folder before upload,
  since Hostinger shared hosting usually does not allow `composer install`
  directly on the server)

---

## 2. Prepare the build locally

CodeIgniter 4's public entry point is `public/index.php`, but the `app/`,
`system/` (via Composer) and `writable/` folders must sit **outside** the
public web root for security. Hostinger's web root is `public_html/`, so
we restructure the upload like this:

```
public_html/            ← contents of VendorBid/public/ go here
vendorbid_app/           ← everything else (app, vendor, writable, .env, etc.)
    app/
    vendor/
    writable/
    .env
    composer.json
    ...
```

Steps:

1. On your local machine, install dependencies (including the optional
   PDF/Excel export packages used by the Reports module):

   ```bash
   composer install --no-dev --optimize-autoloader
   composer require dompdf/dompdf
   composer require phpoffice/phpspreadsheet
   ```

2. Edit `public/index.php` so it points `FCPATH`'s sibling paths at the
   renamed `vendorbid_app` folder instead of the default `../app`. In a
   standard CI4 install this is handled automatically by `app/Config/Paths.php`,
   so instead of editing `index.php`, simply **upload the whole project
   folder as `vendorbid_app`** one level above `public_html`, and copy the
   *contents* of `vendorbid_app/public/` into `public_html/` (keep a copy of
   `index.php` in both places — see step 5).

3. Zip the project locally for a faster upload:

   ```bash
   zip -r vendorbid-deploy.zip . -x "writable/cache/*" "writable/logs/*" "writable/session/*"
   ```

---

## 3. Create the database in hPanel

1. hPanel → **Databases → MySQL Databases**
2. Create a new database (e.g. `u123456789_vendorbid`)
3. Create a new database user and attach it to the database with **All
   Privileges**
4. Note the hostname (usually `localhost`), database name, username and
   password — you'll need these in your `.env` file

---

## 4. Import the database

### Option A — phpMyAdmin (easiest)

1. hPanel → **Databases → phpMyAdmin** → select your new database
2. Go to the **Import** tab
3. **Simplest path:** import just `sql/vendorbid_full.sql` — it creates the
   complete, final schema in one file (equivalent to all 4 parts merged).
   Optionally follow it with `sql/vendorbid_sample_data.sql` for realistic
   demo content.
4. **Alternative:** import the four incremental files **in this exact
   order** instead:
   1. `sql/vendorbid.sql`
   2. `sql/vendorbid_update_part2.sql`
   3. `sql/vendorbid_update_part3.sql`
   4. `sql/vendorbid_update_part4.sql`

### Option B — SSH + mysql CLI

```bash
mysql -u u123456789_vbuser -p u123456789_vendorbid < sql/vendorbid_full.sql
# Optional demo data:
mysql -u u123456789_vbuser -p u123456789_vendorbid < sql/vendorbid_sample_data.sql
```

---

## 5. Upload the files

### Via File Manager / SFTP

1. Upload the entire project (everything **except** `public/`'s contents
   directly) to a folder one level above `public_html`, e.g.:
   `/home/u123456789/vendorbid_app/`
2. Upload the **contents** of `public/` (not the folder itself) into
   `public_html/`
3. Open the `index.php` you just placed in `public_html/` and update the
   two `require` paths so they point at `../vendorbid_app/app/Config/Paths.php`
   and the system bootstrap accordingly (this mirrors the relative-path
   adjustment CI4's own deployment docs describe for the "public folder
   moved" scenario).
4. Copy `deployment/env.production.example` into `vendorbid_app/.env` and
   fill in your real database/email credentials (see Section 6).

### Via SSH (recommended)

```bash
cd /home/u123456789
mkdir vendorbid_app
cd vendorbid_app
# upload vendorbid-deploy.zip here via SFTP, then:
unzip vendorbid-deploy.zip
mv public/* ../public_html/
cp deployment/env.production.example .env
nano .env   # fill in real values
```

---

## 6. Configure `.env`

Use `deployment/env.production.example` as your starting point. At minimum,
update:

- `app.baseURL` → your real domain
- `database.default.*` → the credentials from Section 3
- `encryption.key` → generate a fresh one:
  ```bash
  php spark key:generate
  ```
- `email.*` → your Hostinger Titan Mail (or other SMTP) credentials, used
  by the Bid Submitted / Award / Rejection email notifications

---

## 7. Folder permissions

CodeIgniter needs the `writable/` directory (and its subfolders) to be
writable by the web server user, since it holds cache, logs, sessions and
uploaded bid documents.

```bash
cd /home/u123456789/vendorbid_app
chmod -R 755 writable
chmod -R 755 writable/uploads
chmod -R 755 writable/uploads/bids
```

If Hostinger's PHP-FPM user still can't write (uploads fail, or you see
"Unable to write to logs"), try the more permissive (but still safe on
shared hosting) `775`:

```bash
chmod -R 775 writable
```

Never set `777` in production — `755`/`775` is sufficient because the
PHP-FPM process runs as your own hosting account's user on Hostinger.

---

## 8. Point the domain / verify

1. hPanel → **Websites** → confirm the domain's document root is
   `public_html`
2. Visit `https://yourdomain.com/admin/login` and confirm the login page
   renders with styling (confirms `public/assets` uploaded correctly)
3. Log in with the seeded admin account (see the main `README.md` for
   default credentials) and confirm the dashboard loads
4. Submit a test bid as a contractor and confirm the "Bid Submitted" email
   arrives (confirms SMTP settings are correct)

---

## 9. Post-deployment checklist

- [ ] `.env` → `CI_ENVIRONMENT = production` (never `development` in prod)
- [ ] `app.forceGlobalSecureRequests = true` once your SSL certificate
      (free via hPanel → SSL) is active
- [ ] `writable/` permissions set (Section 7)
- [ ] Default admin password changed from the seeded `Admin@123`
- [ ] SMTP test email sent successfully
- [ ] `composer require dompdf/dompdf` and
      `composer require phpoffice/phpspreadsheet` completed so Reports →
      PDF/Excel export work (otherwise the export buttons show a friendly
      "package not installed" message instead of failing)
- [ ] Cron/Task Scheduler not required for this version — no scheduled
      jobs are used

