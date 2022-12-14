<?php
require_once('navbar.php');
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<link rel="stylesheet" href="forms.css">
<link rel="stylesheet" href="combined.css">
    <title>Registracija</title>
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

              <form>

                <div class="form-outline m-5 mb-3">
                  <input size="15" type="text" id="username" name="username" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example1cg">Korisničko ime</label>
                </div>

                <div class="form-outline  m-5 mb-3">
                  <input size="15" type="password" id="password" name="password" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example4cg">Lozinka</label>
                </div>



                <div class="d-flex justify-content-center">
                  <input type="button"
                    class="btn btn-success btn-block btn-lg gradient-custom-4 text-body proceed" name="login" id="login" value="Uloguj me">
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


</body>
</html>