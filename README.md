# Merchanto

Modular e-commerce platform scaffold built with Laravel. Docker-ready foundation for feature development — no business modules implemented yet.

## Requirements

- Docker & Docker Compose
- Make (optional, for convenience commands)

## Quick start

```bash
git clone repo
cd merchanto
cp .env.example .env   # optional — Docker creates this automatically
make up
```

First run installs Composer dependencies, runs migrations, seeds an admin user, and starts the app.

| URL | Purpose |
|-----|---------|
| http://localhost:8080 | Application |
| http://localhost:8080/admin | Filament admin panel |

**Default admin:** `admin@merchanto.test` / `password`

```bash
make test              # run Pest tests
make lint              # Duster + Larastan (level 5)
make analyse           # Larastan only
make fix               # auto-fix code style (Duster)
make shell             # open app container shell
make down              # stop containers
```

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


## Useful commands

```bash
make module Catalog       # create a new module
make fresh                # migrate:fresh --seed
make filament-user        # create additional admin user
make artisan migrate      # run migrations
make lint                 # Duster + Larastan (run before submitting)
make analyse              # Larastan static analysis only
make fix                  # Duster auto-fix
```
