CONTAINER?=app.local

ifeq ($(ENV),dev)
COMPOSER_OPT=--dev
else ifeq ($(ENV),prod)
COMPOSER_OPT=--no-dev
else
COMPOSER_OPT=
endif

COMPOSER_VERSION = 1.6.2
PHPUNIT_VERSION := 6.5.5

all:
	@more Makefile

composer.phar:
	curl -sS https://getcomposer.org/installer | php -- --version=$(COMPOSER_VERSION) --install-dir=./

###############
# compose
###############
.PHONY: composer/*
composer/install:
>>>>>>> 00ac3365ef10e38b97d1462c2dc6f5c7d41f427e
	php composer.phar install $(COMPOSER_OPT)

composer/update:
	php composer.phar update $(COMPOSER_OPT)

<<<<<<< HEAD
composer-self-update:
	php composer.phar self-update $(COMPOSER_VERSION)
=======
composer/self-update:
	php composer.phar self-update
>>>>>>> 00ac3365ef10e38b97d1462c2dc6f5c7d41f427e

test:
	php composer.phar test

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
	docker exec $(CONTAINER) php vendor/bin/phpmig status
docker/db/up:
	docker exec $(CONTAINER) php vendor/bin/phpmig up
