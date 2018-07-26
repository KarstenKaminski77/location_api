<?php

// Define database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', '12345678');
define('DB_DATABASE', 'locations');

// Define email settings.
define('EMAIL_HOST', 'www27.jnb1.host-h.net');
define('EMAIL_USERNAME', 'test@kwd.co.za');
define('EMAIL_PASSWORD', 'K4rsten001!');
define('EMAIL_ADDRESS', 'test@kwd.co.za');
define('EMAIL_PORT', 587);

// Define Foursquare Client Id & Secret
define('CLIENT_ID', '4KWX3VK1GMEN2T2555WBX0RRYDJKA2F5EQYGYCOIKTHTZ2P4');
define('CLIENT_SECRET', '5VYKYYAYTREGWPFLF1JXBU5HHQTE0VTWAL2ZKKRLWOCG4TL0');

// Define Flickr API Key & Secret
define('FLICKR_API_KEY', '11b3a3835805e2ba7a58e647b0cbb334');
define('FLICKR_SECRET', '5c41b2cb44402d93');

// Define the directory sepearator
define('DS', DIRECTORY_SEPARATOR);

// Define Absolute URL's
define('HOST', 'http://' . $_SERVER['HTTP_HOST']  . '/');
define('PATH', 'location_api/');
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');

define('CSS', HOST . PATH . 'webroot/css/');
define('JS', HOST . PATH . 'webroot/js/');
define('IMG', HOST . PATH . '/webroot/images/');
define('CLASSES', HOST .  PATH . 'classes/');
define('TEMPLATES', ROOT . PATH . 'templates/');

 ?>
