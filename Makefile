.PHONY: up down build shell artisan test test-catalog test-order test-core test-module migrate fresh seed module filament-user lint fix analyse

up:
	docker compose up -d --build --remove-orphans

down:
	docker compose down --remove-orphans

build:
	docker compose build

shell:
	docker compose exec app sh

artisan:
	docker compose exec app php artisan $(filter-out $@,$(MAKECMDGOALS))

test:
	docker compose exec app php artisan test

test-catalog:
	docker compose exec app php artisan test Modules/Catalog/tests/Feature

test-order:
	docker compose exec app php artisan test Modules/Order/tests/Feature

test-core:
	docker compose exec app php artisan test Modules/Core/tests/Feature

test-module:
	docker compose exec app php artisan test Modules/$(filter-out $@,$(MAKECMDGOALS))/tests/Feature

lint:
	docker compose exec app composer lint

fix:
	docker compose exec app composer fix

analyse:
	docker compose exec app composer analyse

migrate:
	docker compose exec app php artisan migrate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

seed:
	docker compose exec app php artisan db:seed

module:
	docker compose exec app php artisan module:make $(filter-out $@,$(MAKECMDGOALS))

filament-user:
	docker compose exec app php artisan make:filament-user

%:
	@:
