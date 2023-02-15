<?php
declare(strict_types=1);

require_once 'core/init.php';
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
require_once 'navbar.php';
$db = DB::getInstance();
$db->action('DELETE', 'aranzmani', array('aran_id', '=', Input::get('id')));
Redirect::to('aranzmani.php');
