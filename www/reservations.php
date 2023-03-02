<?php
declare(strict_types=1);

require_once 'core/init.php';
require_once 'functions/pagination.php';
$trenutna_strana = 1;
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
        function potvrdiRezervaciju(id){
        var odgovor=confirm("Potvrda rezervacije: Da li ste sigurni?");
        if (odgovor)
        window.location = "potvrdi_rezervaciju.php?id="+id;
        return false;
      }
    </script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="styles/booking.css">
<link rel="stylesheet" type="text/css" href="styles/admin.css">
<link rel="stylesheet" type="text/css" href="styles/combined.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous"/><link rel="stylesheet" href="styles/combined.css">


<!-- CSS only -->
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
    <title>Dobrodoslica</title>
</head>
<body>
<div class="container">
    <div class="table-responsive" style="width: fit-content; margin-left:-120px;">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-xs-5">
                        <h2>ADMIN <b>Tabela rezervacija</b></h2>
                    </div>
        <form method="post" id="profil" action="">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <div id = "personal" style="">

        <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Br.Kartice</th>
                        <th>Email</th>
                        <th>Br.Odraslih</th>
                        <th>Br.Dece</th>
                        <th>Cena</th>
                        <th>Komentar</th>
                        <th>Kontakt</th>
                        <th>Broj soba</th>
                        <th>Ime aranzmana</th>
                        <th>Potvrda</th>
                    </tr>
                </thead>
                <?php
                    $db = DB::getInstance();
                    $rezervacije_query = $db->query("SELECT * FROM `rezervacije` WHERE korisnik_id is NULL");
                    if (!Input::exists('get')) {
                        $trenutna_strana = 1;
                    } else {
                        $trenutna_strana = (int) Input::get('strana');
                    }
                    $broj_rezervacija = $rezervacije_query->count();
                    $rezervacije = $db->query('SELECT * FROM rezervacije WHERE korisnik_id is NULL limit ? , ?', array(($trenutna_strana - 1) * 10, 10))->results();
                    if (count($rezervacije) > 0) {
                        foreach ($rezervacije as $rezervacija) {
                            $aranzman = $db->get('aranzmani', array('aran_id', '=', $rezervacija->aran_id))->first();
                            echo "<tr class='manageRows'>";
                            echo "<td id='ime'>";
                            echo $rezervacija->rez_id;
                            echo "</td>";
                            echo "<td id='ime'>";
                            echo $rezervacija->ime;
                            echo "</td>";
                            echo "<td id='prezime'>";
                            echo $rezervacija->prezime;
                            echo "</td>";
                            echo "<td id='br_kartice'>";
                            echo $rezervacija->br_kartice;
                            echo "</td>";
                            echo "<td id='email'>";
                            echo $rezervacija->email;
                            echo "</td>";
                            echo "<td id='broj_odraslih'>";
                            echo $rezervacija->broj_odr;
                            echo "</td>";
                            echo "<td id='broj_dece'>";
                            echo $rezervacija->broj_dece;
                            echo "</td>";
                            echo "<td id='cena'>";
                            echo $rezervacija->cena;
                            echo "</td>";
                            echo "<td id='kom'>";
                            echo $rezervacija->kom;
                            echo "</td>";
                            echo "<td id='kontakt'>";
                            echo $rezervacija->kontakt;
                            echo "</td>";
                            echo "<td id='aran_id'>";
                            echo $rezervacija->broj_soba;
                            echo "</td>";
                            echo "<td id='aran_id'>";
                            echo $aranzman->naziv;
                            echo "</td>";
                            echo "<td><input type='submit' class='btn' onclick='return potvrdiRezervaciju($rezervacija->rez_id)' value='Potvrdi rezervaciju'></td>";
                            echo "</tr>";
                        }
                    }
                    //TODO Fix stranicenje and search
                ?>
          </table>
            <div class="clearfix">
    <div class="hint-text">Prikazano <b><?php echo (($trenutna_strana - 1) * 10) + 1; echo ' - '; echo $trenutna_strana * 10;?></b> od <b><?php echo $broj_rezervacija; ?></b> rezervacija</div>
    <ul class="pagination">
    <?php
        echo pagination($broj_rezervacija, $trenutna_strana, 'reservations.php');
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
