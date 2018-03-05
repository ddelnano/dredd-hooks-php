.PHONY: lint test

test: lint
	vendor/bin/phpunit
	bundle exec cucumber

lint: vendor
	vendor/bin/phpcs --standard=psr2 -n src/

vendor:
	composer install
