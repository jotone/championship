include .env

up:
	@docker-compose up -d

down:
	@docker-compose down

cmd:
	@docker exec -it championship_php-fpm_1 bash -c "${c}"

reset:
	@docker exec -it championship_php-fpm_1 bash -c "php artisan migrate:fresh && php artisan app:install"
	@npm run prod

seed:
	@docker exec -it championship_php-fpm_1 bash -c "${php artisan db:seed}"