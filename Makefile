DOCKER_COMPOSE ?= cd docker && docker-compose -p discord_proxy_slackbot

.PHONY: start
start: erase build up ## clean current environment, recreate dependencies and spin up again

.PHONY: rebuild
rebuild: start ## same as start

.PHONY: erase
erase: ## stop and delete containers, clean volumes.
		$(DOCKER_COMPOSE) stop
		$(DOCKER_COMPOSE) rm -v -f

.PHONY: build
build: ## build environment and initialize composer and project dependencies
		$(DOCKER_COMPOSE) build

.PHONY: up
up: ## spin up environment
		$(DOCKER_COMPOSE) up -d

.PHONY: bash
bash: ## gets inside a workspace container
		$(DOCKER_COMPOSE) exec --user workspace workspace bash -l

.PHONY: composer
composer: ## composer install
		$(DOCKER_COMPOSE) exec --user workspace workspace composer install

.PHONY: keygen
keygen: ## generate keys
		$(DOCKER_COMPOSE) exec --user workspace workspace php artisan key:generate

.PHONY: testing
testing: ## application testing
		$(DOCKER_COMPOSE) exec --user workspace workspace vendor/bin/phpunit
