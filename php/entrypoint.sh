#!/bin/sh
cron -L 15
docker-php-entrypoint php-fpm
