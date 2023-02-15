<?php
declare(strict_types=1);

require_once 'core/init.php';
require_once 'functions/calculate_price.php';
if (isset($_POST['proveriCenu'])) {
    $cena = calculate_price_of_reservation(Input::get('listakreveta'), Input::get('listatipova'), Input::get('clan_odr'), Input::get('clan_deca'), Input::get('broj_soba'));
    echo "€" . $cena;
}
