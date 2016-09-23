Cakephp 2.x skeleton
====================

Introduction
------------
CakePHP 2.x application skeleton. Features Composer, Ant build script
(Jenkins php-template compatible http://jenkins-php.org/) for quality
assurance.

Installation
------------

Using Composer (The Right Way)
----------------------------
The way to get a working copy of this project is to clone the repository
and use `composer` to install dependencies using the `create-project` command:

    curl -s https://getcomposer.org/installer | php --
    php composer.phar create-project -sdev eimanavicius/cakephp-skeleton path/to/install

Alternately, clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git@github.com:eimanavicius/cakephp-skeleton.git
    cd cakephp-skeleton
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Another alternative for downloading the project is to grab it via `curl`, and
then pass it to `tar`:

    cd my/project/dir
    curl -#L https://github.com/eimanavicius/cakephp-skeleton/tarball/master | tar xz --strip-components=1

You would then invoke `composer` to install dependencies per the previous
example.

Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    ./bin/cake -app app server -H 0.0.0.0 -p 8080

OR

    php -S 0.0.0.0:8080 -t app/webroot/ app/webroot/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup apache, setup a virtual host to point to the app/webroot/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName cakephp-skeleton.localhost
        DocumentRoot /path/to/cakephp-skeleton/app/webroot
        <Directory /path/to/cakephp-skeleton/app/webroot>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
