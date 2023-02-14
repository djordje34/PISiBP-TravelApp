<?php

require_once 'core/init.php';
$user = new User();
$update = false;
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
require_once 'navbar.php';
if (Input::exists('get')) {
    if (Input::get('id')) {
        $zaposleni_id = Input::get('id');
        $db = DB::getInstance();
        $zaposlen = $db->get('zaposleni', array('zaposleni_id', '=', $zaposleni_id))->first();
        $update = true;
    }
}
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        if ($update) {
            if ($db->query('UPDATE zaposleni SET ime=?, prezime=? WHERE zaposleni_id = ?', array(Input::get('ime'), Input::get('prezime'), $zaposleni_id))->error()) {
                throw new Exception('Doslo je do greske pri azuriranju podataka zaposlenog');
            }
        } else {
            try {
                $user->update(
                    User_type($user->permissionLevel()),
                    array(
                    'ime' => Input::get('ime'),
                    'prezime' => Input::get('prezime')
                    )
                );
                Session::flash('home', 'Your details have been updated.');
                Redirect::to('profil.php');
            } catch (Exception $e) {
                die($e->getMessage());
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<link rel="stylesheet" href="styles/booking.css">

<script type="text/babel" src="scripts/profileEditGrid.js"></script>
<script>var exports = {};</script>
    <title>Dobrodoslica</title>

</head>
<body onload="myFunction()">
<div id="booking" class="section" style="background-image: url('imgs/profile3.jpg');background-size: cover;background-attachment: fixed;">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="booking-form">
                    <div class="form-header">
                    <h1>Postavka profila</h1>
                    </div>
        
        <form method="post" id="profil" action="">
      <div class="row">
      <div class="col-md-6">
          <div class="form-group"> <input class="form-control" type="text" placeholder="$idIme" id="ime" name="ime"/> <span class="form-label">Ime</span> </div>
      </div>
      <div class="col-md-6">
          <div class="form-group"> <input class="form-control" type="text" placeholder="$idPrezime" id="prezime" name="prezime"/> <span class="form-label">Prezime</span> </div>
      </div>
  </div>
          
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <div id = "personal" style="">
      

        </div>
        </form>


        </div>
        </div>
        </div>
    </section>
<script>
    function myFunction(){
        let x = document.getElementById('ime').value=" <?php
            if ($update) {
                echo $zaposlen->ime;
            } else {
                echo $user->data()->ime;
            }
        ?>";
        let y = document.getElementById('prezime').value=" <?php
        if ($update) {
            echo $zaposlen->prezime;
        } else {
            echo $user->data()->prezime;
        }
        ?>";
    }
</script>
</body>


</html>
