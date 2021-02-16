<?php
ini_set('display_errors', true);

function pr($string) {
	echo '<pre>';
		print_r($string);
	echo '</pre>';
}

// Version
define('VERSION', '3.0.3.3');
define('OPENCART_BRASIL', '1.5.0');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit();
}

// VirtualQMOD
require_once('./vqmod/vqmod.php');
VQMod::bootup();

// VQMODDED Startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

start('catalog');
