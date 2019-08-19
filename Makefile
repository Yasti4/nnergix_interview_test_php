docker-compose-build:
	docker-compose -f $(PWD)/docker-compose.yaml build

docker-compose-up:
	docker-compose -f $(PWD)/docker-compose.yaml up $(COMMAND)

docker-compose-down:
	docker-compose -f $(PWD)/docker-compose.yaml down $(COMMAND)

php-bash:
	@docker exec -it nnergix-php bash

db-bash:
	@docker exec -it nnergix-db bash

php-tests:
	@docker exec -it nnergix-php bin/phpunit

php-coverage:
	@docker exec -it nnergix-php bin/phpunit --coverage-html var/coverage
