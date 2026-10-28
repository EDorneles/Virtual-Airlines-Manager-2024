# Virtual Airlines Manager 2024

Fork of VAM 2.6 with additional Portuguese pages and modules.

## Deploy on Plesk / HelioHost

1. Deploy the repository so `index.php`, `vam/` and `vamcore/` are siblings. Set the domain's document root to that directory (for example, `httpdocs/VAM`).
2. In **Websites & Domains → PHP**, select **PHP 8.5.12** and **FastCGI application** as shown in the supplied Plesk screenshot. `.user.ini` sets display errors off and keeps the upload and timeout limits aligned with the screenshot.
3. Enable the PHP extensions `mysqli`, `pdo_mysql`, `mbstring`, `openssl`, `curl`, `gd`, `fileinfo` and `zip` if they are available in the subscription.
4. Create a MySQL/MariaDB database and database user in Plesk. Follow [database/README.md](database/README.md) to import the schema and starter data.
5. Copy `private/vam-db.example.php` to `private/vam-db.php` and enter the Plesk database host, database name, user and password. `private/vam-db.php` is ignored by Git and the `private/` directory denies HTTP access.
6. Make `vam/uploads/` and `vamcore/app/tmp/` writable by the website subscription user. Use the narrowest permissions Plesk allows; do not make the whole project world-writable.
7. Configure the sender address in `vamcore/app/Config/email.php`, then change the seeded administrator password before opening registration to pilots.

The same database settings feed the plain PHP pages and CakePHP's database connection. You can instead set `VAM_CONFIG_FILE` to an absolute path outside the document root, or define `VAM_DB_HOST`, `VAM_DB_NAME`, `VAM_DB_USER`, and `VAM_DB_PASSWORD` in the PHP application's environment.

## PHP compatibility

The old VAM source used APIs removed from modern PHP. This repository now routes legacy FSACARS database calls through a `mysqli` compatibility layer, uses external configuration for database credentials, and has a CakePHP database configuration file for Plesk. The included CakePHP 2 code and some unfinished custom modules are still legacy code, so treat PHP 8.5.12 as a staging target and verify the site's public pages, login, admin panel, uploads, mail and ACARS integrations in Plesk before relying on it.

## Security note

Earlier commits in this repository contained database credentials. Removing them from the current files does not remove them from Git history. Rotate that database password at the provider before using the repository, and purge the old credential from history before making the repository public.
