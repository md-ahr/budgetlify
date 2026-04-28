# Budgetlify

Personal finance workspace built with **Laravel** and **Tailwind CSS**. It provides dashboards, transactions, budgets, and analytics with a minimal fintech-style interface, light/dark theme, and per-user data backed by the database.

## Stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.3+, Laravel 13 |
| Frontend | Vite 8, Tailwind CSS 4, Chart.js |
| Tests | Pest 4 |

## Features

- **Dashboard** — stat cards from your transactions, expense overview charts (7d / 30d / 90d), recent activity
- **Transactions** — list with filters, create, edit, delete (scoped to the signed-in user)
- **Budgets** — budgets per category with limits; full CRUD
- **Analytics** — monthly trends, categories, savings, cash flow
- **Settings** — profile and email, regional formats (currency, date format), appearance (theme), **delete account** (removes the user and cascades related records)
- **Authentication** — register, login, logout, forgot / reset password (session guard); registration signs you in and redirects to the dashboard with a flash message
- **Theme** — persisted light/dark preference (`localStorage` key `budgetlify-theme`)

## Requirements

- PHP 8.3 or newer with common extensions (`openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) (LTS recommended) and npm

## Quick start

One-shot setup (install deps, `.env`, key, migrate, frontend build):

```bash
composer setup
```

Start the full dev stack (HTTP server, Vite, queue worker, Pail logs):

```bash
composer run dev
```

Then open the URL shown by `php artisan serve` (typically `http://127.0.0.1:8000`).

### Manual setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # if using SQLite; or configure MySQL/Postgres in .env
php artisan migrate
npm install
npm run dev    # or npm run build for production assets
php artisan serve
```

## Scripts

| Command | Purpose |
| --- | --- |
| `composer run dev` | Concurrent server, Vite, queue, and log tail |
| `composer test` | Clear config cache and run `php artisan test` |
| `npm run dev` | Vite dev server and HMR |
| `npm run build` | Production frontend build |
| `vendor/bin/pint` | Format PHP (Laravel Pint) |

## Testing

```bash
php artisan test --compact
```

## Project notes

- **Route names** — the login route is `login` (e.g. `route('login')`), not `auth.login`. Blade views under `resources/views/auth/` use the `auth.*` naming for files only.
- **Flash messages** — many flows use the `status` session key; authenticated layouts show it in the main content area.
- **Charts** — live in `resources/js/charts.js` and reinitialize on theme change.

## License

This project inherits the MIT license from the Laravel application skeleton. See the `LICENSE` file if present in the repository.
