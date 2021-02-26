DC_EXEC_TEST_PHP=docker-compose exec orocommerce
MODULE_PATH=/var/www/module/

.PHONY: help
help: ## This help
	@grep -Eh '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

up: ## Up containers
	docker-compose up

up-d: ## Up containers in background
	docker-compose up -d

ps: ## List containers
	docker-compose ps

logs: ## Retrieve logs
	docker-compose logs -f

stop: ## Stops running containers
	docker-compose stop

down: ## Down containers
	docker-compose down

restart: ## restart
	docker-compose restart

phpstan: composer.lock ## launch phpstan
	$(DC_EXEC_TEST_PHP) $(MODULE_PATH)vendor/bin/phpstan analyse -c $(MODULE_PATH)phpstan.neon -l max $(MODULE_PATH)src/ $(MODULE_PATH)tests/

phpunit: ## launch phpunit tests
	$(DC_EXEC_TEST_PHP) $(BIN_PATH)/phpunit --testdox

composer:
	$(DC_EXEC_TEST_PHP) composer --working-dir=/var/www/module/ ${args}
