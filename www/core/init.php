<?php
declare(strict_types=1);

session_start();
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Europe/Belgrade');
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'db',
        'port' => '3306',
        'username' => 'user',
        'password' => 'test',
        'db' => 'travel'
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

spl_autoload_register(
    function ($class) {
        include_once 'classes/' . $class . '.php';
    }
);
require_once 'functions/sanitize.php';
require_once 'functions/user_type.php';
