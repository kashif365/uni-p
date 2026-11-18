# uni-p

Campus Deals is a Laravel university project for browsing and searching sample student offers. The homepage uses the October 13 coupon layout. The demo includes six fictional stores and coupon codes.

## Local setup

Use PHP 8.2 or later with PDO SQLite, Composer, and Node.js. From a fresh checkout on Windows PowerShell, run:

```powershell
composer install
npm ci
npm run build
Copy-Item .env.example .env
New-Item -ItemType File database/database.sqlite
php artisan key:generate
php artisan migrate --seed
php artisan demo:admin
php artisan serve
```

Open http://localhost:8000. To use the admin dashboard, sign in at /login with the account you created. The local mailer writes outgoing messages to the application log.

Run the test suite with php artisan test.
