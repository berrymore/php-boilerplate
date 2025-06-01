docker run --rm -d -v $(pwd)/Caddyfile:/etc/caddy/Caddyfile -p 80:80 -p 443:443 --net dev --name boilerplate-caddy caddy:2
