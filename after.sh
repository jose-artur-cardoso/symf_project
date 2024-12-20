#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.
#
# If you have user-specific configurations you would like
# to apply, you may also create user-customizations.sh,
# which will be run after this script.


# If you're not quite ready for the latest Node.js version,
# uncomment these lines to roll back to a previous version

# Remove current Node.js version:
#sudo apt-get -y purge nodejs
#sudo rm -rf /usr/lib/node_modules/npm/lib
#sudo rm -rf //etc/apt/sources.list.d/nodesource.list

# Install Node.js Version desired (i.e. v13)
# More info: https://github.com/nodesource/distributions/blob/master/README.md#debinstall
#curl -sL https://deb.nodesource.com/setup_13.x | sudo -E bash -
#sudo apt-get install -y nodejs

#!/bin/bash

DB_NAME="homestead"
DB_USER="homestead"
DB_PASS="secret"

cd symf_project
# Run composer install
echo "Running composer install..."
composer install --ignore-platform-req=php
echo "Composer install completed successfully."

# Run npm install
echo "Running yarn install..."
yarn
echo "yarn install completed successfully."

# Run npm build
echo "Running yarn encore dev..."
yarn encore dev
echo "yarn encore dev completed successfully."

# Symfony commands for database setup

echo "Running Symfony commands for database setup..."
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:update --force
#php bin/console doctrine:fixtures:load --no-interaction
echo "Symfony commands executed successfully."

