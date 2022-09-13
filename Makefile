include .env

up:
	@vendor/bin/sail up -d

down:
	@vendor/bin/sail down

reset:
	@vendor/bin/sail artisan migrate:fresh
	@vendor/bin/sail artisan app:install
	@npm run build
