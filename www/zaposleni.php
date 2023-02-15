<?php
declare(strict_types=1);

require_once 'core/init.php';
require_once 'functions/pagination.php';
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
require_once 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script>
        function obrisiZaposlenog(id){
        var odgovor=confirm("Brisanje zaposlenog: Da li ste sigurni?");
        if (odgovor)
        window.location = "obrisi_zaposlenog.php?id="+id;
        return false;
      }
    </script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles/admin.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous"/><link rel="stylesheet" href="styles/combined.css">
<link rel="stylesheet" href="combined.css">
<link rel="stylesheet" href="styles/booking.css">
<script>var exports = {};</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
    <title>Dobrodoslica</title>
</head>
<body>
<div class="container">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-xs-5">
                        <h2>ADMIN <b>Tabela</b></h2>
                    </div>
        <form method="post" id="profil" action="">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <div id = "personal" style="">
        <div class="col-xs-7">
        <a href="dodaj_zaposlenog.php" class="btn btn-primary"><i class="material-icons">&#xE147;</i> <span>Dodaj Zaposlenog</span></a>
        </div>

        <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ime Zaposlenog</th>
                        <th>Prezime Zaposlenog</th>
                        <th>Email Zaposlenog</th>
                        <th>Promeni/Ukloni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $db = DB::getInstance();
                        $broj_zaposlenih = get_object_vars($db->query("SELECT COUNT(*) FROM zaposleni")->first())['COUNT(*)'];
                        if (!Input::exists('get')) {
                            $trenutna_strana = 1;
                        } else {
                            $trenutna_strana = (int) Input::get('strana');
                        }
                        $zaposleni = $db->query('SELECT * FROM zaposleni limit ? , ?', array(($trenutna_strana - 1) * 10, 10))->results();
                        foreach ($zaposleni as $zaposlen) {
                            echo '<tr>';
                            echo '<td>' . $zaposlen->zaposleni_id . '</td>';
                            echo '<td>' . $zaposlen->ime . '</td>';
                            echo '<td>' . $zaposlen->prezime . '</td>';
                            echo '<td>' . $zaposlen->email . '</td>';
                            echo "<td>
                                  <a href='profil.php?id=" . $zaposlen->zaposleni_id . "' class='settings' title='Settings' data-toggle='tooltip'><i class='material-icons'>&#xE8B8;</i></a>
                                  <input type='submit' class='btn' onclick='return obrisiZaposlenog($zaposlen->zaposleni_id)' value='Obrisi'>
                                  </td>";
                            echo '</tr>';
                        }//TODO and fix stranicenje search
                    ?>
                </tbody>
            </table>

            <div class="clearfix">
    <div class="hint-text">Prikazano <b><?php echo (($trenutna_strana - 1) * 10) + 1; echo ' - '; echo $trenutna_strana * 10;?></b> od <b><?php echo $broj_zaposlenih; ?></b> zaposlenih</div>
    <ul class="pagination">
        <?php
        echo pagination($broj_zaposlenih, $trenutna_strana, 'zaposleni.php');
        ?>
    </ul>
</div>


        </div>
        </form>
        </div>
        </div>
        </div>
    </section>
</body>
</html>
