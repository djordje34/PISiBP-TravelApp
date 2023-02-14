<?php

require_once 'core/init.php';
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
$korisnik_id = $user->data()->korisnik_id;
$db = DB::getInstance();
$db->query("UPDATE `rezervacije` SET `korisnik_id` = ? WHERE `rezervacije`.`rez_id` = ?", array($korisnik_id, Input::get('id')));
Redirect::to('reservations.php');
