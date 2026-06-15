# Merchanto

Modular e-commerce platform built with Laravel.

## Requirements

- Docker & Docker Compose
- Make (optional, for convenience commands)

## Stack

| Layer | Technology |
|-------|------------|
| Framework | Laravel 13 |
| Modules | [nwidart/laravel-modules](https://laravelmodules.com/) |
| Storefront | Livewire 4 |
| Admin | Filament 5 |
| Database | PostgreSQL 16 |
| Tests | Pest 4 |
| Code style | [Laravel Duster](https://github.com/tighten/duster) |
| Static analysis | [Larastan](https://github.com/larastan/larastan) (level 5) |
| Runtime | Docker |


## Modules

| Module | Responsibility |
|--------|----------------|
| **Core** | Shared contracts, DTOs, and exceptions between modules |
| **Catalog** | Products, categories, stock, shop UI |
| **Order** | Orders, checkout, order status workflow |

Catalog and Order do not import each other directly. Cross-module communication goes through Core interfaces (`ProductCatalogInterface`, `ProductStockInterface`).

## Setup Instructions

### Installation steps

```bash
git clone git@github.com:alexjoke/merchanto-ecommerce.git
cd merchanto
cp .env.example .env   # optional — Docker creates this on first run
make up
```

On first start, the app container will:

1. Install Composer dependencies
2. Generate an `APP_KEY` if missing
3. Publish module configuration
4. Wait for PostgreSQL to become healthy
5. Run migrations and seed demo data

The application is available at **http://localhost:8080**.

### Database setup

PostgreSQL 16 runs in Docker alongside the app.
Migrations and seeders run automatically on container startup. To reset the database manually:

```bash
make fresh    # migrate:fresh --seed
make migrate  # run pending migrations only
make seed     # run seeders only
```

Seed data includes:

- Admin user (`admin@merchanto.test` / `password`)
- Sample categories and products (Catalog module)
- Demo orders with hashes `ORD-DEMO0001`–`ORD-DEMO0004` (Order module)

### Environment configuration

Copy `.env.example` to `.env` and adjust values if needed.

To create an additional admin user:

```bash
make filament-user
```

---

## Running the Application

### How to start the application

```bash
make up       # start containers (builds on first run)
make down     # stop containers
make shell    # open a shell inside the app container
```

### How to access admin interfaces

| URL | Purpose |
|-----|---------|
| http://localhost:8080/admin | Filament admin panel |

**Default credentials:** `admin@merchanto.test` / `password`

Admin features:

- **Catalog** — manage categories and products (price, stock, published status)
- **Orders** — list orders, update status, view line items

### How to test main functionality

**Storefront (guest, no login required)**

| URL | Purpose |
|-----|---------|
| http://localhost:8080/ | Home page |
| http://localhost:8080/shop | Browse products, filter by category |
| http://localhost:8080/orders/create | Checkout — select quantities and submit an order |
| http://localhost:8080/orders/{orderHash} | View an order by its hash |

**Suggested manual test flow**

1. Open `/shop` and browse products (try `?category=` filter in the URL).
2. Go to `/orders/create`, add items, fill in customer details, and submit.
3. After checkout, note the order hash (e.g. `ORD-XXXXXXXX`) and open `/orders/ORD-XXXXXXXX`.
4. Log in to `/admin` and confirm the new order appears; update its status.
5. View a pre-seeded demo order: `/orders/ORD-DEMO0001`.

---

## Running Tests

Tests use [Pest](https://pestphp.com/) and run as **feature tests** against SQLite inside Docker.

### Command to run all tests

```bash
make test
```

### Command to run module-specific tests

```bash
make test-catalog   # Catalog module
make test-order     # Order module
make test-core      # Core module
make test-module Catalog   # any module by name
```

Equivalent Docker commands:

```bash
docker compose exec app php artisan test Modules/Catalog/tests/Feature
docker compose exec app php artisan test Modules/Order/tests/Feature
docker compose exec app php artisan test Modules/Core/tests/Feature
```

### Static analysis and code style

```bash
make lint      # Duster + PHPStan (level 5)
make analyse   # PHPStan only
make fix       # auto-fix code style with Duster
```

---

## Useful commands

```bash
make up       # start containers (builds on first run)
make down     # stop containers
make shell    # open a shell inside the app container
make lint      # Duster + PHPStan (level 5)
make analyse   # PHPStan only
make fix       # auto-fix code style with Duster
make test                 # run all tests
make test-catalog         # Catalog module tests
make test-order           # Order module tests
make test-core            # Core module tests
make test-module Catalog  # any module by name
make module Catalog       # create a new module
make fresh                # migrate:fresh --seed
make filament-user        # create additional admin user
make artisan migrate      # run migrations
make artisan route:list   # list registered routes
```
