CONTAINER?=app.local

ifeq ($(ENV),dev)
COMPOSER_OPT=--dev
else ifeq ($(ENV),prod)
COMPOSER_OPT=--no-dev
else
COMPOSER_OPT=
endif

COMPOSER_VERSION = 1.7.2

all:
	@more Makefile

###############
# compose
###############
.PHONY: composer/*
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
.PHONY: db/*
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

###############
# docker
###############
.PHONY: docker/*
# YAMLに「build:」があれば、そのイメージをまとめてビルド
docker/build:
	docker-compose build
# YAMLに「image:」があれば、そのイメージをまとめてプル
docker/pull:
	docker-compose pull
# docker-compose build, docker-compose pullをした後にdocker run
docker/up:
	docker-compose up -d
# 関係するコンテナすべての出力を表示
docker/logs:
	docker-compose logs -f
# 関係するコンテナをまとめて終了
docker/stop:
	docker-compose stop
# 関係するコンテナをまとめて削除
docker/rm:
	docker-compose rm
# sshで接続
docker/ssh:
	docker exec -it -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(CONTAINER) ash
# 未使用イメージの掃除
docker/prune:
	docker system prune 
docker/db/status:
	docker exec $(CONTAINER) make db/status
docker/db/migrate:
	docker exec $(CONTAINER) make db/migrate
docker/db/test/status:
	docker exec $(CONTAINER) make db/test/status
docker/db/test/migrate:
	docker exec $(CONTAINER) make db/test/migrate
docker/db/seed/status:
	docker exec $(CONTAINER) make db/seed/status
docker/db/seed/migrate:
	docker exec $(CONTAINER) make db/seed/migrate
docker/db/seed/down:
	docker exec $(CONTAINER) make db/seed/down ID=$(ID)
docker/composer/install:
	docker exec $(CONTAINER) make composer/install ENV=$(ENV)
docker/composer/update:
	docker exec $(CONTAINER) make composer/update ENV=$(ENV)
docker/test:
	docker exec $(CONTAINER) make test
docker/lint:
	docker exec $(CONTAINER) make lint

#########
# test
#########
.PHONY: test/*
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
