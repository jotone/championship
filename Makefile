include .env

up:
	@docker-compose up -d

down:
	@docker-compose down

cmd:
	@docker exec -it estates_php-fpm_1 bash -c "${c}"

reset:
	@php artisan migrate:fresh
	@php artisan app:install
	@npm run build
