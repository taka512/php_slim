.PHONY: help composer/* db/* test/* docker/*

CONTAINER?=app.local

ifeq ($(ENV),dev)
COMPOSER_OPT=--dev
else ifeq ($(ENV),prod)
COMPOSER_OPT=--no-dev
else
COMPOSER_OPT=
endif

COMPOSER_VERSION = 1.10.13

help:
	@grep -E '^[a-zA-Z\/_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

api/yaml/build: ## update open api document
	vendor/bin/openapi --output data/openapi.yaml src

###############
# compose
###############
composer.phar:
	curl -sS https://getcomposer.org/installer | php -- --version=$(COMPOSER_VERSION) --install-dir=./

composer/install: composer.phar
	php composer.phar install $(COMPOSER_OPT)

composer/update: composer.phar
	php composer.phar update $(COMPOSER_OPT)

composer-self-update: composer.phar
	php composer.phar self-update $(COMPOSER_VERSION)

###############
#  lint
###############
CSFIXER_FULE=@PSR1,@PSR2,@Symfony
CSFIXER_DRYRUN=--dry-run --diff

lint: csfixer phpstan

csfixer:
	php vendor/bin/php-cs-fixer fix ./src $(CSFIXER_DRYRUN) --rules=$(CSFIXER_FULE) --allow-risky=yes
	php vendor/bin/php-cs-fixer fix ./tests $(CSFIXER_DRYRUN) --rules=$(CSFIXER_FULE) --allow-risky=yes

csfixer-fix:
	php vendor/bin/php-cs-fixer fix ./src --rules=$(CSFIXER_FULE)
	php vendor/bin/php-cs-fixer fix ./tests --rules=$(CSFIXER_FULE)

phpstan:
	php vendor/bin/phpstan analyse -l 0 src

###############
#  db
###############
db/status:
	php vendor/bin/phpmig status -b config/migration.php
db/migrate:
	php vendor/bin/phpmig migrate -b config/migration.php
db/test/status:
	php vendor/bin/phpmig status -b config/migration_test.php
db/test/migrate:
	php vendor/bin/phpmig migrate -b config/migration_test.php
db/seed/status:
	php vendor/bin/phpmig status -b config/seed.php
db/seed/migrate:
	php vendor/bin/phpmig migrate -b config/seed.php
db/seed/down:
	php vendor/bin/phpmig down $(ID) -b config/seed.php
db/seed/down/all:
	php vendor/bin/phpmig down 20190323000000 -b config/seed.php
	php vendor/bin/phpmig down 20190322000000 -b config/seed.php
	php vendor/bin/phpmig down 20190105000000 -b config/seed.php
	php vendor/bin/phpmig down 20181027000000 -b config/seed.php

###############
# docker
###############
docker/build:
	docker-compose build
docker/pull:
	docker-compose pull
docker/up: ## run containers
	docker-compose up -d
docker/logs: ## display docker log
	docker-compose logs -f
docker/stop: ## stop containers
	docker-compose stop
docker/rm:
	docker-compose rm
docker/ssh: ## connect in container
	docker exec -it -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(CONTAINER) ash
docker/prune:
	docker system prune 
docker/db/status:
	docker exec $(CONTAINER) make db/status
docker/db/migrate: ## apply DDL for db
	docker exec $(CONTAINER) make db/migrate
docker/db/test/status:
	docker exec $(CONTAINER) make db/test/status
docker/db/test/migrate: ## apply DDL for test db
	docker exec $(CONTAINER) make db/test/migrate
docker/db/seed/status:
	docker exec $(CONTAINER) make db/seed/status
docker/db/seed/migrate: ## apply DML for db
	docker exec $(CONTAINER) make db/seed/migrate
docker/db/seed/down/all: ## drop data for db
	docker exec $(CONTAINER) make db/seed/down/all
docker/composer/install: ## install composer library
	docker exec $(CONTAINER) make composer/install ENV=$(ENV)
docker/composer/update: ## update composer.lock and install composer library
	docker exec $(CONTAINER) make composer/update ENV=$(ENV)
docker/test: ## test for server side code
	docker exec $(CONTAINER) make test
docker/lint: ## lint for server side code
	docker exec $(CONTAINER) make lint

docker/client/install: ## install for client lib
	docker run -w /home/php_slim/client -v `pwd`:/home/php_slim -it node:10.15-alpine npm install
docker/client/lint: ## lint for client code
	docker run -w /home/php_slim/client -v `pwd`:/home/php_slim -it node:10.15-alpine npm run lint
docker/client/build: ## build for client code
	docker run -w /home/php_slim/client -v `pwd`:/home/php_slim -it node:10.15-alpine npm run build
docker/client/test: ## test for client code
	docker run -w /home/php_slim/client -v `pwd`:/home/php_slim -it node:10.15-alpine npm run test

#########
# test
#########
test:
	make test/unit
	make test/functional
	make test/e2e

test/unit:
	php vendor/bin/phpunit -c ./tests/phpunit.xml tests/Unit

test/functional:
	php vendor/bin/phpunit -c ./tests/phpunit.xml tests/Functional

test/e2e:
	php vendor/bin/phpunit -c ./tests/phpunit.xml tests/E2e

