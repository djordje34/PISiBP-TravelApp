

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstraped.css">
       
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
    <?php
    require_once 'core/init.php';
    if(Session::exists(Config::get('session/session_name')))
    {
            echo "
                <ul class=\"navbar-nav mr-auto mt-2 mt-lg-0\">
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"landing.php\">Početna </span></a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"#\">Ponude</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link \" href=\"profil.php\">Vaš profil</a>
                </li>
                </ul>
                <form class=\"form-inline my-2 my-lg-0\">
                <!-- <input class=\"form-control mr-sm-2\" type=\"search\" placeholder=\"Search\" aria-label=\"Search\"> 
                <button class=\"btn btn-outline-success my-2 my-sm-0\" type=\"submit\">Search</button> -->
                </form>
                </div>
                <form method='post'>
                <input type='submit' style='right:0%;margin-right:5%'
                    class='  btn btn-danger btn-block btn-md text-body izlog' name='logout'
                    id='logout' value='Izloguj me'>
                </form>

        ";
        }

            ?>
            </div>
            </nav>
    </body>
</html>