FROM dunglas/frankenphp

ENV SERVER_NAME=:80

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -eux; \
    install-php-extensions \
    @composer \
    pdo_pgsql \
    bcmath
#    apcu \
#    intl \
#    opcache \
#    zip \
#    gd;


# USER www-data

COPY --link composer.* symfony.* ./

RUN set -eux; composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress
#COPY --link . ./
COPY --link . /app


#RUN set -eux; \
	#mkdir -p var/cache var/log; \
	#composer dump-autoload --classmap-authoritative --no-dev; \
	#composer dump-env prod; \
	#composer run-script --no-dev post-install-cmd; \
	#chmod +x bin/console; sync;
