<?php

date_default_timezone_set('UTC');

define('APP_NAME', 'Hub');
define('APP_SCHEME', 'http');
define('APP_URL', 'APP URL');
define('APP_PATH', '/');
define('ASSET_PATH', '/assets');
define('FORCE_SSL', false);

define('SECURE_AUTH', true);
define('BCRYPT_COST', 12);
define('PASSWORD_MIN', 6);
define('PASSWORD_MAX', 30);
define('SESSION_TIMEOUT', 172800);
define('SESSION_SECRET', 'CHANGEME!');
define('SESSION_FORCE_SSL', false);

putenv('DB_TYPE=mysql');
putenv('DB_NAME=DB_NAME');
putenv('DB_HOST=localhost');
putenv('DB_USER=DB_USER');
putenv('DB_PASS=DB_PASS');

define('CJDNS_API_PASSWORD', 'CJDNS ADMIN API KEY HERE');
define("CJDNS_API_HOST", "127.0.0.1");
define("CJDNS_API_PORT", 10010);

// Fixme: pull latest version from official git repo
define("CJDNS_LATEST", 16);