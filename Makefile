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

composer-install:
	php composer.phar install $(COMPOSER_OPT)

composer-update:
	php composer.phar update $(COMPOSER_OPT)

composer-self-update:
	php composer.phar self-update $(COMPOSER_VERSION)

test:
	php composer.phar test
