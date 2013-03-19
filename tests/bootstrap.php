<?php 
define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT', realpath(__DIR__ . DS . '..'));
define('DIR', APP_ROOT);

date_default_timezone_set('America/Sao_Paulo');

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', DIR . DS . 'logs' . DS . date('Y-m-d') . '.log');

error_reporting(E_ALL | E_STRICT);

include_once( APP_ROOT . DS . 'vendor' . DS . 'autoload.php' );