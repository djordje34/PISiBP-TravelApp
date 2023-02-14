<?php

require_once 'core/init.php';
if (Input::get('aran_id')) {
    Session::put('aran_id', Input::get('aran_id'));
}
if (!Input::get('aran_id') && !isset($_POST['booking'])) {
    Redirect::to('ponude.php');
}
if (isset($_POST['booking'])) {
    $lista_kreveta = (Input::get('listakreveta'));
    $lista_tipova = (Input::get('listatipova'));
        $validate = new Validate();
        $validation = $validate->check(
            $_POST,
            array(
            'ime' => array('required' => true),
            'prezime' => array('required' => true),
            'email' => array('required' => true),
            'kartica' => array('required' => true),
            'kontakt' => array('required' => true),
            'clan_odr' => array('required' => true, 'numeric' => true),
            'clan_deca' => array('numeric' => true)
            )
        );
        if ($validation->passed()) {
            try {
                $db = DB::getInstance();
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
                if (
                    !$db->insert(
                        'rezervacije',
                        array(
                        'ime' => Input::get('ime'),
                        'prezime' => Input::get('prezime'),
                        'br_kartice' => Input::get('kartica'),
                        'kontakt' => Input::get('kontakt'),
                        'email' => Input::get('email'),
                        'broj_odr' => (int)Input::get('clan_odr'),
                        'broj_dece' => (int) Input::get('clan_deca'),
                        'broj_soba' => (int) Input::get('broj_soba'),
                        'kom' => Input::get('komentar'),
                        'cena' => $cena,
                        'aran_id' => Session::get('aran_id')
                        )
                    )
                ) {
                    throw new Exception('Desila se greska pri pravljenju rezervacije.');
                }
                $rezervacija_id = $db->query('SELECT rez_id FROM rezervacije WHERE ime = ? AND prezime = ? AND br_kartice = ? AND email = ? AND broj_odr = ? AND broj_dece = ? AND cena = ? AND kom = ? AND kontakt = ? AND broj_soba = ? AND aran_id = ? AND korisnik_id IS NULL', array(Input::get('ime'), Input::get('prezime'), Input::get('kartica'), Input::get('email'), Input::get('clan_odr'), Input::get('clan_deca'), $cena, Input::get('komentar'), Input::get('kontakt'), (int) Input::get('broj_soba'), (int) Session::get('aran_id')))->first()->rez_id;
                for ($i = 0; $i < (int) Input::get('broj_soba'); $i++) {
                    if ($db->query('UPDATE `soba` SET `rez_id` = ? WHERE `soba`.`soba_id` = ?;', array($rezervacija_id, $soba_id[$i]->soba_id))->error()) {
                        Session::delete('aran_id');
                        throw new Exception('Desila se greska pri rezervisanju soba.');
                    }
                }
            } catch (Exception $e) {
                die($e->getMessage());
            }
            Session::delete('aran_id');
            echo $cena;
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
}
require_once 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="scripts/bootstrap.min.js"></script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="styles/booking.css">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script type="text/babel" src="scripts/bookingGrid.js"></script>
<script src="script.js"></script>
<script>var exports = {};</script>

    <title>Dobrodoslica</title>

</head>
<body>
<div id="booking" class="section" style="background-image: url('imgs/profile1.jpg');background-size: cover;background-attachment: fixed;">
    <div class="section-center">
        <div class="container">
            <div class="d-flex flex-row">
            
                <div class="booking-form">
                    <div class="form-header">
                    <h1>Prijava aranzmana</h1>
                    </div>
           
        
        <form method="post" id="profil" action="">
        <div id = "personal" style="">
        </div>
        </div>
        <div class="booking-form">
        <div id='appendForms' class='appendForms w-100'>
        </div>
        <div id='ukupna_cena'>

        </div>

</div>
        </div>
        </div>
        
        </form>
    </section>

</body>
</html>
