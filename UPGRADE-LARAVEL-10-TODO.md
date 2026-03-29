# Laravel 10 Upgrade & Code Review — Todo Checklist

This checklist covers upgrading to Laravel 10 and a full code review. Your `composer.json` already targets Laravel 10; use this to align code, config, and dependencies.

---

## Proceed — What Was Done

- **Composer**
  - Replaced `fzaninotto/faker` with `fakerphp/faker` ^1.23.
  - Removed `beyondcode/laravel-dump-server`.
  - Replaced `brozot/laravel-fcm` with `kreait/firebase-php` ^7.0 (FCM now via Firebase Admin SDK).
  - Updated `milon/barcode` to ^10.0 (Laravel 10).
  - Updated `tymon/jwt-auth` to ^2.0 (PHP 8.1 / Laravel 10).
  - Updated `pusher/pusher-php-server` to ^7.0 (psr/log 2/3).
- **Kernel**
  - Renamed `$routeMiddleware` → `$middlewareAliases`; enabled API `throttle:60,1`.
- **AppServiceProvider**
  - Replaced `Request()` with `request()`; registered Firebase `Messaging` when `FIREBASE_CREDENTIALS` is set.
- **FCM**
  - `FCMController` rewritten to use `Kreait\Firebase\Messaging`; same `Push($title, $content, $token, $data, $activity)` signature.
  - Added `config/services.php` → `firebase.credentials` and `.env.example` → `FIREBASE_CREDENTIALS` (path to service account JSON).
- **Config**
  - `config/auth.php`: comment cleanup.

**Your next steps**

1. Run **`composer update --with-all-dependencies`** (or `composer install` if lock file is committed) until it completes. If downloads fail (e.g. GitHub), retry or use `COMPOSER_AUTH` / VPN as needed.
2. Set **`FIREBASE_CREDENTIALS`** in `.env` to the absolute path of your Firebase service account JSON (e.g. `storage/app/firebase-credentials.json`). Generate it in Firebase Console → Project Settings → Service Accounts.
3. Run **`php artisan config:clear`** and test the app (web + API + push notifications).

---

## Part 1 — Environment & Prerequisites

- [ ] **PHP version**  
  Ensure PHP 8.1+ on all environments (Laravel 10 requirement).  
  - Run: `php -v`

- [ ] **Composer**  
  Update Composer: `composer self-update`

- [ ] **Backup**  
  - Full codebase backup (e.g. git tag or zip)  
  - Database dump  
  - Copy of `.env` and any env-specific config

- [ ] **Bootstrap folder**  
  Confirm `bootstrap/` exists with `app.php`, `cache/`, and that `public/index.php` loads the framework. If missing, restore from a fresh Laravel 10 install.

---

## Part 2 — Composer & Dependencies

- [ ] **Laravel 10 core**  
  - In `composer.json`: `"laravel/framework": "^10.0"` (already set)  
  - Run: `composer update laravel/framework --with-all-dependencies`

- [ ] **Replace abandoned Faker**  
  - Remove: `fzaninotto/faker` (abandoned)  
  - Add: `fakerphp/faker` (e.g. `^1.23`)  
  - Update `database/factories/UserFactory.php`: namespace is `Faker\Generator` (same for fakerphp/faker); ensure `use Faker\Generator as Faker;` and type-hint `Faker $faker`.  
  - Run: `composer require fakerphp/faker --dev` then remove `fzaninotto/faker`.

- [ ] **Optional: remove dump-server**  
  - `beyondcode/laravel-dump-server` is often unnecessary; Laravel uses Symfony’s dump. Remove if not needed: `composer remove beyondcode/laravel-dump-server`

- [ ] **Verify all packages support Laravel 10**  
  - `kreait/firebase-php` ^7.0 (replaces brozot/laravel-fcm)  
  - `laravelcollective/html` ^6.4  
  - `maatwebsite/excel` ^3.1  
  - `milon/barcode` ^10.0  
  - `naif/saudiaddress` ^2.1  
  - `niklasravnsborg/laravel-pdf` ^4.0  
  - `pusher/pusher-php-server` ^7.0  
  - `spatie/laravel-translatable` ^6.0  
  - `tymon/jwt-auth` ^2.0  
  - `fakerphp/faker` ^1.23  
  - `barryvdh/laravel-debugbar` ^3.4  
  - `nunomaduro/collision` ^7.0  
  - `phpunit/phpunit` ^10.5  

- [ ] **Lock and test**  
  Run: `composer update` (or minimal update), then `composer install` in CI/staging and run tests.

---

## Part 3 — Laravel 10 Config & Structure

- [ ] **`$routeMiddleware` → `$middlewareAliases`**  
  In `app/Http/Kernel.php`, rename property to `$middlewareAliases` (Laravel 10.12+). Keep the same array content.

- [ ] **TrustProxies**  
  In `app/Http/Middleware/TrustProxies.php`, ensure it extends Laravel’s `TrustProxies` and uses `$proxies` and `$headers` as per Laravel 10 docs (no deprecated props).

- [ ] **Maintenance mode**  
  Comment in Kernel references `CheckForMaintenanceMode`; Laravel 10 uses `PreventRequestsDuringMaintenance`. Replace if you re-enable maintenance middleware.

- [ ] **Config cache**  
  After config changes: `php artisan config:clear` then `php artisan config:cache` (in production).

---

## Part 4 — Database: Seeds & Factories

- [ ] **Seeds directory (optional but recommended)**  
  - Laravel 8+ uses `database/seeders/` and namespaced seed classes.  
  - You currently have `database/seeds` in `composer.json` classmap.  
  - Either: (A) Keep current setup and ensure classmap is correct, or (B) Move seeders to `database/seeders/`, namespace them `Database\Seeders`, update `composer.json` autoload (remove `database/seeds`, add `Database\\Seeders\\`: `database/seeders/`), and update `DatabaseSeeder` to call new class names.

- [ ] **Factories (optional)**  
  - You use the old `$factory->define()` style in `database/factories/UserFactory.php`.  
  - For Laravel 8+ style: convert to a class extending `Illuminate\Database\Eloquent\Factories\Factory` and use `model::newFactory()` in the model.  
  - At minimum: keep current factory but ensure it works with `fakerphp/faker` (namespace/import only).

- [ ] **Migrations**  
  - Run: `php artisan migrate:status`  
  - Fix any failing migrations (e.g. missing tables, duplicate columns).  
  - Do not change already-run migration file contents in production; use new migrations for fixes.

---

## Part 5 — Routes & Middleware

- [ ] **API throttle**  
  In `app/Http/Kernel.php`, `api` group has `'bindings'`; consider adding `'throttle:60,1'` (or your preferred rate limit) for API routes.

- [ ] **Route names and consistency**  
  - Ensure web and API routes use consistent naming (e.g. `route('admin.orders.index')`).  
  - Search for hardcoded URLs and replace with `route()` where appropriate.

- [ ] **Route caching (production)**  
  After route changes: `php artisan route:cache`. Clear with `php artisan route:clear` when developing.

---

## Part 6 — Application Code Review

- [ ] **Request() helper in boot**  
  In `AppServiceProvider::boot()` you use `Request()->get('notify')`. Prefer `request()->get('notify')` or `Illuminate\Support\Facades\Request::get('notify')` for consistency and testability.

- [ ] **Deprecated PHP/Laravel usage**  
  - Search for `create()` on dates (Carbon) and replace with `now()` or Carbon’s recommended API.  
  - Replace any `array_key_first`/`array_key_last` usage if on PHP < 7.3 (Laravel 10 requires 8.1+, so this is optional).  
  - Replace deprecated string/array helpers with `Str::` and `Arr::` facades where applicable.

- [ ] **Blade and views**  
  - No `{{ $var }}` with unescaped user content; use `@csrf` in forms.  
  - Remove any `{!! !!}` on user-controlled data.

- [ ] **SQL and mass assignment**  
  - No raw queries with concatenated user input; use query builder bindings or Eloquent.  
  - All fillable models have `$fillable` or `$guarded` set appropriately.

- [ ] **Auth and permissions**  
  - `auth` config: ensure guards (`web`, `admin`, `company`) and providers match your models and tables.  
  - Review `PermissionsMiddleware` and `Permission` usage; ensure every protected route has correct middleware and no privilege escalation.

- [ ] **API**  
  - JWT and `apilocale` middleware: confirm token validation and locale handling.  
  - API responses: use consistent JSON shape and status codes (e.g. 401/403 for auth).

- [ ] **File and helper autoload**  
  - `app/Http/helper.php` and `app/Http/GetMsgCode.php` are global files. Ensure they don’t conflict with framework or package names and that they’re required only when needed (e.g. not in console when not used).

---

## Part 7 — Security & Best Practices

- [ ] **.env**  
  - Never commit `.env`.  
  - Ensure `APP_DEBUG=false` and `APP_KEY` set in production.

- [ ] **CSRF**  
  - All state-changing web forms use `@csrf` and are under `web` middleware (VerifyCsrfToken).

- [ ] **Passwords**  
  - Stored with Hash (bcrypt/argon); no plain text.

- [ ] **Sensitive config**  
  - No API keys or secrets in config files committed to repo; use `.env` and `env()`.

- [ ] **Headers**  
  - Consider security headers (CSP, X-Frame-Options, etc.) via middleware or server config.

---

## Part 8 — Testing & Quality

- [ ] **PHPUnit**  
  - Run: `./vendor/bin/phpunit` or `php artisan test`.  
  - Fix failing tests and deprecation notices.

- [ ] **Static analysis (optional)**  
  - Run PHPStan or Larastan at level 2–5 and fix critical issues.

- [ ] **Manual smoke tests**  
  - Login (web admin, company, user).  
  - Critical flows: create order, invoice, driver, company actions.  
  - API: auth, locale, main endpoints.

---

## Part 9 — Performance & Deployment

- [ ] **Caching**  
  - Production: `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`.  
  - Use cache driver appropriate for production (redis/memcached if available).

- [ ] **Queue (if used)**  
  - Ensure queue workers run and failed jobs are monitored.  
  - Use `php artisan queue:restart` after deployment.

- [ ] **Logs and storage**  
  - `storage/logs` and `storage/framework` writable; log channel and level set in `.env`.

- [ ] **Scheduler**  
  - If using `schedule:run`, confirm cron is set up on the server.

---

## Part 10 — Documentation & Cleanup

- [ ] **README**  
  - Update README with PHP 8.1+, Laravel 10, and any new env vars or setup steps.

- [ ] **Remove dead code**  
  - Delete unused controllers, routes, views, and migrations (only if never run in production).

- [ ] **Comment cleanup**  
  - Remove or update outdated comments (e.g. “byst5dem l web” in auth config).

- [ ] **Changelog**  
  - Note Laravel 10 upgrade and breaking changes in CHANGELOG or release notes.

---

## Quick Command Summary

```bash
# Backup
php artisan down
# (dump DB, backup files)

# Dependencies
composer require fakerphp/faker --dev
composer remove fzaninotto/faker
composer update

# App
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# After changes
php artisan migrate:status
./vendor/bin/phpunit

# Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

---

## Notes

- Your app already declares `laravel/framework: ^10.0`; this list ensures the rest of the stack and code match Laravel 10 and good practices.
- If you were on Laravel 9 or earlier, run the official upgrade guide first (e.g. [Laravel 10 upgrade](https://laravel.com/docs/10.x/upgrade)), then use this file as a project-specific and code-review checklist.
- Tick items as you complete them and add project-specific tasks (e.g. “Saudi Address API compatibility”, “FCM token handling”) at the end of the relevant section.
