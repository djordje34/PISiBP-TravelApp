<?php 
require_once 'navbar.php';
require_once 'core/init.php';
$user = new User();
if(!$user->isLoggedIn()) {
	Redirect::to('landing.php');
}
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
			try {
				$user->update(array(
					'ime' => Input::get('ime'), 
          'prezime' => Input::get('prezime'), 
          'adresa' => Input::get('adresa'), 
          'br_kartice' => Input::get('kartica'), 
				));
				Session::flash('home', 'Your details have been updated.');
				Redirect::to('landing.php');
			}catch(Exception $e) {
				die($e->getMessage());
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
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<link rel="stylesheet" href="combined.css">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script>var exports = {};</script>
    <title>Dobrodoslica</title>

</head>
<body>
<section class="vh-100 bg-image"
  style="background-image: url('imgs/profilebg.jpg');background-size: cover;background-attachment: fixed;">
  <div class="mask d-flex align-items-center h-100 backgroundable2">
        <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100 packer ">
        
        <form method="post" id="profil" action="" class = "p-5 d-flex flex-column align-items-center">
          
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <h4>Postavke profila</h4>
        <div id = "personal" style="" class = "m-5 mt-0 mb-0 w-50 d-flex flex-column d-flex justify-content-center flex-row"> 
      

        </div>
        </form>


        </div>
        </div>
        </div>
    </section>
<script type="text/babel" src="scripts/profileEditGrid.js"></script>
</body>


</html>