<?php

require_once 'core/init.php';
require_once 'navbar.php';
require_once 'functions/stars.php';
require_once 'functions/GET_to_link.php';
$db = DB::getInstance();
//SELECT * FROM table limit 100` -- get 1st 100 records
//SELECT * FROM table limit 100, 200` -- get 200 records beginning with row 101
$stavke = '50';
$trenutna_strana = 1;
$strana_prosli = 1;
$broj_ponuda_sql = "SELECT COUNT(*) FROM aranzmani";
$sql_trenutni = "SELECT * FROM aranzmani WHERE krece > ? ORDER BY krece ASC limit ?";
$sql_prosli = "SELECT * FROM aranzmani WHERE krece < ? ORDER BY krece DESC limit ?";
$trenutno_vreme = new DateTime(date('Y-m-d H:i:s', time()));
$trenutno_vreme = $trenutno_vreme->format('Y-m-d H:i:s');
$trenutni_aranzmani = array();
$prosli_aranzmani = array();
if (Input::exists('get') && Input::get('stavke') != '') {
    $stavke = (int) Input::get('stavke');
}
if (Input::exists('get') && Input::get('strana') != '') {
    $trenutna_strana = (int) Input::get('strana');
}
if (Input::exists('get')) {
    $ime = "%" . strtolower(Input::get('ime')) . "%";
    $kontinent = "%" . strtolower(Input::get('kontinent')) . "%";
    $drzava = "%" . strtolower(Input::get('drzava')) . "%";
    $prevoz = "%" . strtolower(Input::get('prevoz')) . "%";
    $datum_pol = new DateTime(Input::get('datum_polaska'));
    $datum_odl = new DateTime(Input::get('datum_odlaska'));
    $broj_ponuda_sql = "SELECT
                        COUNT(*)
                    FROM
                        aranzmani a
                    JOIN prevoz p ON
                        a.p_id = p.p_id
                    JOIN smestaj s ON
                        a.smestaj_id = s.smestaj_id
                    JOIN grad g ON
                        s.g_id = g.g_id
                    JOIN drzava d ON
                        g.d_id = d.d_id
                    JOIN kontinent k ON
                        d.k_id = k.k_id
                    WHERE
                    (
                        (LOWER(a.naziv) LIKE ?) OR(LOWER(g.ime) LIKE ?)
                    ) AND k.ime LIKE ? AND d.ime LIKE ? AND tip LIKE ? ";
    $sql_trenutni = "SELECT aran_id, a.naziv, krece, vraca, nap, tip, k.ime, d.ime, g.ime, a.p_id, a.smestaj_id FROM aranzmani a JOIN prevoz p ON a.p_id = p.p_id JOIN smestaj s ON a.smestaj_id = s.smestaj_id JOIN grad g ON s.g_id = g.g_id JOIN drzava d ON g.d_id = d.d_id JOIN kontinent k ON d.k_id = k.k_id WHERE
                    (
                        (LOWER(a.naziv) LIKE ?) OR(LOWER(g.ime) LIKE ?)
                    ) AND k.ime LIKE ? AND d.ime LIKE ? AND tip LIKE ? ";
    $sql_prosli = "SELECT
                        aran_id,
                        a.naziv,
                        krece,
                        vraca,
                        nap,
                        tip,
                        k.ime,
                        d.ime,
                        g.ime,
                        a.p_id,
                        a.smestaj_id
                    FROM
                        aranzmani a
                    JOIN prevoz p ON
                        a.p_id = p.p_id
                    JOIN smestaj s ON
                        a.smestaj_id = s.smestaj_id
                    JOIN grad g ON
                        s.g_id = g.g_id
                    JOIN drzava d ON
                        g.d_id = d.d_id
                    JOIN kontinent k ON
                        d.k_id = k.k_id
                    WHERE
                        (
                            (LOWER(a.naziv) LIKE ?) OR(LOWER(g.ime) LIKE ?)
                        ) AND k.ime LIKE ? AND d.ime LIKE ? AND tip LIKE ? ";
    if (!Input::get('datum_polaska') && !Input::get('datum_odlaska')) {
        $sql_trenutni .= 'AND krece > ? ';
        $broj_trenutnih_ponuda = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme))->count();
        $broj_ponuda = get_object_vars($db->query($broj_ponuda_sql, array($ime, $ime, $kontinent, $drzava, $prevoz))->first())['COUNT(*)'];
        $strana_prosli = floor($broj_trenutnih_ponuda / $stavke) + 1;
        $sql_trenutni .= 'ORDER BY krece ASC limit ? ';
        $sql_prosli = $sql_prosli . 'AND krece < ? ORDER BY krece DESC limit ?';
        if ($trenutna_strana > 1) {
            $sql_trenutni = $sql_trenutni . ', ?';
            $sql_prosli = $sql_prosli . ', ?';

            $trenutni_aranzmani = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, (int) $stavke * ($trenutna_strana - 1), $stavke))->results();

            if ((int) $db->count() < (int) $stavke) {
                $prosli_aranzmani = $db->query($sql_prosli, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, (int) $stavke * ($trenutna_strana - $strana_prosli), (int) $stavke - (int) $db->count()))->results();
            }
        } else {
            $trenutni_aranzmani = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, $stavke))->results();
            if ((int) $db->count() < (int) $stavke) {
                $prosli_aranzmani = $db->query($sql_prosli, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, (int) $stavke - (int) $db->count()))->results();
            }
        }
    } elseif (!Input::get('datum_odlaska')) {
        $broj_ponuda_sql = $broj_ponuda_sql . "AND DATE(krece) = ?";
        $broj_ponuda = get_object_vars($db->query($broj_ponuda_sql, array($ime, $ime, $kontinent, $drzava, $prevoz, $datum_pol->format('Y-m-d')))->first())['COUNT(*)'];
        $sql_trenutni .= "ORDER BY krece ASC limit ?";
        if ($trenutna_strana > 1) {
            $sql_trenutni = $sql_trenutni . ', ?';
            $trenutni_aranzmani = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $datum_pol->format('Y-m-d'), (int) $stavke * ($trenutna_strana - 1), $stavke))->results();
        } else {
            $trenutni_aranzmani = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $datum_pol->format('Y-m-d'), $stavke))->results();
        }
    } elseif (!Input::get('datum_polaska')) {
        $broj_ponuda_sql = $broj_ponuda_sql . "AND DATE(vraca) = ?";
        $broj_ponuda = get_object_vars($db->query($broj_ponuda_sql, array($ime, $ime, $kontinent, $drzava, $prevoz, $datum_odl->format('Y-m-d')))->first())['COUNT(*)'];

        $sql_trenutni .= 'AND krece > ? AND DATE(vraca) = ? ';
        $broj_trenutnih_ponuda = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, $datum_odl->format('Y-m-d')))->count();
        $strana_prosli = floor($broj_trenutnih_ponuda / $stavke) + 1;
        $sql_trenutni .= "ORDER BY krece ASC limit ?";
        $sql_prosli = $sql_prosli . "AND krece < ? AND DATE(vraca) = ? ORDER BY krece DESC limit ?";
        if ($trenutna_strana > 1) {
            $sql_trenutni = $sql_trenutni . ', ?';
            $sql_prosli = $sql_prosli . ', ?';
            $trenutni_aranzmani = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, $datum_odl->format('Y-m-d'), (int) $stavke * ($trenutna_strana - 1), $stavke))->results();
            if ((int) $db->count() < (int) $stavke) {
                $prosli_aranzmani = $db->query($sql_prosli, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, $datum_odl->format('Y-m-d'), (int) $stavke * ($trenutna_strana - $strana_prosli), (int) $stavke - (int) $db->count()))->results();
            }
        } else {
            $trenutni_aranzmani = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, $datum_odl->format('Y-m-d'), $stavke))->results();
            if ((int) $db->count() < (int) $stavke) {
                $prosli_aranzmani = $db->query($sql_prosli, array($ime, $ime, $kontinent, $drzava, $prevoz, $trenutno_vreme, $datum_odl->format('Y-m-d'), (int) $stavke - (int) $db->count()))->results();
            }
        }
    } else {
        $broj_ponuda_sql = $broj_ponuda_sql . "AND DATE(krece) >= ? AND DATE(vraca) <= ?";
        $broj_ponuda = get_object_vars($db->query($broj_ponuda_sql, array($ime, $ime, $kontinent, $drzava, $prevoz, $datum_pol->format('Y-m-d'), $datum_odl->format('Y-m-d')))->first())['COUNT(*)'];
        $sql_trenutni .= 'AND DATE(krece) >= ? AND DATE(vraca) <= ? ';
        $sql_trenutni = $sql_trenutni . " ORDER BY krece ASC limit ?";
        if ($trenutna_strana > 1) {
            $sql_trenutni = $sql_trenutni . ', ?';
            $trenutni_aranzmani = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $datum_pol->format('Y-m-d'), $datum_odl->format('Y-m-d'), (int) $stavke * ($trenutna_strana - 1), $stavke))->results();
        } else {
            $trenutni_aranzmani = $db->query($sql_trenutni, array($ime, $ime, $kontinent, $drzava, $prevoz, $datum_pol->format('Y-m-d'), $datum_odl->format('Y-m-d'), $stavke))->results();
        }
    }
} else {
    $broj_ponuda = get_object_vars($db->query($broj_ponuda_sql)->first())['COUNT(*)'];

    if ($trenutna_strana > 1) {
        $broj_trenutnih_ponuda = $db->query("SELECT * FROM aranzmani WHERE krece > ?", array($trenutno_vreme))->count();
        $strana_prosli = floor($broj_trenutnih_ponuda / $stavke) + 1;
        $sql_trenutni = $sql_trenutni . ', ?';
        $sql_prosli = $sql_prosli . ', ?';
        $trenutni_aranzmani = $db->query($sql_trenutni, array($trenutno_vreme, (int) $stavke * ($trenutna_strana - 1), (int) $stavke))->results();
        if ((int) $db->count() < (int) $stavke) {
            $prosli_aranzmani = $db->query($sql_prosli, array($trenutno_vreme, (int) $stavke * ($trenutna_strana - $strana_prosli), (int) $stavke - (int) $db->count()))->results();
        }
    } else {
        $trenutni_aranzmani = $db->query($sql_trenutni, array($trenutno_vreme, (int) $stavke))->results();
        if ((int) $db->count() < (int) $stavke) {
            $prosli_aranzmani = $db->query($sql_prosli, array($trenutno_vreme, (int) $stavke - (int) $db->count()))->results();
        }
    }
}
$aranzmani = array_merge($trenutni_aranzmani, $prosli_aranzmani);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha512-3P8rXCuGJdNZOnUx/03c1jOTnMn3rP63nBip5gOP2qmUh5YAdVAvFZ1E+QLZZbC1rtMrQb+mah3AfYW11RUrWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js" integrity="sha512-Blu6KWaTqestULmtJUTB2mwDFTB/FDRuTHV52Uu5dTFVQZ8YWUq2LuxN/brNEjWAqlHr50oAbxrydsuxoK/mqQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!-- CSS only -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/css/bootstrap-select.min.css" integrity="sha512-8IKwXYhvXkrNGaU06NnGsiDqJign94kk5+AAdTu4wR3hkuU5x2Weo1evN3xYSpnRtIJNLwAT2/R4ITAAv0IhdA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-responsive-bs/2.4.0/responsive.bootstrap.css" integrity="sha512-vI15fTaw/NoZgZcW6ipbDQf/BlDYDO7ae6+RWnSy9xSDC7L22BV1RdA8GtTjSFe4TLy+9twcCCnI29UbfbBhUg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous"/><link rel="stylesheet" href="styles/combined.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="styles/MultiCarousel.css">
<link rel="stylesheet" type="text/css" href="styles/ponude.css">
<link rel="stylesheet" type="text/css" href="styles/ponude_responsive.css">
<script>var exports = {};</script>
    <title>Dobrodoslica</title>
</head>
<body>
<div id = "ponuda"></div>
<div class="super_container">
    <!-- Home -->
    <div class="home">
        <div class="home_background parallax-window" data-parallax="scroll" data-image-src="imgs/pozadina1.jpg"></div>
        <div class="home_content">
            <div class="home_title">NASA PONUDA:</div>
        </div>
    </div>
    <!-- Offers -->
    <div class="offers">
        <!-- Offers -->
        <div class="container">
            <div class="row">
            <section class="ftco-section ftco-no-pb ftco-no-pt">
        <div class="container">
            <div class="row">
                    <div class="col-md-12">
                        <div class="search-wrap-1 ftco-animate p-4">
                            <form method="get" class="search-property-1">
                        <div class="row">
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Destinacija</label>
                                  <div class="form-field">
                                      <div class="icon"><span class="fas fa-search"></span></div>
                                <input type="text" class="form-control" placeholder="Pretrazite mesta" id="ime" name="ime">
                              </div>
                          </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Kontinent</label>
                                    <div class="form-field">
                                      <div class="select-wrap">
                              <div class="icon"><span class="fas fa-arrow-down"></span></div>
                              <select name="kontinent" id="kontinent" class="form-control">
                                <option class=option value="">Sve</option>
                                <option class=option value="Evropa">Evropa</option>
                                <option class=option value="Azija">Azija</option>
                                <option class=option value="Afrika">Afrika</option>
                                <option class=option value="Australija / Okeanija">Australija</option>
                                <option class=option value="Severna amerika">Severna amerika</option>
                                <option class=option value="Južna amerika">Južna amerika</option>
                              </select>
                            </div>
                              </div>
                          </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Drzava</label>
                                    <div class="form-field">
                                      <div class="select-wrap">
                                        <div class="icon" id="drzaveOvde"><span class="fas fa-arrow-down"></span></div>
                                        <select name="drzava" id="drzava" class="form-control">
                                        </select>
                                </div>
                              </div>
                          </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Tip Prevoza</label>
                                    <div class="form-field">
                                      <div class="select-wrap">
                              <div class="icon"><span class="fas fa-arrow-down"></span></div>
                              <select name="prevoz" id="prevoz" class="form-control">
                                <option class=option value="">Sve</option>
                                <option class=option value="airplane">Avion</option>
                                <option class=option value="train">Voz</option>
                                <option class=option value="cruise">Brod</option>
                                <option class=option value="bus">Autobus</option>
                                <option class=option value="lično vozilo">Samostalni prevoz</option>
                              </select>
                            </div>
                              </div>
                          </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Datum polaska</label>
                                    <div class="form-field">
                                      <div class="icon"><span class="fas fa-calendar"></span></div>
                                <input data-provide="datepicker" type="text" class="form-control checkin_date" placeholder="mm/dd/yyyy" id="datum_polaska" name="datum_polaska">
                              </div>
                          </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Datum povratka</label>
                                    <div class="form-field">
                                      <div class="icon"><span class="fas fa-calendar"></span></div>
                                <input data-provide="datepicker" type="text" class="form-control checkout_date" placeholder="mm/dd/yy" id="datum_odlaska" name="datum_odlaska">
                              </div>
                          </div>
                            </div>
                            <div class="col-lg align-self-end">
                                <div class="form-group">
                                    <div class="form-field">
                                <input type="submit" value="Search" class="form-control btn btn-primary">
                              </div>
                          </div>
                            </div>
                        </div>
                    </form>
                </div>
                    </div>
            </div>
        </div>
    </section>

    <div class="paginationjss">
  <div class="paginationjss-pages">
    <ul>
        <a href="ponude.php?stavke=25&strana=<?php echo $trenutna_strana; echo GET_to_link();?>">
      <li class="paginationjss-page J-paginationjss-page <?php if ((int) Input::get('stavke') == 25) echo "active"?>" data-num="25">
        25
      </li>
      </a>
      <a href="ponude.php?stavke=50&strana=<?php echo $trenutna_strana; echo GET_to_link();?>">
      <li class="paginationjss-page J-paginationjss-page <?php if (!Input::get('stavke') || (int) Input::get('stavke') == 50) echo "active"?>" data-num="50">
        50
      </li>
      </a>
      <a href="ponude.php?stavke=100&strana=<?php echo $trenutna_strana; echo GET_to_link();?>">
      <li class="paginationjss-page J-paginationjss-page <?php if ((int) Input::get('stavke') == 100) echo "active"?>" data-num="100">
        100
      </li>
      </a>
      <a href="ponude.php?stavke=200&strana=<?php echo $trenutna_strana; echo GET_to_link();?>">
      <li class="paginationjss-page J-paginationjss-page <?php if ((int) Input::get('stavke') == 200) echo "active"?>" data-num="200">
        200
      </li>
      </a>
    </ul>
  </div>
</div>

<script>
  const paginationPages = document.querySelectorAll(".paginationjss-page");

  paginationPages.forEach(page => {
    page.addEventListener("click", function() {
      paginationPages.forEach(p => {
        p.classList.remove("active");
      });
      this.classList.add("active");
    });
  });
</script>

                <div class="col-lg-12">
                    <!-- Offers Grid -->
                    <div class="offers_grid">
                        <!-- Offers Item -->
                        <?php
                        foreach ($aranzmani as $aranzman) {
                            $naziv = $aranzman->naziv;
                            $id = $aranzman->aran_id;
                            $minimalna_cena_kreveta = $db->query('SELECT gen_cena FROM aranzmani a JOIN smestaj s ON a.smestaj_id=s.smestaj_id JOIN soba ON s.smestaj_id=soba.smestaj_id JOIN sobatip_hash sh ON soba.tip = sh.id WHERE a.aran_id = ? ORDER BY gen_cena ASC', array($id))->first()->gen_cena;
                            //TODO promeni na stvarnu minimalnu cenu po danu
                            $datum_polaska = new DateTime($aranzman->krece);
                            $datum_povratka = new DateTime($aranzman->vraca);
                            $dani = $datum_polaska->diff($datum_povratka)->d;
                            $prevoz = $db->action('SELECT *', 'prevoz', array('p_id','=',$aranzman->p_id))->first();
                            $prevoz_tip = $prevoz->tip;
                            $current_time = new DateTime(date('Y-m-d H:i:s', time()));
                            $smestaj = $db->get('smestaj', array('smestaj_id', '=', $aranzman->smestaj_id))->first();
                            $ocena_smestaja = $smestaj->br_zvezdica;
                            $cena = $minimalna_cena_kreveta + (($minimalna_cena_kreveta / 2) * ($ocena_smestaja - 3));
                            $slika_grada = $db->action('SELECT slika', 'grad_ima_sliku', array('grad_id', '=', $smestaj->g_id))->first()->slika;
                            $slika_grada = str_replace('\\', '/', $slika_grada);
                            $prevoz_cena = $prevoz->cena;
                            if(!$prevoz_cena)
                                $prevoz_cena='(Putarina + gorivo)';
                            echo '
                        <div class="offers_item rating">
                            <div class="row">
                                <div class="col-lg-1 temp_col"></div>
                                <div class="col-lg-3 col-1680-4">
                                    <div class="offers_image_container">
                                        <div class="offers_image_background" style="background-image:url(' . $slika_grada . ');';
                                        if ($datum_polaska < $current_time) {
                                            echo 'filter: grayscale(1);';
                                        }
                                        echo '"></div>
                                        <div class="offer_name"><a href="#">' . $naziv . '</a></div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="offers_content">
                                        <div class="offers_price"> Pocinje od €' . $cena . '<span>po noci</span></div>
                                        <div class="rating rating_5">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half"></i>
                                        </div>
                                        <p class="offers_text" style="font-weight: bold; font-size: 17px;">' . $naziv . '</p>
                                        <div class="offers_icons">
                                            <ul class="offers_icons_list">
                                                <li class="offers_icons_item" style="color:grey; font-weight: bold;"><img src="imgs/' . $prevoz_tip . '.png"  alt=""> '.$prevoz_cena.'€</li>
                                            </ul>
                                        </div>
                                        <div class="tourmaster-tour-info-wrap clearfix">
                                        <div class="tourmaster-tour-info tourmaster-tour-info-duration-text" style="font-weight: bolder;">
                                        <i class="fas fa-clock" style="color:grey"></i>' . $dani . ' Dana
                                        </div>
                                        </div>

                                        <div class="tourmaster-tour-info-wrap clearfix">
                                        <div class="tourmaster-tour-info tourmaster-tour-info-duration-text" style="font-weight: bolder;">
                                        <i class="fas fa-calendar" style="color:grey"></i>' . $datum_polaska->format('d-m-Y') . '
                                        </div>
                                        </div>';
                            if ($datum_polaska > $current_time) {
                                echo
                                    '
                                    <form action="hotel.php" method="post" id="' . $id . '" name="' . $id . '">
                                        <input type="hidden" name="aran_id" value="' . $id . '">
                                        <div class="book_button">
                                        <button class="book_button_1" type="submit" form="' . $id . '" value="submit">Zakazi<span></span><span></span><span></span></button>
                                        </div>
                                        </form>';
                            }
                                        echo'
                                        <div class="offer_reviews">
                                            <div class="offer_reviews_content">
                                                <div class="offer_reviews_title">' . convertStars($ocena_smestaja) . '</div>
                                            </div>
                                            <div class="offer_reviews_rating text-center">' . $ocena_smestaja . '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="paginationjs">
    <div class="paginationjs-pages">
        <ul style='display: inline-flex;'><?php $search = GET_to_link(); ?>
        <a href=" <?php echo 'ponude.php?stavke=' . $stavke . '&strana=1' . $search; ?>"><li class="paginationjs-prev <?php if ($trenutna_strana == 1) echo 'disabled'?>">‹‹</li></a>
            
            <?php
                $broj_strana = floor($broj_ponuda / $stavke);
                if ($broj_ponuda % $stavke != 0) {
                    $broj_strana++;
                }
                if ($broj_strana <= 6) {
                    for ($i = 1; $i <= $broj_strana; $i++) {
                        echo '<a href="ponude.php?stavke=' . $stavke . '&strana=' . $i . $search . '" class="page-link">';
                        echo '<li class="paginationjs-page J-paginationjs-page  ';
                        if ($i == $trenutna_strana) {
                            echo 'active';
                        }
                        echo'" data-num="' . $i . '">' . $i . '</li></a>';
                    }
                } else {
                    if ($trenutna_strana >= 3 && $trenutna_strana < $broj_strana - 3) {
                        for ($i = $trenutna_strana - 1; $i <= $trenutna_strana + 1; $i++) {
                            echo '<a href="ponude.php?stavke=' . $stavke . '&strana=' . $i . $search . '" class="page-link">';
                            echo '<li class="paginationjs-page J-paginationjs-page  ';
                            if ($i == $trenutna_strana) {
                                echo 'active';
                            }
                            echo'" data-num="' . $i . '">' . $i . '</li></a>';
                        }
                    } elseif ($trenutna_strana < $broj_strana - 3) {
                        for ($i = $trenutna_strana; $i <= $trenutna_strana + 2; $i++) {
                            echo '<a href="ponude.php?stavke=' . $stavke . '&strana=' . $i . $search . '" class="page-link">';
                            echo '<li class="paginationjs-page J-paginationjs-page  ';
                            if ($i == $trenutna_strana) {
                                echo 'active';
                            }
                            echo'" data-num="' . $i . '">' . $i . '</li></a>';
                        }
                    }
                    echo '<li class="paginationjs-ellipsis disabled"><a>...</a></li>';
                    for ($i = $broj_strana - 2; $i <= $broj_strana; $i++) {
                        echo '<a href="ponude.php?stavke=' . $stavke . '&strana=' . $i . $search . '" class="page-link">';
                        echo '<li class="paginationjs-page J-paginationjs-page  ';
                        if ($i == $trenutna_strana) {
                            echo 'active';
                        }
                        echo'" data-num="' . $i . '">' . $i . '</li></a>';
                    }
                }//TODO FIX
            ?>
            <a href=" <?php echo 'ponude.php?stavke=' . $stavke . '&strana=' . $broj_strana . '' . $search; ?>">
            <li class="paginationjs-next J-paginationjs-next " data-num="2" title="Next page">››</li></a>
        </ul>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="MultiCarousel" data-items="1,2,3,4" data-slide="1" id="MultiCarousel"  data-interval="1000">
            <div class="MultiCarousel-inner">
                <div class="item">
                    <div class="pad15">
                        <p class="lead">Air Serbia</p>
                        <a id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl00_imgMainLink" href="http://www.airserbia.com" target="_blank"><img id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl00_imgMain" title="Air Serbia" class="img-responsive" src="imgs/Logo-AirSerbia.png" alt="Air Serbia" style="border-width:0px; margin: auto;"></a>
                    </div>
                </div>
                <div class="item">
                    <div class="pad15">
                        <p class="lead">Etihad</p>
                        <a id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl01_imgMainLink" href="http://www.etihad.com/" target="_blank"><img id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl01_imgMain" title="Etihad" class="img-responsive" src="imgs/Logo-Etihad.png" alt="Etihad" style="border-width:0px;margin: auto;"></a>
                    </div>
                </div>
                <div class="item">
                    <div class="pad15">
                        <p class="lead">Turkish Airlines</p>
                        <a id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl02_imgMainLink" href="http://www.turkishairlines.com" target="_blank"><img id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl02_imgMain" title="Tusrkish Airlines" class="img-responsive" src="imgs/Logo-Turkish.png" alt="Tusrkish Airlines" style="border-width:0px;margin: auto;"></a>
                    </div>
                </div>
                <div class="item">
                    <div class="pad15">
                        <p class="lead">Banca Intesa</p>
                        <a id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl03_imgMainLink" href="http://www.bancaintesa.rs" target="_blank"><img id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl03_imgMain" title="Banca Intesa" class="img-responsive" src="imgs/Logo-BancaIntesa.png" alt="Banca Intesa" style="border-width:0px;margin: auto;"></a>
                    </div>
                </div>
                <div class="item">
                    <div class="pad15">
                        <p class="lead">YUTA</p>
                        <a id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl04_imgMainLink" href="http://www.yuta.rs/yuta/o-nama/yuta-garancija-putovanja-21" target="_blank"><img id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl04_imgMain" title="YUTA" class="img-responsive" src="imgs/Logo-YUTA.png" alt="YUTA" style="border-width:0px;margin: auto;"></a>
                    </div>
                </div>
                <div class="item">
                    <div class="pad15">
                        <p class="lead">IATA</p>
                        <a id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl05_imgMainLink" href="http://www.iata.org" target="_blank"><img id="ctl00_cphMain_bottom_ctl01_rptGallery_ctl05_imgMain" title="IATA" class="img-responsive" src="imgs/Logo-IATA.png" alt="IATA" style="border-width:0px;margin: auto;"></a>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary leftLst"><</button>
            <button class="btn btn-primary rightLst">></button>
        </div>
    </div>
</div>
<footer class="bg-dark text-center text-white p-0" id="footer">
    <!-- Grid container -->
    <div class="container p-4 pb-0">
        <!-- Section: Social media -->
        <section class="mb-4">
        <!-- Facebook -->
        <img src="imgs/logo.png" alt="logo">


        </section>
        
    </div>

    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        BARKA TRAVEL by C.L.A.Y.
    </div>

</footer>
        
<script type="text/javascript" src="scripts/MultiCarousel.js"></script>
<script type="text/javascript" src="scripts/script.js"></script>
<script type="text/javascript" src="scripts/filter.js"></script>
<script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script src="scripts/ponudeCustom.js"></script>
<script src="scripts/ponudeCard.js" type="text/babel"></script>

</body>
</html>
