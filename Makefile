.PHONY: lint test


IMAGE := dredd-hooks-php
DOCKER_RUN := docker run -it --init -v $$(pwd):/src -w /src $(IMAGE)

build:
	docker build -t $(IMAGE) .

test: build lint node_modules
	$(DOCKER_RUN) bash -c \
	    "vendor/bin/phpunit && \
	    npx cucumber-js features --require features/support/ --tags 'not @skip'"

lint: vendor
	$(DOCKER_RUN) vendor/bin/phpcs --standard=psr2 -n src/

vendor:
	$(DOCKER_RUN) composer install

node_modules:
	$(DOCKER_RUN) npm install
