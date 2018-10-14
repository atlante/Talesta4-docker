help: ## cran d'aide
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
build: ## Construction de l'image
	docker build -t agentcobra/talesta .
run: build ## Lance la compilation et l'image docker (garde le conteneur  l'arret)
	docker run -d --name talesta -p 8080:80 -v lieux:/var/www/html/lieux agentcobra/talesta
shell: ## Lance bash dans le conteneur
	docker exec -it talesta bash
test: build ## Lance la compilation et l'image docker (supprime le conteneur  l'arret)
	docker run --rm --name talesta -p 8080:80 -v lieux:/var/www/html/lieux agentcobra/talesta
