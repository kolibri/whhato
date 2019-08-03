STAGE=vagrantbox

all: 
#	$(MAKE) init
	$(MAKE) provision
	$(MAKE) tarball
	$(MAKE) deploy


provision:
	ansible-playbook -i ansible/$(STAGE) ansible/provision.yml

deploy:
	ansible-playbook -i ansible/$(STAGE) ansible/deploy.yml

init:
	ansible-playbook -i ansible/$(STAGE)-init ansible/provision.yml --tags=init

install:
	cd project && composer install --no-interaction --prefer-dist --optimize-autoloader
	cd project && ./bin/phpunit install

test:
	cd project && ./bin/console lint:yaml data
	cd project && ./bin/phpunit

tarball: install
	rm -f whhato.tar.gz
	cd project && tar -czf ../whhato.tar.gz .
