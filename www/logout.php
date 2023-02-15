<?php
declare(strict_types=1);

require_once 'core/init.php';

$user = new User();
$user->logout();

Redirect::to('login.php');
