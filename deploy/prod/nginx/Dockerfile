# Stage 1: tomamos el public/ ya generado desde la imagen de la app
FROM ghcr.io/penarrocha/myshop-app:prod AS app_public

# Stage 2: nginx runtime
FROM nginx:1.27-alpine

COPY deploy/prod/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=app_public /var/www/html/public /var/www/html/public
