<?php
declare(strict_types=1);

require_once 'core/init.php';
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
require_once 'navbar.php';
$db = DB::getInstance();
$korisnik_id = $db->get('zaposleni', array('zaposleni_id', '=', Input::get('id')))->first()->korisnik_id;
$db->action('DELETE', 'korisnik', array('korisnik_id', '=', $korisnik_id));
Redirect::to('zaposleni.php');
