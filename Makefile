start:
	php bin/console server:run
database:
	php bin/console doctrine:database:create
migration:
	php bin/console make:migration
migrate:
	php bin/console doctrine:migrations:migrate
fixtures:
	php bin/console doctrine:fixtures:load
controller:
	php bin/console make:controller
entity:
	php bin/console make:entity
form:
	php bin/console make:form
user:
	php bin/console make:user