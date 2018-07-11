CONTAINER?=app.local

ifeq ($(ENV),dev)
COMPOSER_OPT=--dev
else ifeq ($(ENV),prod)
COMPOSER_OPT=--no-dev
else
COMPOSER_OPT=
endif

all:
	@more Makefile

###############
# compose
###############
.PHONY: composer/*
composer/install:
	php composer.phar install $(COMPOSER_OPT)

composer/update:
	php composer.phar update $(COMPOSER_OPT)

composer/self-update:
	php composer.phar self-update

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
	docker exec -it -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(CONTAINER) /bin/sh
# 未使用イメージの掃除
docker/prune:
	docker system prune 
