.PHONY: rr caddy bash stop

NETWORK_NAME=dev
PROJECT_DIR=$(shell pwd)
UID=$(shell id -u)
GID=$(shell id -g)

rr:
	docker run --rm -d \
		--name boilerplate-api \
		--net $(NETWORK_NAME) \
		-w /app/boilerplate-api \
		-v $(PROJECT_DIR):/app \
		berrymore/php:8.4-dev

caddy:
	docker run --rm -d \
		--name boilerplate-caddy \
		--net $(NETWORK_NAME) \
		-p 80:80 -p 443:443 \
		-v $(PROJECT_DIR)/Caddyfile:/etc/caddy/Caddyfile \
		caddy:2

bash:
	docker run --rm -it -w /app -v $(PROJECT_DIR):/app --net $(NETWORK_NAME) -u$(UID):$(GID) berrymore/php:8.4-dev bash

stop:
	-docker stop boilerplate-api
	-docker stop boilerplate-caddy
