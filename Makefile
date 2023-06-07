.DEFAULT_GOAL := help

include .env

echo-server: ## print server
	@echo "Server link: http://${COMPOSE_PROJECT_NETWORK}.2/api/docs"

open-server: ## open server
	@open "http://${COMPOSE_PROJECT_NETWORK}.2/api/docs"

build: create-env ## Build all containers
	@docker compose build --no-cache

up: create-env ## Start all containers
	@docker compose up -d
	@docker compose exec php-fpm composer install
	@docker compose exec php-fpm php artisan key:generate
	@docker compose exec php-fpm php artisan jwt:secret --force
	@docker compose exec php-fpm php artisan migrate:fresh --seed
	@docker compose exec php-fpm php artisan storage:link
	@docker compose exec php-fpm php artisan l5-swagger:generate
	@make echo-server

down: ## Stop all containers
	@docker compose down -v

start: ## Start all containers
	@docker compose start
#    @echo "http://.2:8000/api/docs"
stop: ## Stop all containers
	@docker compose stop

restart: ## Restart all containers
	@docker compose restart

ps: ## List all containers
	@docker compose ps

logs: ## Show logs
	@docker compose logs -f

php: ## Connect to PHP container
	@docker compose exec -it php-fpm bash

tinker: ## Tinker
	@docker compose exec -it php-fpm php artisan tinker

swagger: ## Swagger
	@docker compose exec -it php-fpm php artisan l5-swagger:generate

postgresql: create-env ## Connect to PostgreSQL
	@docker compose exec -it postgresql psql -U ${DB_USERNAME} -d ${DB_DATABASE}

create-env:
	if ! [ -f .env ]; then cp .env.example .env;fi

help:
	@grep -E '^[a-zA-Z0-9 -]+:.*#'  Makefile | sort | while read -r l; do printf "\033[1;32m$$(echo $$l | cut -f 1 -d':')\033[00m:$$(echo $$l | cut -f 2- -d'#')\n"; done
