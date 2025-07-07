include .env
-include .env.local

IMAGE = $(IMAGE_REPO)/$(IMAGE_NAME):$(IMAGE_TAG)

# Выбираем compose команду в зависимости от CONTAINER_SYSTEM
ifeq ($(CONTAINER_SYSTEM),podman)
	COMPOSE = podman-compose
else
	COMPOSE = docker-compose
endif

.PHONY: help
help: ## Показать помощь
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

############
#  Docker  #
############
.PHONY: build
build: ## Сборка контейнера
	docker build -t $(IMAGE) .

.PHONY: push
push: ## Публикация контейнера
	docker push $(IMAGE)

#########
#  Кэш  #
#########
.PHONY: cache-clear
cache-clear: ## Сбросить кэш
	bin/console cache:clear

###########
#  Фиксы  #
###########
.PHONY: fix
fix: ## Автоматическое исправление кода (phpcbf)
	vendor/bin/phpcbf

###########
#  Тесты  #
###########
.PHONY: test-all
test-all: ## Запустить тесты
	$(COMPOSE) run --rm mcp-server bin/console cache:clear
	$(COMPOSE) run --rm mcp-server bin/console -vv app:test
	$(COMPOSE) run --rm mcp-server bin/phpunit
	$(COMPOSE) run --rm mcp-server bin/console -vv app:test-server
	$(COMPOSE) run --rm mcp-server bin/console -vv app:test-client --via=console

.PHONY: lint
lint: ## Запустить линтер
	./vendor/bin/parallel-lint config src tests

.PHONY: tests
tests: ## Запустить тесты
	php -d pcov.enabled=1 bin/phpunit tests/Unit --coverage-text --only-summary-for-coverage-text

.PHONY: psalm
psalm: ## Запустить статический анализ
	vendor/bin/psalm --no-cache --no-progress --output-format=console

.PHONY: phpcs
phpcs: ## Проверить стиль кода
	vendor/bin/phpcs -q && echo "PHPCS passed"
