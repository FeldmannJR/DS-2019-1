#!/bin/bash

rm -rf /etc/supervisord/conf.d/*
if [ "$WATCH_ASSETS" == 1 ]; then
    cp /etc/supervisor/programs/app-watch.conf /etc/supervisor/conf.d/ 
fi

exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
