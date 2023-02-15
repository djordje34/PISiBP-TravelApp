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
        function obrisiAranzman(id){
        var odgovor=confirm("Potvrda brisanja: Da li ste sigurni?");
        if (odgovor)
        window.location = "obrisi_aranzman.php?id="+id;
        return false;
      }
    </script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="combined.css">
<link rel="stylesheet" href="styles/booking.css">
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles/admin.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous"/><link rel="stylesheet" href="styles/combined.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
        <a href="dodaj_aranzman.php" class="btn btn-primary"><i class="material-icons">&#xE147;</i> <span>Dodaj Aranzman</span></a>
        </div>

        <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ime Aranzmana</th>
                        <th>Datum Polaska</th>
                        <th>Datum Odlaska</th>
                        <th>Prevoznik</th>
                        <th>Smestaj</th>
                        <th>Napomena</th>
                        <th>Status</th>
                        <th>Promeni/Ukloni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $db = DB::getInstance();
                        $broj_aranzmana = get_object_vars($db->query("SELECT COUNT(*) FROM aranzmani")->first())['COUNT(*)'];
                        if (!Input::exists('get')) {
                            $trenutna_strana = 1;
                        } else {
                            $trenutna_strana = (int) Input::get('strana');
                        }
                        $aranzmani = $db->query('SELECT * FROM aranzmani limit ? , ?', array(($trenutna_strana - 1) * 10, 10))->results();
                        foreach ($aranzmani as $aranzman) {
                            $datum_polaska = new DateTime($aranzman->krece);
                            $datum_povratka = new DateTime($aranzman->vraca);
                            $current_time = new DateTime(date('Y-m-d H:i:s', time()));
                            $prevoz = $db->action('SELECT tip, ime_komp', 'prevoz', array('p_id','=',$aranzman->p_id))->first();
                            $smestaj = $db->action('SELECT naziv, adresa', 'smestaj', array('smestaj_id', '=', $aranzman->smestaj_id))->first();
                            echo '<tr>';
                            echo '<td>' . $aranzman->aran_id . '</td>';
                            echo '<td>' . $aranzman->naziv . '</td>';
                            echo '<td>' . $aranzman->krece . '</td>';
                            echo '<td>' . $aranzman->vraca . '</td>';
                            echo '<td>' . $prevoz->tip . ' - ' . $prevoz->ime_komp . '</td>';
                            echo '<td>' . $smestaj->naziv . ' - ' . $smestaj->adresa . '</td>';
                            echo '<td>' . $aranzman->nap . '</td>';
                            if ($datum_polaska > $current_time) {
                                echo '<td><span class="status text-success">&bull;</span> Aktivan</td>';
                            } elseif ($datum_polaska < $current_time) {
                                echo '<td><span class="status text-danger">&bull;</span> Prosao</td>';
                            } else {
                                echo '<td><span class="status text-warning">&bull;</span> Inactive</td>';
                            }
                            echo "<td>
                                  <a href='dodaj_aranzman.php?id=" . $aranzman->aran_id . "' class='settings' title='Settings' data-toggle='tooltip'><i class='material-icons'>&#xE8B8;</i></a>
                                  <input type='submit' class='btn' onclick='return obrisiAranzman($aranzman->aran_id)' value='Obrisi'>
                                  </td>";
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>

            <div class="clearfix">
    <div class="hint-text">Prikazano <b><?php echo (($trenutna_strana - 1) * 10) + 1; echo ' - '; echo $trenutna_strana * 10;?></b> od <b><?php echo $broj_aranzmana; ?></b> aranzmana</div>
    <ul class="pagination">
        <?php
        echo pagination($broj_aranzmana, $trenutna_strana, 'aranzmani.php');
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