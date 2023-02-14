<?php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script>
            $(function(){
                $('a').each(function(){
                    if ($(this).prop('href') == window.location.href) {
                        $(this).addClass('active'); $(this).parents('li').addClass('active');
                    }
                });
            });
        </script>
        <link rel="stylesheet" href="styles\bootstraped.css">
        <link rel="stylesheet" href="styles\navbar.css">
       
    </head>
    <body>
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="">
                <a class="navbar-brand m-3 mb-0 mt-0" href="">
                <img src="imgs/logo.png" alt="logo" width="50px">
                </a>
    <div class="nav_bar">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Početna</span></a>
        </li>
        <li class="nav-item\">
            <a class="nav-link" href="ponude.php">Ponude</a>
        </li>
    <?php
    $user = new User();
    if ($user->permissionLevel() == 2) {
        echo "
        <li class=\"nav-item\">
            <a class=\"nav-link \" href=\"zaposleni.php\">Dodavanje zaposlenog</a>
        </li>
";
    }
    if ($user->permissionLevel() == 2 || $user->permissionLevel() == 1) {
            echo "
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"aranzmani.php\">Lista aranzmana</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link \" href=\"changepassword.php\">Promena sifre</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link \" href=\"dodaj_aranzman.php\">Dodavanje aranzmana</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link \" href=\"profil.php\">Vas profil</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link \" href=\"reservations.php\">Lista rezervacija</a>
                </li>
                </ul>
                <form class=\"form-inline my-2 my-lg-0\">
                <!-- <input class=\"form-control mr-sm-2\" type=\"search\" placeholder=\"Search\" aria-label=\"Search\">
                <button class=\"btn btn-outline-success my-2 my-sm-0\" type=\"submit\">Search</button> -->
                </form>
                </div>
                <div class='ml-auto'>
                    <a href='logout.php' class='link-danger m-3 mb-3' style = 'text-decoration:none;'>Izloguj me</a>
                </div>
        ";
    } else {
        echo "
        </ul>
        <form class=\"form-inline my-2 my-lg-0\">
        <!-- <input class=\"form-control mr-sm-2\" type=\"search\" placeholder=\"Search\" aria-label=\"Search\">
        <button class=\"btn btn-outline-success my-2 my-sm-0\" type=\"submit\">Search</button> -->
        </form>
        </div>
            <a href='login.php' class='link-warning m-3 mb-3' style = 'text-decoration:none;'>Uloguj me</a>
        <div>
";
    }
    ?>
            
            </nav>
            </div>
    </body>
</html>
