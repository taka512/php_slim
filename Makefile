ifeq ($(ENV),dev)
COMPOSER_OPT=--dev
else ifeq ($(ENV),prod)
COMPOSER_OPT=--no-dev
else
COMPOSER_OPT=
endif

all:
	@more Makefile

composer-install:
	php composer.phar install $(COMPOSER_OPT)

composer-update:
	php composer.phar update $(COMPOSER_OPT)

composer-self-update:
	php composer.phar self-update

test:
	php composer.phar test
