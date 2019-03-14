git_sha := $(shell git rev-parse HEAD)
SHELL:=/bin/bash

.PHONY: coverage infection integration it test

it: test

coverage: vendor
	vendor/bin/phpunit --configuration=tests/Unit/phpunit.xml --coverage-text

#cs: vendor
#	vendor/bin/php-cs-fixer fix --config=.php_cs.dist --diff --verbose

infection: vendor
	vendor/bin/infection --min-covered-msi=80 --min-msi=80

dep-analyser-layer:
	bin/deptrac analyze depfile.layers.yml --formatter-graphviz=0

phpstan:
	vendor/bin/phpstan analyse src tests -l 7

tests: vendor
	$(MAKE) test-unit
	$(MAKE) test-integration
	$(MAKE) dep-analyser-layer
	$(MAKE) phpstan

test-unit: vendor
	vendor/bin/phpunit --configuration=tests/Unit/phpunit.xml --coverage-html=var/coverage/ --testdox --coverage-text

test-integration: vendor
	vendor/bin/phpunit --configuration=tests/Integration/phpunit.xml

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install

cs-fixer:
	vendor/bin/php-cs-fixer fix src
	vendor/bin/php-cs-fixer fix tests

skaffold-dev:
	skaffold dev -p dev

skaffold-run:
	_CI_COMMIT_SHA=$(git_sha) skaffold run

shell:
	kubectl exec -it $$(kubectl get pods --field-selector=status.phase=Running -o=jsonpath='{.items[0].metadata.name}' -l app=player-php) bash

shell-stadium:
	kubectl exec -it $$(kubectl get pods --field-selector=status.phase=Running -o=jsonpath='{.items[0].metadata.name}' -l app=stadium-php) bash

shell-ball:
	kubectl exec -it $$(kubectl get pods --field-selector=status.phase=Running -o=jsonpath='{.items[0].metadata.name}' -l app=ball-php) bash

port-forward:
	kubectl port-forward $$(kubectl get pods --field-selector=status.phase=Running -o=jsonpath='{.items[*].metadata.name}' -l app=v1-k8s-sport-php-app) 8002:80

gitlab-runner-test:
	git add . && git commit --amend --no-edit && gitlab-runner exec docker --docker-privileged  test

gitlab-runner-shell:
	docker exec -it $$(docker ps --format "{{.Names}}" | grep runner | grep build) sh

reset-game:
	@for pod in $$(kubectl get pods -o name | cut -d '/' -f2); do echo $$pod; kubectl exec $$pod -- rm /tmp/data.txt;done