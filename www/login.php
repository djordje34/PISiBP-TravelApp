<?php

require_once 'core/init.php';
require_once 'navbar.php';
if (Session::exists(Config::get('session/session_name'))) {
    Redirect::to('index.php');
}
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check(
            $_POST,
            array(
            'email' => array('required' => true),
            'password' => array('required' => true,
                                'min' => 8)
            )
        );
        if ($validation->passed()) {
            $user = new User();
            $login = $user->login(Input::get('email'), Input::get('password'));
            if ($login) {
                Redirect::to('index.php');
            } else {
                echo '<p>Zao nam je, login je neuspesan';
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
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="styles\forms.css">
<link rel="stylesheet" href="styles\combined.css">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

    <title>Log-in</title>
</head>
<body>
<section class="vh-100 bg-image"
  style="background-image: url('imgs/background.jpg');background-size: cover;">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-7 col-md-7 col-lg-7 col-xl-7">
          <div class="card" style="border:none;background-color: rgba(253, 228, 156, 0.2);">
            <div class="card-body p-5 ">
              <h2 class="text-uppercase text-center mb-4">Prijava</h2>

              <form method="post" id="root">
                <div id = "input">

                </div>

                  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                <div class="d-flex justify-content-center">
                  <input type="submit"
                    class="btn btn-success btn-block btn-lg gradient-custom-4 text-body proceed" value="Uloguj me">
                </div>

                <p class="text-center text-muted mt-4 mb-0">Nemate nalog? <a href="register.php"
                    class="fw-bold text-body"><u>Registrujte se</u></a></p>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/babel" src="scripts/formsGenerator.js"></script>
</body>
</html>
