# Variables
DOCKER_COMPOSE = docker compose
DOCKER_COMPOSE_PHP = $(DOCKER_COMPOSE) exec -u devuser php
SYMFONY = $(DOCKER_COMPOSE_PHP) symfony
CONSOLE = $(SYMFONY) console
SYMFONY_PHP = $(SYMFONY) php
COMPOSER = $(SYMFONY) composer

cc: ## Clear the cache
	$(CONSOLE) cache:clear
.PHONY: cc

cc-test: ## Clear the cache for test environment
	$(CONSOLE) cache:clear --env=test
.PHONY: cc-test

start: up vendor ## Start the Docker containers
	$(SYMFONY) server:start --allow-http --no-tls -d
.PHONE: start

up: ## Start the Docker containers
	$(DOCKER_COMPOSE) up -d
.PHONY: up

stop: ## Stop the Docker containers
	$(SYMFONY) server:stop
	$(DOCKER_COMPOSE) down
.PHONY: down

restart: ## Restart the Docker containers
	$(DOCKER_COMPOSE) restart
.PHONY: restart

build: ## Build the Docker images
	$(DOCKER_COMPOSE) build
.PHONY: build

logs: ## Show the Docker logs
	$(DOCKER_COMPOSE) logs -f
.PHONY: logs

php-shell: ## Run a shell in the PHP container
	$(DOCKER_COMPOSE_PHP) sh
.PHONY: php-shell

vendor: up ## Install the dependencies
	$(COMPOSER) install --no-scripts --prefer-dist --no-progress --no-interaction

# Tests
behat: ## Run Behat tests
	$(SYMFONY_PHP) vendor/bin/behat
.PHONY: behat

behat-progress: ## Run Behat tests
	$(SYMFONY_PHP) vendor/bin/behat --format=progress
.PHONY: behat-progress

behat-suite: ## Run Behat tests
	$(SYMFONY_PHP) vendor/bin/behat --suite=$(suite)
.PHONY: behat-suite

phpunit: ## Run PHPUnit tests
	$(SYMFONY_PHP) vendor/bin/phpunit
.PHONY: phpunit

phpunit-filter: ## Run PHPUnit tests
	$(SYMFONY_PHP) vendor/bin/phpunit --filter=$(filter)
.PHONY: phpunit-filter

test: ## Run all the tests
	$(MAKE) phpunit
	$(MAKE) behat-progress
.PHONY: test

deptrac: ## Run Deptrac
	$(SYMFONY_PHP) vendor/bin/deptrac analyze --report-uncovered
.PHONY: deptrac

phpstan: ## Run PHPStan
	$(SYMFONY_PHP) vendor/bin/phpstan analyse --memory-limit=256M
.PHONY: phpstan

php-cs-fix: ## Run fix PHP-CS-Fixer
	$(SYMFONY_PHP) vendor/bin/php-cs-fixer fix --no-interaction
.PHONY: php-cs-fix

php-cs-fixer: ## Run PHP-CS-Fixer
	$(SYMFONY_PHP) vendor/bin/php-cs-fixer fix --dry-run --no-interaction --diff
.PHONY: php-cs-fixer

rector: ## Run Rector dry-run
	$(SYMFONY_PHP) vendor/bin/rector process --dry-run --ansi
.PHONY: rector

rector-fix: ## Run Rector
	$(SYMFONY_PHP) vendor/bin/rector process --ansi
.PHONY: rector-fix

rector-ci: ## Run Rector in CI mode
	$(CONSOLE) cache:warmup
	$(SYMFONY_PHP) vendor/bin/rector process --dry-run --ansi
.PHONY: rector-ci

security: ## Run the security checker
	$(SYMFONY) security:check
.PHONY: security

qa: ## Run all the QA tools
	$(MAKE) php-cs-fixer
	$(MAKE) phpstan
	$(MAKE) deptrac
	$(MAKE) security
	$(MAKE) rector-ci
	$(MAKE) phpunit
	$(MAKE) behat-progress
.PHONY: qa

# Database
db-migration: ## Generate the database migration
	$(CONSOLE) make:migration
.PHONY: db-migration

db-migrate: ## Apply the database migration
	$(CONSOLE) doctrine:migrations:migrate
.PHONY: db-migrate

db-rollback: ## Rollback the database migration
	$(CONSOLE) doctrine:migrations:migrate prev
.PHONY: db-rollback

db-reset: ## Reset the database
	$(CONSOLE) doctrine:database:drop --force --if-exists
	$(CONSOLE) doctrine:database:create
	$(CONSOLE) doctrine:migrations:migrate --no-interaction
.PHONY: db-reset

db-reset-test: ## Reset the database for test environment
	$(CONSOLE) doctrine:database:drop --force --if-exists --env=test
	$(CONSOLE) doctrine:database:create --env=test
	$(CONSOLE) doctrine:migrations:migrate --env=test --no-interaction
.PHONY: db-reset-test

db-dump: ## Dump the database
	$(CONSOLE) doctrine:database:dump --force
.PHONY: db-dump

db-load: ## Load the database
	$(CONSOLE) doctrine:database:load
.PHONY: db-load

db-up-to-date: ## Check if the database is up to date
	$(CONSOLE) doctrine:migrations:up-to-date
.PHONY: db-up-to-date

# Help
help:
	@echo "Available targets:\n"
	@awk -F ':|##' '/^[^\t].+?:.*?##/ { printf "\033[36m%-20s\033[0m %s\n", $$1, $$NF }' $(MAKEFILE_LIST) | sort -u

.DEFAULT_GOAL := help
