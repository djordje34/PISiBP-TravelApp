<?php

require_once 'core/init.php';
$user = new User();
$update = false;
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
require_once 'navbar.php';
require_once 'functions/prevoz_prevod.php';
if (Input::exists('get')) {
    if (Input::get('id')) {
        $aran_id = Input::get('id');
        $db = DB::getInstance();
        $aranzman = $db->get('aranzmani', array('aran_id', '=', $aran_id))->first();
        $update = true;
    }
}
if (Input::exists('post')) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check(
            $_POST,
            array(
            'ime' => array(
                'required' => true
            ),
            'napomena' => array(
                'required' => true
            ),
            'starting_date' => array(
                'required' => true
            ),
            'return_date' => array(
                'required' => true
            ),
            'prevoz' => array(
                'required' => true
            ),
            'smestaj' => array(
                'required' => true
            )
            )
        );
        if ($validation->passed()) {
            try {
                $db = DB::getInstance();
                if ($update) {
                    if (
                        !$db->query('UPDATE aranzmani SET naziv=?, krece=?, vraca=?, nap=?, smestaj_id=?, p_id=? WHERE aran_id=?', array(
                            Input::get('ime'),
                            Input::get('starting_date'),
                            Input::get('return_date'),
                            Input::get('napomena'),
                            Input::get('smestaj'),
                            Input::get('prevoz'),
                            $aran_id
                            ))
                    ) {
                        throw new Exception('There was a problem updating this aranzman.');
                    }
                } elseif (
                    !$db->insert(
                        'aranzmani',
                        array(
                        'naziv' => Input::get('ime'),
                        'krece' => Input::get('starting_date'),
                        'vraca' => Input::get('return_date'),
                        'nap' => Input::get('napomena'),
                        'smestaj_id' => Input::get('smestaj'),
                        'p_id' => Input::get('prevoz'),
                        )
                    )
                ) {
                    throw new Exception('There was a problem adding this aranzman.');
                }
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>'; //TODO: Make this look nice
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="combined.css">
<link rel="stylesheet" href="styles/booking.css">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script>var exports = {};</script>
    <title>Dobrodoslica</title>
</head>
<body>
<div id="booking" class="section" style="background-image: url('imgs/profile2.jpg');background-size: cover;background-attachment: fixed;">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="booking-form">
                    <div class="form-header">
                    <h1>Dodavanje aranzmana</h1>
                    </div>
        <form method="post" id="profil" action="">
        <div class="form-group"> <input class="form-control" type="text" placeholder="Ime Aranzmana"  id="ime" name="ime" value="<?php if ($update) echo $aranzman->naziv;?>"/> <span class="form-label">Naziv</span> </div>

        <div class="form-group"> <input class="form-control" type="text" placeholder="Napomena" id="napomena" name="napomena" value="<?php if ($update) echo $aranzman->nap;?>"/> <span class="form-label">Napomena</span> </div>

        <div class="row">
    <div class="col-md-6">
        <div class="form-group"> <input class="form-control" type="date" id="starting_date" name="starting_date" value="" required/> <span class="form-label"><?php if ($update) echo $aranzman->krece;?></span> </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"> <input class="form-control" type="date" id="return_date" name="return_date" value="" required/> <span class="form-label"><?php if ($update) echo $aranzman->vraca;?></span> </div>
    </div>
</div>

<div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Izbor prevoznika</label>
                                    <div class="form-field">
                                      <div class="select-wrap">
                              <div class="icon"><span class="fas fa-arrow-down"></span></div>
                              <select name="prevoz" id="prevoz" class="form-control">
                              <?php
                                $db = DB::getInstance();
                                $lista_prevoza = $db->query('SELECT * FROM prevoz')->results();
                                foreach ($lista_prevoza as $prevoz) {
                                    if ($update && $prevoz->p_id == $aranzman->p_id) {
                                        echo '<option class=option value="' . $prevoz->p_id . '" selected>' . prevoz_prevod($prevoz->tip) . ' - ' . $prevoz->ime_komp . '</option>';
                                    } else {
                                        echo '<option class=option value="' . $prevoz->p_id . '">' . prevoz_prevod($prevoz->tip) . ' - ' . $prevoz->ime_komp . '</option>';
                                    }
                                }
                                ?>
                              </select>
                            </div>
                              </div>
                          </div>
                            </div>

                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Izbor smestaja</label>
                                    <div class="form-field">
                                      <div class="select-wrap">
                              <div class="icon"><span class="fas fa-arrow-down"></span></div>
                              <select name="smestaj" id="smestaj" class="form-control">
                              <?php
                                $db = DB::getInstance();
                                $lista_smestaja = $db->query('SELECT smestaj_id, naziv, adresa FROM `smestaj` ORDER BY `smestaj`.`naziv` ASC')->results();
                                foreach ($lista_smestaja as $smestaj) {
                                    if ($update && $smestaj->smesstaj_id == $aranzman->smestaj_id) {
                                        echo '<option class=option value="' . $smestaj->smestaj_id . '" selected>' . $smestaj->naziv . ' - ' . $smestaj->adresa . '</option>';
                                    } else {
                                        echo '<option class=option value="' . $smestaj->smestaj_id . '">' . $smestaj->naziv . ' - ' . $smestaj->adresa . '</option>';
                                    }
                                }//TODO search select
                                ?>
                              </select>
                            </div>
                              </div>
                          </div>
                            </div>

<div class="form-btn"> <button class="submit-btn">
    <?php
    if ($update) {
        echo "Azuriraj aranzman";
    } else {
        echo "Dodaj aranzman";
    }
    ?>
</button> </div>

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        
        <div id = "personal" style="">
        </div>
        </form>
        </div>
        </div>
        </div>
    </section>
</body>
</html>
