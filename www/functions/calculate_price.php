<?php
declare(strict_types=1);

require_once 'core/init.php';
function calculate_price_of_reservation($lista_kreveta, $lista_tipova, $clan_odr, $clan_deca, $broj_soba)
{
    $db = DB::getInstance();
    $aranzman = $db->get('aranzmani', array('aran_id', '=', Session::get('aran_id')))->first();
    $datum_polaska = new DateTime($aranzman->krece);
    $datum_povratka = new DateTime($aranzman->vraca);
    $smestaj_id = $aranzman->smestaj_id;
    $broj_zvezdica = $db->get('smestaj', array('smestaj_id', '=', $smestaj_id))->first()->br_zvezdica;
    $dani = $datum_polaska->diff($datum_povratka)->d;
    $cena_prevoza = (int) $db->get('prevoz', array('p_id', '=', $aranzman->p_id))->first()->cena;
    $cena = $cena_prevoza * (int) $clan_odr + (($cena_prevoza / 2) * (int) $clan_deca);
    for ($i = 0; $i < (int) $broj_soba; $i++) {
        $tip[] = '%' . $lista_tipova[$i] . '%';
        $soba_tip[] = $db->query('SELECT id, gen_cena FROM sobatip_hash WHERE LOWER(tip) LIKE ? AND br_kreveta = ?', array(strtolower($tip[$i]), $lista_kreveta[$i]))->first();
        $gen_cena = $soba_tip[$i]->gen_cena;
        $cena += ($gen_cena + (($gen_cena / 2) * ($broj_zvezdica - 3))) * $dani;
    }
    return $cena;
}
