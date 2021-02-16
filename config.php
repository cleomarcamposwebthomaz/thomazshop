<?php

$script_name = (explode('/', $_SERVER['SCRIPT_NAME']))[1];


// HTTP
define('HTTP_SERVER', 'http://'.$_SERVER['HTTP_HOST'].'/'.$script_name.'/');


// HTTPS
define('HTTPS_SERVER', 'http://'.$_SERVER['HTTP_HOST'].$script_name);

// DIR
define('DIR_APPLICATION', __DIR__. '/catalog/');
define('DIR_SYSTEM', __DIR__. '/system/');
define('DIR_IMAGE', __DIR__. '/image/');
define('DIR_STORAGE', __DIR__. '/storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'thomazshop');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');
