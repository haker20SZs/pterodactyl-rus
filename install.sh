#!/bin/bash

apt update -y
apt upgrade -y
apt autoremove -y
apt install unzip -y

cd /var/www/pterodactyl

unzip gg.zip -A
yarn add react-feather
php artisan migrate

sudo chown -R www-data:www-data /var/www/pterodactyl
php artisan down
npm i -g yarn
yarn install
#export NODE_OPTIONS=--openssl-legacy-provider
yarn build:production
php artisan view:clear
php artisan up