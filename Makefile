git_sha := $(shell git rev-parse HEAD)

.PHONY: coverage infection integration it test

it: test

coverage: vendor
	vendor/bin/phpunit --configuration=tests/Unit/phpunit.xml --coverage-text

#cs: vendor
#	vendor/bin/php-cs-fixer fix --config=.php_cs.dist --diff --verbose

infection: vendor
	vendor/bin/infection --min-covered-msi=80 --min-msi=80

test: vendor
	vendor/bin/phpunit --configuration=tests/Unit/phpunit.xml
	vendor/bin/phpunit --configuration=tests/Integration/phpunit.xml

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install

skaffold-dev:
	skaffold dev -p dev

skaffold-run:
	_CI_COMMIT_SHA=$(git_sha) skaffold run

shell:
	kubectl exec -it $$(kubectl get pods -l app=v1-skaffold-ci-sandbox-php-app -o=jsonpath='{.items[0].metadata.name}') sh

gitlab-runner-test:
	git add . && git commit --amend --no-edit && gitlab-runner exec docker --docker-privileged  test

gitlab-runner-shell:
	docker exec -it $$(docker ps --format "{{.Names}}" | grep runner | grep build) sh
