<?php
declare(strict_types=1);

require_once 'core/init.php';
$string = '';
if (Input::get('vratiSobe')) {
    $db = DB::getInstance();
    $aran_id = (int) Input::get('aran_id');
    $aranzman = $db->get('aranzmani', array('aran_id', '=', $aran_id))->first();
    $smestaj = $db->get('smestaj', array('smestaj_id', '=', $aranzman->smestaj_id))->first();
    $broj_zvezdica = (int) Input::get('broj_zvezdica');
    $kreveti = (int) Input::get('kreveti');
    $tip = '%' . strtolower(Input::get('tip')) . '%';
    $cenaMin = 0;
    $cenaMax = 9999999999999;
    if (Input::get('cenaMin')) {
        $cenaMin = (int) Input::get('cenaMin');
    }
    if (Input::get('cenaMax')) {
        $cenaMax = (int) Input::get('cenaMax');
    }
    $checker = 1;
    $query = $db->query("SELECT s.soba_id, s.tip, s.smestaj_id ,s.rez_id, sh.id, sh.br_kreveta, sh.gen_cena, sh.opis, sh.tip FROM soba s, sobatip_hash sh WHERE s.tip = sh.id AND sh.br_kreveta=? AND LOWER(sh.tip) LIKE ? AND sh.gen_cena>? AND sh.gen_cena<? AND s.smestaj_id=? AND s.rez_id IS NULL", array($kreveti, $tip, $cenaMin, $cenaMax, $smestaj->smestaj_id));
    $broj_soba = $query->count();
    if ($broj_soba) {
        $row = $query->first();
        $checker = 1;
        $prikaz = 0;
        $images = $db->query("SELECT sh.id, ss.slika FROM sobatip_hash sh, soba_ima_sliku ss WHERE sh.id = ss.soba_id AND sh.id=?", [$row->id])->results();
        $string .= "<div class='room_book_item'>
            <div id='carouselExampleIndicators" . $row->soba_id . "' class='carousel slide' data-bs-ride='carousel' style = ''>
                <div class='carousel-inner'>";
                foreach ($images as $key => $img) {
                    $string .= "<div class='carousel-item" . str_repeat(' active', $checker) . "' id = 'prikaz" . $prikaz . "' name = 'prikaz" . $prikaz . "'>
                            <a href='" . $img->slika . "' >
                                <img class='d-block ' style='width=100%' src='" . $img->slika . "' alt='" . $row->soba_id . "slide'></a></div>";
                                        $prikaz += 1;
                                        $checker = 0;
                                    }
                                    $string .= "
                                        </div>


                                        <button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleIndicators" . $row->soba_id . "' data-bs-slide='prev'>
                                        <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                        <span class='visually-hidden'>Previous</span>
                                      </button>
                                      <button class='carousel-control-next' type='button' data-bs-target='#carouselExampleIndicators" . $row->soba_id . "' data-bs-slide='next'>
                                        <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                        <span class='visually-hidden'>Next</span>
                                      </button>

                                        </a>
                                        </div>
                                        </div>
                                        

                                        <div class='room_booking_right_side'>
                                                <div class='room_booking_heading' style='width:35%;'>
                                                    <h3><a href='/room-booking' class=''>" . $row->tip . " " . $row->br_kreveta . " krevet/a</a></h3>


                                                    <div class='room_fasa_area d-flex flex-column w-50'>";
                                                    if (strpos($row->opis, "TV") !== false) {
                                                        $string .= "        <ul class='d-row'>
                                                    <i class='fas fa-tv'>TV</i>
                                                    </ul>";
                                                    }
                                                    if (strpos($row->opis, "internet") !== false) {
                                                        $string .= "      <ul class='d-row'>
                                                    <i class='fas fa-wifi'>Wi-Fi</i>
                                                     </ul>";
                                                    }
                                                    if (strpos($row->opis, "klimu") !== false) {
                                                        $string .= "      <ul class='d-column'>
                                                     <i class='fas fa-wind'>Klima</i>
                                                     </ul>";
                                                    }
                                                    if (strpos($row->opis, "frižider") !== false) {
                                                        $string .= "
                                                     <ul class='d-column'>
                                                     <i class='fas fa-snowflake'>Frižider</i>
                                                     </ul>";
                                                    }
                                                    $string .= "
                                                </div>
                                            </div>
                                            <div class='d-flex justify-content-center align-items-center'>
                                            " . $row->opis . "
                                            </div>";
                                            $query = $db->query("SELECT * FROM prevoz, aranzmani where prevoz.p_id = aranzmani.p_id AND aranzmani.aran_id = ?", [$aran_id]);
                                            $res = $query->first();
                                            $string .= "
                                            <div class='room_person_select'>
                                                <h3>" . round($row->gen_cena + $row->gen_cena / 2 * ($broj_zvezdica - 3), 2) . "€/ <sub>Po noći" . " </sub </h3>
                                            </div>
                                        </div>
                                    
                                        ";
    }
    echo $string;
}
