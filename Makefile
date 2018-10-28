CONTAINER?=app.local

ifeq ($(ENV),dev)
COMPOSER_OPT=--dev
else ifeq ($(ENV),prod)
COMPOSER_OPT=--no-dev
else
COMPOSER_OPT=
endif

COMPOSER_VERSION = 1.7.2
PHPUNIT_VERSION := 6.5.5

all:
	@more Makefile

composer.phar:
	curl -sS https://getcomposer.org/installer | php -- --version=$(COMPOSER_VERSION) --install-dir=./

###############
# compose
###############
.PHONY: composer/*
composer/install: composer.phar
	php composer.phar install $(COMPOSER_OPT)

composer/update: composer.phar
	php composer.phar update $(COMPOSER_OPT)

composer-self-update: composer.phar
	php composer.phar self-update $(COMPOSER_VERSION)

test:
	php -d memory_limit=256M vendor/bin/phpunit --configuration ./tests/phpunit.xml tests

###############
#  db
###############
.PHONY: db/*
db/status:
	php vendor/bin/phpmig status -b config/migration.php
db/migrate:
	php vendor/bin/phpmig migrate -b config/migration.php
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
docker/exec/init:
	docker exec $(CONTAINER) make composer/install
docker/exec/update:
	docker exec $(CONTAINER) make composer/update
docker/db/status:
	docker exec $(CONTAINER) make db/status
docker/db/migrate:
	docker exec $(CONTAINER) make db/migrate
docker/db/seed/status:
	docker exec $(CONTAINER) make db/seed/status
docker/db/seed/migrate:
	docker exec $(CONTAINER) make db/seed/migrate
docker/db/seed/down:
	docker exec $(CONTAINER) make db/seed/down ID=$(ID)
