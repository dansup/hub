<?php
/* File:	config.inc.php - v 1.0
 * HQ - The Outreach App
 * Required: PHP 5.5+, php-gd, php-mcrypt
 * Created: March 17/2014
 * Last Edited:	today
 * Todo: 
 */
date_default_timezone_set('UTC');

define('debug_mode', false);
define('maintenance_mode', false);
define('maintenance_msg', 'We are doing some maintenance, the site will brb.');

// App settings
define('appname', 'Hub');
// http or https
define('appScheme', 'http');
// No trailing slash or scheme
define('appUrl', 'APP URL');
// Add trailing slash
define('appDir', '/');
// No trailing slash
define('assetsUrl', '/assets');
define('secureAuth', true);
define('bcryptCost', 12);
define('passwordMinlength', 6);
define('passwordMaxlength', 30);
//define('sTimeout', 172800);
define('sTimeout', 172800);

// Datebase settings

// Database Type. <3 PDO. Supported: mysql
define('dbtype', 'mysql');
// Database Name
define('dbname', 'DB NAME');
// Binding mysql to localhost + restricting access to localhost only. Also do mysql_secure_installation
define('dbhost', 'DB HOST');
// Database User. The users with priviledges for dbname.
define('dbuser', 'DB USER');
// Ultra-secure database password that wasn't generated with phpmyadmin. *nervous laugh*
define('dbpass', 'DB PASSWORD');

// Mail settings
define('use_sendmail', true);
define('allow_alerts', true);
define('allow_outbound', true);
define('allow_inbound', false);
define('site_email', '');


// Gearman queue server. 
define('gearman_enabled', false);
define('gearman_ip', '');
define('gearman_port', '');
// define('gearman_options', $gearmanoptions);

define('CJDNS_API_KEY', 'API KEY HERE');