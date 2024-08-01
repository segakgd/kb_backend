BRANCH = main

# Задачи
.PHONY: update install-dev-deps migrate cache-clear build

# Задача для выполнения всех шагов
build-dev: update install-dev-deps migrate cache-clear

# Подгрузка обновлений с основной ветки
update:
	@echo "Fetching updates from $(BRANCH) branch..."
	git checkout $(BRANCH)
	git pull origin $(BRANCH)
	@echo "Update complete."

# Установка зависимостей Composer
install-dev-deps:
	@echo "Installing Composer dependencies..."
	composer install --no-interaction
	@echo "Dependencies installed."

# Прокатывание миграций базы данных
migrate:
	@echo "Running database migrations..."
	php bin/console doctrine:migrations:migrate --no-interaction
	@echo "Migrations complete."

# Дроп кеша
cache-clear:
	@echo "Clear cache"
	php bin/console cache:clear
	@echo "Clear cache complete."

# Заходим в контейнер php
php:
	docker exec -it php-fpm-local bash

local-up:
	docker compose -f docker-compose-local.yml up -d

local-build:
	docker compose -f docker-compose-local.yml build

dev-up:
	docker compose -f docker-compose.yml up -d

dev-build:
	docker compose -f docker-compose.yml build

certbot-issue-certificate:
	certonly --webroot --webroot-path=/var/www/certbot --email hpsergeyy@yandex.ru --agree-tos --no-eff-email --force-renewal --no-eff-email -d mydevbot.ru -d www.mydevbot.ru