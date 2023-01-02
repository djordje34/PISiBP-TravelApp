
<?php 
require_once 'core/init.php';
require_once 'navbar.php';
if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'korisnik'
            ),
            'password' => array(
                'required' => true,    
                'min' => 6
            ),
            'email' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));
        if($validation->passed()){
            $user = new User();
            try{
                $user->create(array(
                    'email' => Input::get('email'),
                    'password' => password_hash(Input::get('password'), PASSWORD_BCRYPT),
                    'username' => Input::get('username')
                ));
                $db=DB::getInstance();
                $id=get_object_vars($db->get('korisnik', array('username','=',Input::get('username')))->first())['korisnik_id'];//DONT ASK ME 
                if(!$db->insert('kupac', array('kupac_id'=>$id))) {//TODO: This is cringe
                  throw new Exception('There was a problem creating this account.');
                }
                Session::flash('home','You are registered and can log in');
                Redirect::to('login.php');
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }
        else
        {
            foreach($validation->errors() as $error){
                echo $error, '<br>'; //TODO: Make this look nice
            }
        }
    }
}
?>
<!--   
64C9CF
FDE49C
FFB740
DF711B
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<link rel="stylesheet" href="forms.css">
<link rel="stylesheet" href="combined.css">
<link rel="stylesheet" href="bootstrapped.css">
<script src="usernameSchecker.js"></script>



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
                <?php include('errors.php');?>
                <div class="form-outline m-5 mb-3">
                  <input size="15" type="text" id="username" name="username" class="form-control form-control-lg" value="<?php echo escape(Input::get('username')); ?>"/>
                  <label class="form-label" for="form3Example1cg">Korisničko ime</label>
                  <span id="uname_response"></span>
                </div>
                <div class="form-outline  m-5 mb-3">
                  <input size="15" type="email" id="email" name="email" class="form-control form-control-lg" value="<?php echo escape(Input::get('email')); ?>"/>
                  <label class="form-label" for="form3Example3cg">E-mail adresa</label>
                </div>

                <div class="form-outline  m-5 mb-3">
                  <input size="15" type="password" id="password" name="password" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example4cg">Lozinka</label>
                </div>


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


</body>
</html>