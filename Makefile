.PHONY: dev migrate seed

dev:
	php -S localhost:8000 -t public

migrate:
	php seeds/001_migration.php

seed:
	php seeds/002_seed_data.php

reset: migrate seed
