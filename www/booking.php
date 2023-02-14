<?php

require_once 'core/init.php';
require_once 'navbar.php';
if (Input::get('aran_id')) {
    Session::put('aran_id', Input::get('aran_id'));
}
if (Input::get('booking') == 1) {
    Redirect::to(404);
}
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check(
            $_POST,
            array(
            'ime' => array('required' => true),
            'prezime' => array('required' => true),
            'email' => array('required' => true),
            'kartica' => array('required' => true),
            'kontakt' => array('required' => true),
            'clanovi_odrasli' => array('required' => true, 'numeric' => true),
            'clanovi_deca' => array('numeric' => true)
            )
        );
        if ($validation->passed()) {
            try {
                $db = DB::getInstance();
                $br_clan = (int)Input::get('clanovi_deca') + (int) Input::get('clanovi_odrasli');
                //$aran_cena = $db->action('SELECT cena', 'aranzmani', array('aran_id', '=', Session::get('aran_id')))->first()->cena;
                //$cena = $br_clan * (int) Input::get('br_soba') * $aran_cena;//TODO
                if (
                    !$db->insert(
                        'rezervacije',
                        array(
                        'ime' => Input::get('ime'),
                        'prezime' => Input::get('prezime'),
                        'br_kartice' => Input::get('kartica'),
                        'kontakt' => Input::get('kontakt'),
                        'email' => Input::get('email'),
                        'broj_odr' => (int)Input::get('clanovi_deca'),
                        'broj_dece' => (int) Input::get('clanovi_odrasli'),
                        'kom' => Input::get('komentar'),
                        'cena' => 1,
                        'aran_id' => Session::get('aran_id')
                        )
                    )
                ) {
                    throw new Exception('There making this reservation.');
                }
            } catch (Exception $e) {
                die($e->getMessage());
            }
            Session::delete('aran_id');
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
//if (!Input::get('aran_id')) {
//    Redirect::to('ponude.php');
//}
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
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <div id = "personal" style="">
        </div>
        </div>
        <div id='appendForms' class='appendForms w-100'>

</div>
        </div>
        </div>
        
        </form>
    </section>

</body>
</html>
