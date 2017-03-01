Getting started with the libretro-netplay-registry
==================================================

This application's purpose is to serve as a list or api for RetroArch.
You can add an entry of your system to the registry through the RetroArch-interface. (If your system is supported)
When looking for netplay-lobbies in RetroArch, your system checks this api to see what lobbies are currently open.


Prerequisites
-------------

- PHP 5.6 or higher
- `Composer <https://getcomposer.org/download/>`_
- And the requirements that are checked with: ``php bin/symfony_requirements``
- A webserver such as Apache2, NGINX, ISS etc.


Installation
------------

IMPORTANT: For production installation read `this <#production-installation>`_.

1. ``git clone https://github.com/libretro/libretro-netplay-registry``
2. ``cd libretro-netplay-registry``
3. ``composer install`` During the installation, you can just skip through the prompts with enter-key.


Creating a vhost
----------------

Virtual hosts differ from system to system. Please consult Google or you local sysadmin for guidance.

Needed key-information:

- DocumentRoot is: ``libretro-netplay-registry/web``
- The ``libretro-netplay-registry/var/cache`` needs to be writable by the webserver-user.


Production installation
-----------------------

For a production environment consider this facts/guides:

- Use the latest PHP version for more speed and security.
- Use a PHP Accelerator such as APC.
- Install dependencies with optimized autoloader: ``composer dump-autoload --optimize --no-dev --classmap-authoritative``
- Delete the following files: ``/web/app_dev.php`` and ``/web/config.php``

Cron-Job
--------

Entries are currently not being cleared by the system who created it.
To cleanup on "older" entries (2 minutes or older) you have to run the following command: ``php bin/console app:entry:cleanup``.
This command will delete the entries which are older than 2 minutes.

To set this up automatically, you have to create a routine.
I'll cover cron, because the majority of servers have cron/acron installed.

Edit the cronjobs with running the following command:

``crontab -e``

Just add the following:

``* * * * * php /path/to/the/application/bin/console app:entry:cleanup``

This command will be executed every minute.
