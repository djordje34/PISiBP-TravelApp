<?php 
require_once 'navbar.php';
require_once 'core/init.php';

if(!Session::exists(Config::get('session/session_name'))){
  Redirect::to('login.php');
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
    <title>Dobrodoslica</title>
</head>
<body>
<section class="vh-100 bg-image"
  style="background-image: url('imgs/profilebg.jpg');background-size: cover;background-attachment: fixed;">
  <div class="mask d-flex align-items-center h-100 backgroundable2">
        <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100 packer">



        </div>
        </div>
        </div>
    </section>

</body>