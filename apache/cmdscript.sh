#!/bin/bash
set -x 

cd /var/www/app/
npm install
/usr/sbin/apache2ctl -D FOREGROUND & npm run watch
