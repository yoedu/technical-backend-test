JOB_NAME=technical-backend-test_techtest
PROJECT_NAME=${JOB_NAME}
USER_ID:=$(shell id -u)
GROUP_ID:=$(shell id -g)
COMPOSE=docker-compose

.EXPORT_ALL_VARIABLES:
DOCKER_UID=$(USER_ID)
DOCKER_GID=$(GROUP_ID)

up:
	$(COMPOSE) build
	$(COMPOSE) up -d
down:
	$(COMPOSE) down
install:
	make down
	make up
	make api-vendors-install
reload:
	$(COMPOSE) stop
	make up
api-bash:
	docker exec -it $(PROJECT_NAME)_api_1 bash
nginx-bash:
	docker exec -it $(PROJECT_NAME)_nginx_1 bash
autoload:
	docker exec -it $(PROJECT_NAME)_api_1 composer dump-autoload
api-vendors-install:
	docker exec -it $(PROJECT_NAME)_api_1 composer install
api-vendors-require:
	docker exec -it $(PROJECT_NAME)_api_1 composer require $(package)
api-tests:
	docker exec -it $(PROJECT_NAME)_api_1 vendor/bin/phpunit