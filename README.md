# overview

sample application of slim framework

 file | contents
 --- | --- 
 batch/ | batch file
 bootstrap/ | web access
 [client/](./client) | client code
 config/ | setting files
 data/ | open api data..etc
 docker/ | docker env files
 migrations/ | DDL migration files
 public/ | document root dir from web access
 seeds/ | DML migration files
 src/ | server side code
 templates/ | twig template files
 tests/ | test files
 vendor/ | composer lib dir

# how to develop

run docker

```
make docker/up
```

create .env

```
cp env.sample .env
```

install composer lib

```
make docker/composer/install
```

create database and table data

```
make docker/db/migrate
make docker/db/test/migrate
make docker/db/seed/migrate
```

access local develop site

https://localhost

command list

```
make help
```

# see also

https://github.com/taka512/php_slim/wiki

[@MITLicense](https://twitter.com/MITLicense)
