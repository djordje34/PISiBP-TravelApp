<?php

require_once 'core/init.php';
$db = DB::getInstance();
$gradovi = (int) $db->query('SELECT COUNT(*) AS count FROM grad')->first()->count;
$grad_ima_sliku = (int) $db->query('SELECT COUNT(*) AS count FROM grad_ima_sliku')->first()->count;
if ($gradovi > 0 && $gradovi == $grad_ima_sliku) {
    Redirect::to('index.php');
}
if (Input::get('proveri')) {
    if ($gradovi > 0 && $gradovi == $grad_ima_sliku) {
        echo 1;
        return;
    }
    echo 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dobrodoslica</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha512-3P8rXCuGJdNZOnUx/03c1jOTnMn3rP63nBip5gOP2qmUh5YAdVAvFZ1E+QLZZbC1rtMrQb+mah3AfYW11RUrWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/waiting.js"></script>
</head>
<body>
<img src="imgs/nasgif.gif" alt="">
    </body>
</html>
