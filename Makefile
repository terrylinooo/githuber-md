PROJECT_NAME=wp-githuber-md

.PHONY: up build down restart reset shell shell.db \ 
	wp.install wp.activate-plugin wp.init-dev-site

up:
	docker compose up -d

build:
	docker compose build

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d

reset:
	docker compose down -v --remove-orphans
	docker compose up -d

shell:
	docker compose exec wordpress bash

shell.db:
	docker compose exec db mysql -u wp_user -pwp_pass wordpress

wp.install:
	docker compose exec wordpress sh -c '\
		cd /var/www/html && \
		if [ ! -f wp-config.php ]; then \
			wp config create \
				--dbname=wordpress \
				--dbuser=wp_user \
				--dbpass=wp_pass \
				--dbhost=db:3306 \
				--dbprefix=wp_ \
				--skip-check \
				--allow-root; \
		fi'
	docker compose exec wordpress sh -c '\
		if ! wp core is-installed --allow-root; then \
			wp core install \
				--url="http://localhost:8000" \
				--title="WP GitHuber MD Dev" \
				--admin_user=admin \
				--admin_password=admin \
				--admin_email=admin@example.com \
				--skip-email \
				--allow-root; \
		fi'
	docker compose exec wordpress sh -c '\
		cd /var/www/html/wp-content/plugins/wp-githuber-md && composer install'

wp.activate-plugin:
	docker compose exec wordpress wp plugin activate wp-githuber-md --allow-root

wp.init-dev-site:
	make wp.install
	make wp.activate-plugin