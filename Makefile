STAGE=vagrantbox

full-deploy:
#	$(MAKE) init
	$(MAKE) install
	$(MAKE) test
	$(MAKE) tarball
#	$(MAKE) provision
	$(MAKE) deploy

vagrant-start:
	vagrant up
	$(MAKE) provision
	$(MAKE) deploy

vagrant-full-init:
	vagrant destroy -f
	vagrant up
	$(MAKE) provision-init
	$(MAKE) provision
	$(MAKE) deploy

provision-init:
	ansible-playbook -i ansible/$(STAGE)-init ansible/provision.yml --tags=init -v

provision:
	ansible-playbook -i ansible/$(STAGE) ansible/provision.yml -v

deploy:
	ansible-playbook -i ansible/$(STAGE) ansible/deploy.yml -v

install:
	cd project && composer install --no-interaction --prefer-dist --optimize-autoloader
	cd project && yarn install --non-interactive
	cd project && ./bin/phpunit install

test: clean
	cd project && ./bin/console lint:yaml data
	cd project && ./bin/console lint:twig templates
	cd project && ./bin/phpunit
	cd project && ./vendor/bin/php-cs-fixer fix --dry-run

test-fix: cs-fixer test

test-coverage:
	cd project && ./bin/phpunit --coverage-html=../coverage tests/Whhato

cs-fixer:
	cd project && ./vendor/bin/php-cs-fixer fix

clean:
	rm -rf project/var/cache/*


docker-build:
	docker build -t whhato-deploy .
docker-run: docker-build
	docker run -ti whhato-deploy


tarball: install
	rm -f whhato.tar.gz
	cd project && tar -czf ../whhato.tar.gz .
