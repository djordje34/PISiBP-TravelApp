<?php 
require_once 'core/init.php';
if (isset($_POST['proveriCenu'])) {
    $db = DB::getInstance();
    $lista_kreveta = (Input::get('listakreveta'));
    $lista_tipova = (Input::get('listatipova'));
    $aranzman = $db->get('aranzmani', array('aran_id', '=', Session::get('aran_id')))->first();
    $datum_polaska = new DateTime($aranzman->krece);
    $datum_povratka = new DateTime($aranzman->vraca);
    $smestaj_id = $aranzman->smestaj_id;
    $broj_zvezdica = $db->get('smestaj', array('smestaj_id', '=', $smestaj_id))->first()->br_zvezdica;
    $dani = $datum_polaska->diff($datum_povratka)->d;
    $cena_prevoza = (int) $db->get('prevoz', array('p_id', '=', $aranzman->p_id))->first()->cena;
                $cena = $cena_prevoza * (int) Input::get('clan_odr') + (($cena_prevoza / 2) * (int) Input::get('clan_deca'));
                for ($i = 0; $i < (int) Input::get('broj_soba'); $i++) {
                    $tip[] = '%' . $lista_tipova[$i] . '%';
                    $soba_tip[] = $db->query('SELECT id, gen_cena FROM sobatip_hash WHERE LOWER(tip) LIKE ? AND br_kreveta = ?', array(strtolower($tip[$i]), $lista_kreveta[$i]))->first();
                    $soba_id[] = $db->query('SELECT soba_id FROM `soba` WHERE tip=? AND smestaj_id = ? AND rez_id IS NULL;', array($soba_tip[$i]->id, $smestaj_id))->first();
                    $gen_cena = $soba_tip[$i]->gen_cena;
                    $cena += ($gen_cena + (($gen_cena / 2) * ($broj_zvezdica - 3))) * $dani;
                }
                echo $cena;
}