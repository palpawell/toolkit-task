.PHONY: run
run:
	docker-compose up -d \
	&& docker-compose exec php bash -c "php artisan migrate:fresh --seed"

.PHONY: docker-up
docker-up:
	docker-compose up -d

.PHONY: stop
docker-stop:
	docker-compose down

.PHONY: docker-restart
docker-restart: docker-stop docker-up

.PHONY: run-tests
run-tests:
	docker-compose up -d \
	&& docker-compose exec php bash -c "php artisan test"

.PHONY: run-api-docs
run-api-docs:
	docker-compose up -d \
	&& docker-compose exec php bash -c "php artisan l5-swagger:generate"

.PHONY: run-phpcs
run-phpcs:
	docker-compose up -d \
    	&& docker-compose exec php bash -c "php vendor/bin/phpcs -v app"
