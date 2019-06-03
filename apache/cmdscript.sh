#!/bin/bash
set -m 

cd /var/www/app/
/usr/sbin/apache2ctl -D FOREGROUND & npm run watch
fg %1