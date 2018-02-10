.PHONY: clean test

test: vendor node_modules
	npm run dredd
	
vendor:
	composer install

node_modules:
	npm install

clean:
	rm -rf vendor
	rm -rf node_modules
