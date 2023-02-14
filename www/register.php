<?php

require_once 'core/init.php';
$user = new User();
if ($user->permissionLevel() != 2) {
    Redirect::to('index.php');
}
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check(
            $_POST,
            array(
            'password' => array(
                'required' => true,
                'min' => 8
            ),
            'email' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
                'unique' => 'zaposleni'
            )
            )
        );
        if ($validation->passed()) {
            $user = new User();
            try {
                $db = DB::getInstance();
                if (
                    !$db->insert(
                        'korisnik',
                        array(
                        'tip' => 1)
                    )
                ) {
                    throw new Exception('There was a problem creating this account.');
                }
                $id = $db->query('SELECT * FROM korisnik ORDER BY korisnik_id DESC LIMIT 1')->first()->korisnik_id;
                $user->create(
                    'zaposleni',
                    array(
                    'email' => Input::get('email'),
                    'password' => password_hash(Input::get('password'), PASSWORD_BCRYPT),
                    'korisnik_id' => $id
                    )
                );
                Session::flash('home', 'You are registered and can log in');
                Redirect::to('login.php');
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
require_once 'navbar.php';
?>
-->
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
<link rel="stylesheet" href="styles\bootstraped.css">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>


<script src="usernameChecker.js"></script>




    <title>Registracija</title>


    <script>

$(document).ready(function(){
    
   $("#username").keyup(function(){
      var username = $(this).val().trim();
    console.log(username);
      if(username != ''){

         $.ajax({
            url: 'server.php',
            type: 'post',
            data: {username: username},
            success: function(response){
                $('#uname_response').html(response);
                
             }
         });
      }
      else{
         $('#uname_response').html("");
      }

    })

 });

    </script>
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
              <h2 class="text-uppercase text-center mb-4">Pridružite nam se</h2>

              <form method="post">
                <div id="input"></div>

                <input type="hidden" value="<?php echo Token::generate(); ?>" name='token'/>
                <div class="d-flex justify-content-center">
                  <input type="submit"
                    class="btn btn-success btn-block btn-lg gradient-custom-4 text-body proceed" value="Registruj me">
                </div>

                <p class="text-center text-muted mt-4 mb-0">Imate nalog? <a href="login.php"
                    class="fw-bold text-body"><u>Ulogujte se</u></a></p>

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
