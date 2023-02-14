<?php

require_once 'core/init.php';
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
require_once 'navbar.php';
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check(
            $_POST,
            array(
            'password_current' => array(
                'required' => true,
                'min' => 6
            ),
            'password_new' => array(
                'required' => true,
                'min' => 8
            ),
            'password_new_again' => array(
                'required' => true,
                'min' => 8,
                'matches' => 'password_new'
            )
            )
        );
        if ($validation->passed()) {
            // change password
            if (!password_verify((Input::get('password_current')), $user->data()->password)) {
                echo 'Vasa trenutna sifra je pogresna.';
            } else {
                $table = User_type($user->permissionLevel());
                $user->update(
                    $table,
                    array(
                    'password' => password_hash(Input::get('password_new'), PASSWORD_BCRYPT)
                    )
                );
                Session::flash('home', 'Vasa sifra je promenjena!');
                Redirect::to('index.php');
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha512-3P8rXCuGJdNZOnUx/03c1jOTnMn3rP63nBip5gOP2qmUh5YAdVAvFZ1E+QLZZbC1rtMrQb+mah3AfYW11RUrWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    <script type="text/babel" src="scripts/changePass.js"></script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<link rel="stylesheet" href="styles/booking.css">
<script>var exports = {};</script>
    <title>Dobrodoslica</title>

</head>

<body>
<div id="booking" class="section" style="background-image: url('imgs/profile3.jpg');background-size: cover;background-attachment: fixed;">
    <div class="section-center">
        <div class="container">
            <div class="row">
                <div class="booking-form">
                    <div class="form-header">
                    <h1>Promena lozinke</h1>
                    </div>

<form action="" method="post">
<div class="row">
        <form method="post" id="oldP">
        <div class="form-group">
        <input type="password" class="input-lg form-control" name="password_current" id="password_current" placeholder="Trenutna Lozinka" autocomplete="off">
        </div>
</div>
<div class="row">
        <form method="post" id="newP">
        <div class="form-group">
        <input type="password" class="input-lg form-control" name="password_new" id="password_new" placeholder="Nova Lozinka" autocomplete="off">
    </div>
</div>
    <form method="post" id="confirmP">
    <div class="form-group">
    <input type="password" class="input-lg form-control" name="password_new_again" id="password_new_again" placeholder="Ponovite Lozinku" autocomplete="off">
    </div>
    <div class="form-btn" > <button class="submit-btn" type="submit"  onClick="validateForm()">PROMENI</button> </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    </form>
</div>
</div>

</body>