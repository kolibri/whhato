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

#init:
#	ansible-playbook -i ansible/inventory-init ansible/provision.yml --tags=init

tarball:
	rm -f whhato.tar.gz
	cd project && tar -czf ../whhato.tar.gz .
