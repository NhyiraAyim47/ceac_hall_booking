# CEAC Hall Booking – Laravel version

The CEAC Hall Booking System (Christ Embassy Airport City), converted from plain PHP to **Laravel 12**.
Every public page, admin page, form and email from the original project is here, and the HTML, CSS and
images are the same, so the site looks exactly as before.

---------------------------------------------------------------------

## 1. What you need

| Requirement | Notes |
|---|---|
| PHP **8.2 or newer** | with the usual extensions: mbstring, openssl, pdo_mysql, fileinfo, ctype, json, tokenizer, xml |
| **Composer** | https://getcomposer.org (installs Laravel into a `vendor/` folder) |
| MySQL / MariaDB | XAMPP, Laragon, or your hosting database |

> The `vendor/` folder is not in the zip (it is large and Composer creates it). That is normal for Laravel.

---------------------------------------------------------------------

## 2. Run it on your computer

Open a terminal in the project folder, then:

```bash
# 1. Install Laravel and the packages
composer install

# 2. Create your settings file and the app key
cp .env.example .env            # Windows: copy .env.example .env
php artisan key:generate

# 3. Create an empty database (e.g. in phpMyAdmin) called  ceac_booking
#    then open .env and set DB_DATABASE / DB_USERNAME / DB_PASSWORD

# 4. Create the tables and the starter hall
php artisan migrate
php artisan db:seed

# 5. Create your first admin (you choose the password)
php artisan ceac:make-admin

# 6. Start the site
php artisan serve
```

Open **http://localhost:8000** (website) and **http://localhost:8000/admin/login** (admin).

### Using your EXISTING database (the one from InfinityFree) instead
1. In phpMyAdmin, export your live database (structure **and** data) and import it into your local MySQL.
2. Put its name in `.env` (`DB_DATABASE=...`).
3. Run `php artisan migrate`. The migrations see the tables already exist and skip them, so your
   data is untouched. Your old admin logins keep working (same bcrypt password hashes).
4. Skip `db:seed` and `ceac:make-admin`.

---------------------------------------------------------------------

## 3. Email (booking confirmations, approvals, replies)

In `.env`:

```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_SCHEME=smtp
MAIL_USERNAME=hallbookingceac@gmail.com
MAIL_PASSWORD=<your NEW Gmail app password>
```

* Create a **new** app password in your Google account (Security → 2-Step Verification → App passwords).
* The old Gmail app password and database password were written inside the old PHP files. **Delete the
  old app password in Google and change the old database password** – they should be considered exposed.
* If an email fails to send, the booking/approval is still saved and the error is written to
  `storage/logs/laravel-*.log`.

---------------------------------------------------------------------

## 4. Old project → new project

| Old file | New route (URL) | Code |
|---|---|---|
| `index.php` | `/` | `HomeController`, `views/home` |
| `pages/halls.php` | `/halls` (`?id=` for one hall) | `HallController`, `views/pages/halls` |
| `pages/events.php` | `/events` | `EventController` |
| `pages/about.php`, `contact.php`, `policy.php` | `/about`, `/contact`, `/policy` | `PageController` |
| `pages/404.php` | automatic 404 | `views/errors/404` |
| `includes/header.php` + `footer.php` | – | `views/layouts/app.blade.php` |
| `includes/process_booking.php` | `POST /booking/submit` | `BookingController@submit` |
| `includes/check_availability.php` | `GET /api/check-availability` | `BookingController@availability` |
| `includes/process_contact.php` | `POST /contact/submit` | `ContactController` |
| `includes/get_stats.php` | `GET /api/stats` | `ApiController@stats` |
| `includes/save_subscription.php` | `POST /api/push-subscription` | `ApiController@pushSubscription` |
| `sitemap.php` | `/sitemap.xml` | `SitemapController` |
| `admin/login.php`, `logout.php` | `/admin/login`, `/admin/logout` | `Admin\AuthController` |
| `admin/index.php` | `/admin/index` | `Admin\DashboardController` |
| `admin/bookings.php` | `/admin/bookings` | `Admin\BookingController` |
| `admin/booking_detail.php` | `/admin/booking_detail?id=` | `Admin\BookingDetailController` |
| `admin/booking_edit.php` | `/admin/booking_edit?id=` | `Admin\BookingEditController` |
| `admin/booking_action.php` | `/admin/booking_action?...` | `Admin\BookingActionController` |
| `admin/payment_action.php`, `payment_logs.php` | `/admin/payment_action`, `/admin/payment_logs` | `Admin\PaymentController` |
| `admin/events.php` | `/admin/events` | `Admin\EventController` |
| `admin/halls.php` | `/admin/halls` | `Admin\HallController` |
| `admin/messages.php` | `/admin/messages` | `Admin\MessageController` |
| `admin/users.php` | `/admin/users` | `Admin\UserController` |
| `admin/logs.php`, `settings.php` | `/admin/logs`, `/admin/settings` | `Admin\LogController`, `Admin\SettingController` |
| `includes/db.php` (DB, Security, Auth) | – | `App\Support\Sql`, `Security`, `app/helpers.php`, Laravel auth |
| `includes/config.php` | – | `config/ceac.php` + `.env` |
| PHPMailer | – | Laravel Mail (`App\Support\Mailer`, `views/emails/*`) |

Old links such as `/pages/halls.php?id=2` redirect automatically to `/halls?id=2`.

Where things live: `routes/web.php` (all URLs), `app/Http/Controllers` (logic),
`resources/views` (pages – these are your old pages as Blade), `database/migrations` (tables),
`public/assets` (CSS, JS, images – unchanged apart from the URLs of the AJAX calls).

---------------------------------------------------------------------

## 5. Security notes

Kept from the old system: hashed passwords, 5-attempt lockout (3 min), IP blocking, rate limiting,
CSRF tokens on every form, prepared SQL statements, escaped output, image-type checking on uploads,
activity log, security headers.

Improved while converting:
* Passwords/keys now live only in `.env` (never in code, never in the web folder).
* Sessions come from Laravel; the session ID is regenerated at login (prevents session fixation).
* Approve / reject / cancel / complete and all delete links now **require a CSRF token** (before, the
  approve/reject links had none).
* The **viewer** role is now read-only (before it could approve or reject).
* The booking rate-limit message now really stops the request (the old code forgot to `exit`).
* Booking submission locks the hall row, so two people booking the same slot at the same instant can
  no longer both succeed.
* Admin user roles are checked against the 3 allowed values; the login `redirect=` can only point to this site.
* Approval/edit conflict checks ignore soft-deleted bookings.

---------------------------------------------------------------------

## 6. Putting it online (Hostinger shared hosting with SSH)

1. Create a MySQL database in hPanel and note its name, user and password.
2. Upload the project (without `vendor/`) to a folder **above** `public_html`, e.g. `/home/USER/ceac`.
3. Copy everything inside `ceac/public/` into `public_html/` (including `.htaccess`).
4. In `public_html/index.php` change the two paths:
   `__DIR__.'/../vendor/autoload.php'` → `__DIR__.'/../ceac/vendor/autoload.php'` and
   `__DIR__.'/../bootstrap/app.php'` → `__DIR__.'/../ceac/bootstrap/app.php'`
   (and the maintenance-file line the same way).
5. Over SSH, inside `/home/USER/ceac`: `composer install --no-dev --optimize-autoloader`, create `.env`
   with `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-domain`, `SESSION_SECURE_COOKIE=true`,
   your database and mail settings, then `php artisan key:generate`, `php artisan migrate --force`,
   `php artisan ceac:make-admin`.
6. Make `storage/` and `bootstrap/cache/` writable (permissions 775) and `public_html/assets/images/halls/` writable.
7. Test: homepage, a booking, the admin login, an approval email.

On a VPS / Railway / Render set the web root to the `public/` folder instead of step 3–4.

---------------------------------------------------------------------

## 7. Test checklist (do this once after installing)

1. Home, Halls (and a single hall), Events, About, Contact, Policy open and look like the old site.
2. Open the booking pop-up, pick a hall, date and time → the availability message appears.
3. Submit a booking → you get a reference number and a confirmation email.
4. Submit a second booking for the same hall and overlapping time → it is refused.
5. Log in to `/admin/login`, open Bookings → approve the booking → the booker gets the approval email.
6. Reject another with a reason; cancel / complete one.
7. Open a booking → record a payment → check Payments page totals.
8. Edit a booking; reply to a booker by email; add notes.
9. Events: add / edit / delete. Halls: add a hall with photos, edit, delete.
10. Messages: send from the Contact page, read in admin, reply, compose a new message, check the Sent tab.
11. Admin Users: add a user, change role, deactivate, reset password. Log in as a `viewer` and confirm it cannot change anything.
12. Settings: change profile and password. Activity Log shows your actions.

---------------------------------------------------------------------

## 8. Good to know

* `admin/hall_form_fields.php` in the old project was never used by any page, so it is not carried over.
* Admin page URLs keep the old names without `.php` (for example `/admin/booking_detail?id=5`), so every
  link and script works as before.
* The `Admin` pages that show money use the ₵ symbol exactly as before.
* If you later want to move more SQL into Eloquent models, the models in `app/Models` are ready.
