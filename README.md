# ContestManager

[![Build Status](https://travis-ci.org/GuillaumeTorres/contestmanager.svg?branch=master)](https://travis-ci.org/GuillaumeTorres/contestmanager)
[![Code Climate](https://codeclimate.com/github/GuillaumeTorres/contestmanager/badges/gpa.svg)](https://codeclimate.com/github/GuillaumeTorres/contestmanager)
[![Issue Count](https://codeclimate.com/github/GuillaumeTorres/contestmanager/badges/issue_count.svg)](https://codeclimate.com/github/GuillaumeTorres/contestmanager)
[![Test Coverage](https://codeclimate.com/github/GuillaumeTorres/contestmanager/badges/coverage.svg)](https://codeclimate.com/github/GuillaumeTorres/contestmanager/coverage)

[![Build Statu](http://contestmanager.ddns.net:8080/job/Contest%20Manager/badge/icon)](http://contestmanager.ddns.net:8080/job/Contest%20Manager/)

### Github app ###
 
```
https://github.com/GuillaumeTorres/contestmanagerapp
```

## Installation ##

```
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console assets:install
 ```

### Commandes utiles ###

```
php app/console doctrine:database:drop --force
php app/console server:run
php app/console doctrine:fixtures:load --no-interaction
php app/console cache:clear [--env=prod]
```

### Json Web Token ###

Générer les clés ssh
```
mkdir -p var/jwt
openssl genrsa -out var/jwt/private.pem -aes256 4096
openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```
Penser à changer 'jwt_key_pass_phrase' dans les paramètres

### Configuration host

```
 <VirtualHost *:80>
     ServerName contestmanager.local
     DocumentRoot /var/www/projects/contestmanager/web
     SetEnv APPLICATION_ENV "development"
     <Directory /var/www/projects/contestmanager/web>
         DirectoryIndex app_dev.php
         Options Indexes FollowSymLinks Includes ExecCGI
         AllowOverride All
         Order allow,deny
         Allow from all
     </Directory>
 </VirtualHost>
```
