BRANCH = main

# Задачи
.PHONY: update install-deps migrate cache-clear build

# Задача для выполнения всех шагов
build: update install-deps migrate cache-clear

# Подгрузка обновлений с основной ветки
update:
	@echo "Fetching updates from $(BRANCH) branch..."
	git checkout $(BRANCH)
	git pull origin $(BRANCH)
	@echo "Update complete."

# Установка зависимостей Composer
install-deps:
	@echo "Installing Composer dependencies..."
	composer install --no-dev --optimize-autoloader
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
