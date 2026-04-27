# Budgetlify

Personal finance workspace UI built with **Laravel** and **Tailwind CSS**. The app focuses on dashboards, transactions, budgets, and analytics with a minimal fintech-style interface, light/dark theme, and demo data (no production auth or persistence wired yet).

## Stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.3+, Laravel 13 |
| Frontend | Vite 8, Tailwind CSS 4, Chart.js |
| Tests | Pest 4 |

## Features

- **Dashboard** — stat cards, expense overview chart with range filter (demo series), recent transactions
- **Transactions** — filters, table, pagination UI (demo)
- **Budgets** — budget cards and create flow (demo)
- **Analytics** — charts for monthly trends, categories, savings, cash flow
- **Settings** — profile, currency, appearance (theme), notifications (demo)
- **Auth screens** — split-layout login, register, and forgot-password (UI only; forms are placeholders)
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

- **Auth routes** (`/login`, `/register`, `/forgot-password`) render Blade only; connect to [Laravel authentication](https://laravel.com/docs/authentication) when you are ready.
- **Demo content** lives in controllers and Blade (`@php` blocks) where noted; replace with Eloquent models and policies as you grow the product.
- **Charts** live in `resources/js/charts.js` and reinitialize on theme change.

## License

This project inherits the MIT license from the Laravel application skeleton. See the `LICENSE` file if present in the repository.
