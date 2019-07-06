#!/bin/bash
set -x 

cd /var/www/app/
npm install
cron
/usr/sbin/apache2ctl -D FOREGROUND 