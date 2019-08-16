docker-compose-build:
	docker-compose -f $(PWD)/docker-compose.yaml build

php-bash:
	@docker exec -it nnergix-php bash

db-bash:
	@docker exec -it nnergix-db bash

docker-compose-up:
	docker-compose -f $(PWD)/docker-compose.yaml up $(COMMAND)

docker-compose-down:
	docker-compose -f $(PWD)/docker-compose.yaml down $(COMMAND)

