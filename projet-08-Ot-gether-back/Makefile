SAIL = ./vendor/bin/sail

.PHONY: prepare
prepare:
	$(SAIL) artisan migrate:fresh --seed

.PHONY: pint
pint:
	$(SAIL) pint

.PHONY: analyse
analyse:
	./vendor/bin/phpstan analyse

.PHONY: clean
clean:
	make pint
	make analyse

.PHONY: helpers
helpers:
	$(SAIL) artisan ide-helper:generate
	$(SAIL) artisan ide-helper:models -M
	$(SAIL) artisan ide-helper:meta
