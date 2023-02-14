<?php

require_once 'core/init.php';
if (!Input::get('aran_id')) {
    Redirect::to('ponude.php');
}
require_once 'navbar.php';
require_once 'functions/stars.php';
require_once 'functions/prevoz_prevod.php';
require_once 'functions/convert_hours.php';
$db = DB::getInstance();
$aran_id = Input::get('aran_id');
$aranzman = $db->get('aranzmani', array('aran_id', '=', $aran_id))->first();
$prevoz = $db->get('prevoz', array('p_id', '=', $aranzman->p_id))->first();
$smestaj = $db->get('smestaj', array('smestaj_id', '=', $aranzman->smestaj_id))->first();
$naziv_aranzmana = $aranzman->naziv;
$grad = $db->get('grad', array('g_id', '=', $smestaj->g_id))->first();
$grad_ime = $grad->ime;
$grad_slika = $db->get('grad_ima_sliku', array('grad_id', '=', $grad->g_id))->first()->slika;
$broj_zvezdica = $db->get('smestaj', array('smestaj_id', '=', $aranzman->smestaj_id))->first()->br_zvezdica;
$adresa = preg_replace('/\s+/', '+', $smestaj->adresa);
$datum_pol = new DateTime($aranzman->krece);
$datum_odl = new DateTime($aranzman->vraca);
$trajanje_aranzmana = $datum_pol->diff($datum_odl)->d;
$aktivnosti = $db->get('ima_aktivnost', array('aran_id', '=', $aran_id))->results();
$aktivnosti_u_gradu = $db->get('akt_u_gradu', array('smestaj_id', '=', $smestaj->smestaj_id))->results();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha512-3P8rXCuGJdNZOnUx/03c1jOTnMn3rP63nBip5gOP2qmUh5YAdVAvFZ1E+QLZZbC1rtMrQb+mah3AfYW11RUrWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js" integrity="sha512-Blu6KWaTqestULmtJUTB2mwDFTB/FDRuTHV52Uu5dTFVQZ8YWUq2LuxN/brNEjWAqlHr50oAbxrydsuxoK/mqQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script><script type="text/babel" src="scripts/ponudeCard.js"></script>
<!-- CSS only -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-responsive-bs/2.4.0/responsive.bootstrap.css" integrity="sha512-vI15fTaw/NoZgZcW6ipbDQf/BlDYDO7ae6+RWnSy9xSDC7L22BV1RdA8GtTjSFe4TLy+9twcCCnI29UbfbBhUg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous"/><link rel="stylesheet" href="styles/combined.css">
<link rel="stylesheet" type="text/css" href="styles/ponude.css">
<link rel="stylesheet" type="text/css" href="styles/hotel.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="scripts/script.js"></script>
<script>var exports = {};</script>
    <title>Dobrodoslica</title>
</head>


<body>

<div class="super_container">
    

    <!-- Home -->

    <div class="home">
        <div class="home_background parallax-window" data-parallax="scroll" data-image-src="<?php echo $grad_slika?>"></div>
        <div class="home_content">
            <div class="home_title"><?php echo $grad_ime;?></div>
        </div>
    </div>
    
    <section id="tour_details_main" class="section_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="tour_details_leftside_wrapper">
                    <div class="tour_details_heading_wrapper">
                        <div class="tour_details_top_heading">
                            <h2><?php echo $smestaj->naziv?></h2>
                            <h5><i class="fas fa-map-marker-alt"></i> <?php echo $grad_ime?></h5>
                        </div>
                        <div class="tour_details_top_heading_right">
                            <h4><?php echo convertStars($broj_zvezdica);?></h4>
                            <h6><?php echo $broj_zvezdica;?>/5</h6>
                            <p></p>
                        </div>
                    </div>
                    <div class="tour_details_top_bottom">
                        <div class="toru_details_top_bottom_item">
                            <div class="tour_details_top_bottom_icon"><img src="data:image/jpg;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAZCAYAAADAHFVeAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAF1SURBVHgB1VXNWcMwDH0F7mQDfOUWbhzDBmwATNBu0DBB2YAyAd0gZQKyQdIbN9IJioSlxkntlp+639f3fa+1Y1kvsixlAMAQJ8QEcfFwSj+vxGviB+IhJV6eyaQk3iAeCuL5CQ6Ioxcbwh7b5BBiA/k3voVCxrEvyPHnLAitMy66AvHA/kuObAHbqjLEQSr+a57cElfCMfaLseObdb5VP4X7FFQh9btu9IU8nAYEE30zgTvOsPnFUKFnYoXefUhFrEIb9siz+UKcr2SPkXEOf0RKtltffe76V8Q34lyoYMN7GS/RjVYxdOZL2c8+Z7DNosYP4SYZTmSZzHUtxz9h0B7te0BM1/m4tn7td3WQO7QNdRGw4aNvRGiEP4oZ2FyVMp8F7Eq0Revm7ldiGlUj83nAbio2Kro1Oh8MbC44T1ovigzdnDGe5Bnv2Zm7PtwbWPU2Zx6xBN0azX1OQ8fItVITH2Hrr3HWSmHtPGvE7gXtcW7gCxSAbRAA5uhSAAAAAElFTkSuQmCC" alt="icon"></div>
                            <div class="tour_details_top_bottom_text">
                                <p>Klima</p>
                            </div>
                        </div>
                        <div class="toru_details_top_bottom_item">
                            <div class="tour_details_top_bottom_icon"><img src="data:image/jpg;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAVCAYAAACzK0UYAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAB8SURBVHgB7ZTRCYAgFEXvawJHcYQ2q42qCXKUNtANzEcSIRSRvvDDA/d9qLwDflzgYAixIb5gbNx7CrxgRopGBTkcRZsoHX6gSZqkSSqSOMjiWLJAlpkHN/AEmZpfeT9djArlK3/jQTeXOkNo3jzqkfdFOl1ID6KvmPRgB3tvXNj0lksBAAAAAElFTkSuQmCC" alt="icon"></div>
                            <div class="tour_details_top_bottom_text">
                                <p>Pametan televizor</p>
                            </div>
                        </div>
                        <div class="toru_details_top_bottom_item">
                            <div class="tour_details_top_bottom_icon"><img src="data:image/jpg;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAAXCAYAAADgKtSgAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAADpSURBVHgB7VTbDYJAEBwfBVCCViAdiBVoB2IFhk+rMHagHWgFWAIdyK9f0AEusMbNwZ6BeDExTjJwuZmd7D1yALAmZsTig7xxbjUoHDAb8MAJhnCI3ww/EjfEVNET1k+wQDvtkPVY0WPWQy3D7DwiLizdasi5LpKTY8NULvWK7sjb6saGwVYsde+Nr9Llthygd30mTgUvii/hHJjhW6KvFK1QPxNPLhVfwDkV5LZ40Jdr09q8jfASvmnogIA4kxNm+B79MMHr3qvhEnP+e5bAUPga+D+53wnP4QbpiD531Pe7z93WUDa8ewC1UWFlwznDrwAAAABJRU5ErkJggg==" alt="icon"></div>
                            <div class="tour_details_top_bottom_text">
                                <p>Teretana</p>
                            </div>
                        </div>
                        <div class="toru_details_top_bottom_item">
                            <div class="tour_details_top_bottom_icon"><img src="data:image/jpg;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAUCAYAAAB4d5a9AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAEHSURBVHgBtZQPEYIwFMY/SYANZgMi2MAKkAAbQAMjYAPOBNgAGowGRtAHPBTGNjaV3913u7G9v3vHDnaOpBNJkCJeR1pWQ7qR7vAgJGWkB+npIUmKlUS0pF84NwXTZl/+6FxVwX57BEdfM+oqrEkVry4VS/ZvraC7lMPc54hbY0uyS8qY9Rl+xJZgfaTph1rJPGQHBZ9JtimxfGChCVSPBxVnX2HyWHCbNqlU3dlfJ/4EDIRYVrimEvMEVyk8A0wDOSNhb49tzBcEhiCtZp+Q9qQDr4nmXgMPBIapGOdcWO5VDve8yDD0PcVGXDDvf44NUB9cuhoGcKdd2f8FgU8177/rVgh48gJTpMs1B6Cu8wAAAABJRU5ErkJggg==" alt="icon"></div>
                            <div class="tour_details_top_bottom_text">
                                <p>Besplatan Wi-fi</p>
                            </div>
                        </div>
                    </div>
                    <div class="tour_details_img_wrapper">
                        <div class="image__gallery">
                            <div class="gallery">
                                <div class="gallery__slideshow">
                                    <div class="gallery__slides">
                                        <div class="gallery__slide">
                                        <?php
                                            $tipovi_soba = $db->action('SELECT DISTINCT tip', 'soba', array('smestaj_id', '=', $smestaj->smestaj_id))->results();
                                            srand($smestaj->smestaj_id);
                                            $random = [];
                                            for ($i = 0; $i < 6; ++$i) {
                                                $random[] = rand(1, count($tipovi_soba));
                                            }
                                            $brojac = 1;
                                            foreach ($random as $key) {
                                                $slika = $db->action('SELECT slika', 'soba_ima_sliku', array('soba_id', '=', $tipovi_soba[$key - 1]->tip))->first()->slika;
                                                if ($brojac == 1) {
                                                    echo'<img class="gallery__img" src="' . $slika . '" alt="" style="display: block;">';
                                                } else {
                                                    echo'<img class="gallery__img" src="' . $slika . '"alt="" style="display: none;">';
                                                }
                                                $brojac++;
                                            }
                                        ?>
                                        </div>
                                    </div><a class="gallery__prev">❯</a><a class="gallery__next">❮</a>
                                </div>
                                <div class="gallery__content">
                                    <div class="gallery__items">
                                        <div class="gallery__item gallery__item--is-active">
                                        <?php
                                        $brojac = 1;
                                        foreach ($random as $key) {
                                            $slika = $db->action('SELECT slika', 'soba_ima_sliku', array('soba_id', '=', $tipovi_soba[$key - 1]->tip))->first()->slika;
                                            if ($brojac == 1) {
                                                echo'<img class="gallery__img-img" src="' . $slika . '"></div>';
                                            } else {
                                                echo'<div class="gallery__item"><img class="gallery__item-img" src="' . $slika . '"></div>';
                                            }
                                            $brojac++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tour_details_boxed">
                        <h3 class="heading_theme"><?php echo $naziv_aranzmana?></h3>
                        <div class="tour_details_boxed_inner">
                            <p>DAN 1: <?php if (in_array($prevoz->tip, array('bus', 'train', 'cruise', 'airplane'))) echo 'Polazak sa ';?> <?php echo prevoz_lokacija($prevoz->tip)?> u vreme <?php echo $datum_pol->format('H:i')?>. Nakon toga 
                            <?php $aktivnost = $db->get('aktivnosti', array('akt_id', '=', $aktivnosti[0]->akt_id))->first(); echo $aktivnost->naziv;?>.
                            </p>
                            <?php
                            for ($i = 2; $i < $trajanje_aranzmana; $i++) {
                                echo '<p> DAN ';
                                echo $i . ': ';
                                $aktivnost = $db->get('aktivnosti', array('akt_id', '=', $aktivnosti[$i - 1]->akt_id))->first();
                                echo $aktivnost->naziv;
                                echo '.</p>';
                            }
                            ?>
                            <p>DAN <?php echo $trajanje_aranzmana; ?>: Poseta 
                            <?php
                            echo $db->get('grad', array('g_id', '=', $aktivnosti_u_gradu[0]->g_id))->first()->ime . 'u i ';
                            $aktivnost = $db->get('aktivnosti', array('akt_id', '=', $aktivnosti_u_gradu[0]->akt_id))->first(); echo $aktivnost->naziv;?>.
                            Dolazak u Kragujevac u <?php echo convert_hours((int) $datum_odl->format('H'));?> satima.</p>
                        </div>
                    </div>
                    <div class="tour_details_boxed">
                        <h3 class="heading_theme">Napomene</h3>
                        <div class="tour_details_boxed_inner">
                            <?php echo $aranzman->nap?>
                        </div>
                    </div>
                    <div class="tour_details_boxed">
                        <h3 class="heading_theme">Izaberite vasu sobu:</h3>
                        <div class="room_select_area">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="room_booking_area">
                                        <div class="tour_search_form">
                                            <form action="!#">
                                                <div class="row">
                                                    <div class="datumi">
                                                        <div class="form_search_date">
                                                            <div class="flight_Search_boxed date_flex_area">
                                                                <div class="Journey_date">
                                                                    <p>Datum polaska</p><input type="text" value="<?php echo $datum_pol->format('d-m-Y')?>" disabled>
                                                                </div>
                                                                <div class="Journey_date">
                                                                    <p>Datum povratka</p><input type="text" value="<?php echo $datum_odl->format('d-m-Y')?>" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    <div class="row">
                                                    <div class="form-group col-md-6">
                                                    <label for="beds">Broj kreveta:</label>
                                                    <select class="form-control brojKreveta" name="brojKreveta" id="beds">
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                    </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                    <label for="room_type">Tip sobe:</label>
                                                    <select class="form-control tipSobe" name="tipSobe" id="room_type">
                                                        <option value='Soba'>Soba (Standardna)</option>
                                                        <option value='Studio'>Studio</option>
                                                        <option value='Family'>Family</option>
                                                        <option value='Suite'>Suite</option>
                                                        <option value='Superior'>Superior</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                    <label for="min_price">Cena od:</label>
                                                    <input type="number" class="form-control minCena" name="minCena" id="min_price">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                    <label for="max_price">Cena do:</label>
                                                    <input type="number" class="form-control" name="maxCena" id="max_price">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                    <label for="sort_price">Sortiraj po ceni:</label>
                                                    <select class="form-control" name="sortPrice" id="sort_price">
                                                        <option value="asc">Od najniže do najviše</option>
                                                        <option value="desc">Od najviše do najniže</option>
                                                    </select>
                                                </div>
                                                </div>
                                                <input type="hidden" name="aran_id" value="<?php echo $aran_id?>">
                                                <input type="hidden" name="broj_zvezdica" value="<?php echo $broj_zvezdica?>">
                                                <div class="form-group">
                                                <div class="book_button">
                                                <button class="book_button_1" type="submit" form="' . $id . '" value="submit" name="pretraziSobe">Pretraži<span></span><span></span><span></span></button>
                                                </div>
                                                </div>
                                                </div>
                                        <div id='prikazSoba'>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-lg-4">
                <div class="tour_details_right_sidebar_wrapper">
                    <div class="tour_detail_right_sidebar">
                        <div class="tour_details_right_boxed">
                            <div class="tour_details_right_box_heading">
                                <h3>Cena Prevoza</h3>
                            </div>
                            <div class="tour_package_bar_price">
                                <h3>$<?php echo $prevoz->cena ?><sub> Prevoz <?php echo prevoz_prevod($prevoz->tip)?></sub></h3>
                            </div>
                        </div>
                    </div>
                </div>
                            <?php echo '
                            <form action="booking.php" method="post" id="' . $aran_id . '" name="' . $aran_id . '>
                                    <div class="tour_details_right_sidebar_wrapper">
                                    <div class="tour_detail_right_sidebar">
                                        <div class="tour_details_right_boxed">
                                            <div class="book_buttond">
                                                    <button class="book_button_1" type="submit" form="' . $aran_id . '" value="' . $aran_id . '" name="aran_id">Zakaži<span></span><span></span><span></span></button>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                            </form>';?>
            </div>
        </div>
    </div>
</section>
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
</body>

<script src="scripts/hotel.js"></script>
</html>
