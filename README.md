# Attqwa College

A responsive school website with home, about, contact, and student portal pages. The portal supports student-account activation and sign-in, payment history, and server-initiated Kora checkout with server-side transaction verification.

## Requirements

- PHP 8.1 or later with PDO MySQL and cURL enabled
- MySQL 8 or later
- A Kora merchant secret key to accept live or test payments

## Local setup

1. Create the database tables by importing `schema.sql` into MySQL.
2. Configure the PHP process environment:

   ```text
   DB_HOST=127.0.0.1
   DB_NAME=attqwa_college
   DB_USER=your_mysql_user
   DB_PASSWORD=your_mysql_password
   APP_BASE_URL=https://your-public-site.example
   KORA_SECRET_KEY=your_kora_secret_key
   ```

   Do not commit credentials or expose the Kora secret in browser code.
3. Add each enrolled student to the `students` table from a trusted school
   administration process. Students activate their account with the admission
   number and email already on their school record.
4. Run locally with `php -S 127.0.0.1:8000` and open
   `http://127.0.0.1:8000`.

The home, about, and contact pages and the student portal shell can be viewed
without configuring Kora. MySQL is required to submit contact forms, activate
student accounts, sign in, and use the portal. The payment page deliberately
fails closed when the Kora secret or the public HTTPS site URL is not set.

## Deploy to Vercel

The Vercel configuration uses the community PHP runtime and serves the existing
root-level PHP pages. The site requires a remote MySQL database; set `DB_HOST`,
`DB_NAME`, `DB_USER`, `DB_PASSWORD`, `APP_BASE_URL`, and `KORA_SECRET_KEY` in
the Vercel project settings. Set `APP_BASE_URL` to the deployed HTTPS URL.

Do not store credentials in Git. Vercel functions do not share persistent local
files between invocations, so verify student login and session behavior after
deployment; use a shared session store if PHP file sessions are not reliable
for your deployment.

## School customization

Update the school name, term-fee amounts, and currency in `config.php`. Replace
the sample phone, email, and address in `includes/footer.php` and `contact.php`. Home-page
photographs are served from Unsplash; replace their URLs in `index.php` and
`assets/css/style.css` with school-owned images when ready.

The portal uses `assets/js/site.js`. A matching typed TypeScript source is in
`assets/ts/site.ts`; if you change portal/menu behavior, keep both in sync. No
TypeScript compiler is needed to run the PHP site.
