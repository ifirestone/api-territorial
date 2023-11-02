#!/bin/bash
cd $WORK_DIR

if [ $FRESH_INSTALL ]
then
    echo 'FRESH_INSTALL variable is set'
    echo "Starting fresh install migration"
    php artisan migrate:refresh -n --force
    # php artisan passport:install -n --force

    echo "Start Seeding"
    php artisan db:seed -n --force
else
    echo "Starting to migrate database with no fresh install..."
    php artisan migrate -n --force
    # php artisan passport:install -n --force
fi

echo "Starting Supervisor"
/usr/bin/supervisord -c /etc/supervisord.conf
