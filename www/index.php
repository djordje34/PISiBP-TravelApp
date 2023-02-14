<?php
require_once 'core/init.php';
$db = DB::getInstance();
$grad_ima_sliku = (int) $db->query('SELECT COUNT(*) AS count FROM grad_ima_sliku')->first()->count;
if ($grad_ima_sliku == 0) {
    Redirect::to('loading.php');
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
<!-- CSS only -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous"/><link rel="stylesheet" href="styles/combined.css">
<link rel="stylesheet" type="text/css" href="styles/combined.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="styles/intro.css">

<!-- CSS only -->

    <title>Dobrodoslica</title>
</head>
<body>
<div class="super_container">
        <div class="home">
                <!-- Home Slider -->

                <div class="home_slider_container">
                    
                    <div class="owl-carousel owl-theme home_slider">

                        <!-- Slider Item -->
                        <div class="owl-item home_slider_item">
                            <!-- Image by https://com/@anikindimitry -->
                            <div class="home_slider_background" style="background-image:url(imgs/home_slider.jpg)"></div>

                            <div class="home_slider_content text-center">
                                <div class="home_slider_content_inner" data-animation-in="bounceIn" data-animation-out="animate-out zoomOutUp">
                                    <h1>Upoznajte svet</h1>
                                    <h1>Zajedno sa nama</h1>
                                    <div class="button home_slider_button"><div class="button_bcg"></div><a href="ponude.php">Pogledajte nasu ponudu<span></span><span></span><span></span></a></div>
                                </div>
                            </div>
                        </div>

                        <!-- Slider Item -->
                        <div class="owl-item home_slider_item">
                            <div class="home_slider_background" style="background-image:url(imgs/proba2.jpg)"></div>

                            <div class="home_slider_content text-center">
                                <div class="home_slider_content_inner" data-animation-in="fadeInDownBig" data-animation-out="animate-out fadeOutUpBig">
                                    <h1>Upoznajte svet</h1>
                                    <h1>Zajedno sa nama</h1>
                                    <div class="button home_slider_button"><div class="button_bcg"></div><a href="ponude.php">Pogledajte nasu ponudu<span></span><span></span><span></span></a></div>
                                </div>
                            </div>
                        </div>

                        <!-- Slider Item -->
                        <div class="owl-item home_slider_item">
                            <div class="home_slider_background" style="background-image:url(imgs/proba1.jpeg)"></div>

                            <div class="home_slider_content text-center">
                                <div class="home_slider_content_inner" data-animation-in="rotateIn" data-animation-out="animate-out fadeOutDownBig">
                                    <h1>Upoznajte svet</h1>
                                    <h1>Zajedno sa nama</h1>
                                    <div class="button home_slider_button"><div class="button_bcg"></div><a href="ponude.php">Pogledajte nasu ponudu<span></span><span></span><span></span></a></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Home Slider Nav - Prev -->
                    <div class="home_slider_nav home_slider_prev">
                        <svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            width="28px" height="33px" viewBox="0 0 28 33" enable-background="new 0 0 28 33" xml:space="preserve">
                            <defs>
                                <linearGradient id='home_grad_prev'>
                                    <stop offset='0%' stop-color='#fa9e1b'/>
                                    <stop offset='100%' stop-color='#8d4fff'/>
                                </linearGradient>
                            </defs>
                            <path class="nav_path" fill="#F3F6F9" d="M19,0H9C4.029,0,0,4.029,0,9v15c0,4.971,4.029,9,9,9h10c4.97,0,9-4.029,9-9V9C28,4.029,23.97,0,19,0z
                            M26,23.091C26,27.459,22.545,31,18.285,31H9.714C5.454,31,2,27.459,2,23.091V9.909C2,5.541,5.454,2,9.714,2h8.571
                            C22.545,2,26,5.541,26,9.909V23.091z"/>
                            <polygon class="nav_arrow" fill="#F3F6F9" points="15.044,22.222 16.377,20.888 12.374,16.885 16.377,12.882 15.044,11.55 9.708,16.885 11.04,18.219 
                            11.042,18.219 "/>
                        </svg>
                    </div>
                    
                    <!-- Home Slider Nav - Next -->
                    <div class="home_slider_nav home_slider_next">
                        <svg version="1.1" id="Layer_3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        width="28px" height="33px" viewBox="0 0 28 33" enable-background="new 0 0 28 33" xml:space="preserve">
                            <defs>
                                <linearGradient id='home_grad_next'>
                                    <stop offset='0%' stop-color='#fa9e1b'/>
                                    <stop offset='100%' stop-color='#8d4fff'/>
                                </linearGradient>
                            </defs>
                        <path class="nav_path" fill="#F3F6F9" d="M19,0H9C4.029,0,0,4.029,0,9v15c0,4.971,4.029,9,9,9h10c4.97,0,9-4.029,9-9V9C28,4.029,23.97,0,19,0z
                        M26,23.091C26,27.459,22.545,31,18.285,31H9.714C5.454,31,2,27.459,2,23.091V9.909C2,5.541,5.454,2,9.714,2h8.571
                        C22.545,2,26,5.541,26,9.909V23.091z"/>
                        <polygon class="nav_arrow" fill="#F3F6F9" points="13.044,11.551 11.71,12.885 15.714,16.888 11.71,20.891 13.044,22.224 18.379,16.888 17.048,15.554 
                        17.046,15.554 "/>
                        </svg>
                    </div>

                    <!-- Home Slider Dots -->

                    <div class="home_slider_dots">
                        <ul id="home_slider_custom_dots" class="home_slider_custom_dots">
                            <li class="home_slider_custom_dot active"><div></div>01.</li>
                            <li class="home_slider_custom_dot"><div></div>02.</li>
                            <li class="home_slider_custom_dot"><div></div>03.</li>
                        </ul>
                    </div>
                    
                </div>

    </div>

    <div class="intro">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="intro_title text-center">Zašto baš mi?</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="intro_text text-center">
                        <p>Samo neke od nasih najtraženijih ponuda:</p>
                    </div>
                </div>
            </div>
            <div class="row intro_items">

                <!-- Intro Item -->

                <div class="col-lg-4 intro_col">
                    <div class="intro_item">
                        <div class="intro_item_overlay"></div>
                        <div class="intro_item_background" style="background-image:url(imgs/landing_1.jpg)"></div>
                        <div class="intro_item_content d-flex flex-column align-items-center justify-content-center">
                            <div class="button intro_button"><div class="button_bcg"></div><a href="http://localhost:8001/ponude.php?stavke=50&strana=1&ime=&kontinent=Afrika&drzava=Egipat&prevoz=&datum_polaska=&datum_odlaska=">Pogledati više<span></span><span></span><span></span></a></div>
                            <div class="intro_center text-center">
                                <h1>Egipat</h1>
                                <div class="rating rating_5">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intro Item -->

                <div class="col-lg-4 intro_col">
                    <div class="intro_item">
                        <div class="intro_item_overlay"></div>
                        <div class="intro_item_background" style="background-image:url(imgs/landing_2.jpg)"></div>
                        <div class="intro_item_content d-flex flex-column align-items-center justify-content-center">
                            <div class="button intro_button"><div class="button_bcg"></div><a href="http://localhost:8001/ponude.php?stavke=50&strana=1&ime=&kontinent=Azija&drzava=Kina&prevoz=&datum_polaska=&datum_odlaska=">Pogledati više<span></span><span></span><span></span></a></div>
                            <div class="intro_center text-center">
                                <h1>Kina</h1>
                                <div class="rating rating_5">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intro Item -->

                <div class="col-lg-4 intro_col">
                    <div class="intro_item">
                        <div class="intro_item_overlay"></div>
                        <div class="intro_item_background" style="background-image:url(imgs/landing_3.jpg)"></div>
                        <div class="intro_item_content d-flex flex-column align-items-center justify-content-center">
                            <div class="button intro_button"><div class="button_bcg"></div><a href="http://localhost:8001/ponude.php?stavke=50&strana=1&ime=&kontinent=Evropa&drzava=Francuska&prevoz=&datum_polaska=&datum_odlaska=">Pogledati više<span></span><span></span><span></span></a></div>
                            <div class="intro_center text-center">
                                <h1>Francuska</h1>
                                <div class="rating rating_5">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 intro_col">
                    <div class="intro_item">
                        <div class="intro_item_overlay"></div>
                        <div class="intro_item_background" style="background-image:url(imgs/landing_4.jpg)"></div>
                        <div class="intro_item_content d-flex flex-column align-items-center justify-content-center">
                            <div class="button intro_button"><div class="button_bcg"></div><a href="http://localhost:8001/ponude.php?stavke=50&strana=1&ime=&kontinent=Australija%20/%20Okeanija&drzava=Australija&prevoz=&datum_polaska=&datum_odlaska=">Pogledati više<span></span><span></span><span></span></a></div>
                            <div class="intro_center text-center">
                                <h1>Australija</h1>
                                <div class="rating rating_5">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intro Item -->

                <div class="col-lg-4 intro_col">
                    <div class="intro_item">
                        <div class="intro_item_overlay"></div>
                        <div class="intro_item_background" style="background-image:url(imgs/landing_5.jpg)"></div>
                        <div class="intro_item_content d-flex flex-column align-items-center justify-content-center">
                            <div class="button intro_button"><div class="button_bcg"></div><a href="http://localhost:8001/ponude.php?stavke=50&strana=1&ime=&kontinent=Evropa&drzava=Rusija&prevoz=&datum_polaska=&datum_odlaska=">Pogledati više<span></span><span></span><span></span></a></div>
                            <div class="intro_center text-center">
                                <h1>Rusija</h1>
                                <div class="rating rating_5">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intro Item -->

                <div class="col-lg-4 intro_col">
                    <div class="intro_item">
                        <div class="intro_item_overlay"></div>
                        <div class="intro_item_background" style="background-image:url(imgs/landing_6.jpg)"></div>
                        <div class="intro_item_content d-flex flex-column align-items-center justify-content-center">
                            <div class="button intro_button"><div class="button_bcg"></div><a href="http://localhost:8001/ponude.php?stavke=50&strana=1&ime=&kontinent=Evropa&drzava=Nema%C4%8Dka&prevoz=&datum_polaska=&datum_odlaska=">Pogledati više<span></span><span></span><span></span></a></div>
                            <div class="intro_center text-center">
                                <h1>Nemačka</h1>
                                <div class="rating rating_5">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

  <div class="testimonials">
        <div class="test_border"></div>
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h2 class="section_title">Sta nasi klijenti kažu o nama:</h2>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    
                    <!-- Testimonials Slider -->

                    <div class="test_slider_container">
                        <div class="owl-carousel owl-theme test_slider">

                            <!-- Testimonial Item -->
                            <div class="owl-item">
                                <div class="test_item">
                                    <div class="test_image"><img src="cards/djordje2.jfif" alt="https://com/@anniegray"></div>
                                    <div class="test_content_container">
                                        <div class="test_content">
                                            <div class="test_item_info">
                                                <div class="test_name">Djordje Karisić</div>
                                                <div class="test_date">Jan 22 2023</div>
                                            </div>
                                            <div class="test_quote_title">" Best holliday ever "</div>
                                            <p class="test_quote_text">Nullam eu convallis tortor. Suspendisse potenti. In faucibus massa arcu, vitae cursus mi hendrerit nec.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Testimonial Item -->
                            <div class="owl-item">
                                <div class="test_item">
                                    <div class="test_image"><img src="cards/mladen.jpg" alt="https://com/@tschax"></div>
                                    <div class="test_content_container">
                                        <div class="test_content">
                                            <div class="test_item_info">
                                                <div class="test_name">Mladen Cvetković</div>
                                                <div class="test_date">Jan 22 2023</div>
                                            </div>
                                            <div class="test_quote_title">" Best holliday ever "</div>
                                            <p class="test_quote_text">Nullam eu convallis tortor. Suspendisse potenti. In faucibus massa arcu, vitae cursus mi hendrerit nec.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Testimonial Item -->
                            <div class="owl-item">
                                <div class="test_item">
                                    <div class="test_image"><img src="cards/aleksa.jpg" alt=""></div>
                                    <div class="test_content_container">
                                        <div class="test_content">
                                            <div class="test_item_info">
                                                <div class="test_name">Aleksa Vuković</div>
                                                <div class="test_date">Jan 22 2023</div>
                                            </div>
                                            <div class="test_quote_title">" Best holliday ever "</div>
                                            <p class="test_quote_text">Nullam eu convallis tortor. Suspendisse potenti. In faucibus massa arcu, vitae cursus mi hendrerit nec.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Testimonial Item -->
                            <div class="owl-item">
                                <div class="test_item">
                                    <div class="test_image"><img src="cards/cole.jpg" alt=""></div>
                                    <div class="test_content_container">
                                        <div class="test_content">
                                            <div class="test_item_info">
                                                <div class="test_name">Filip Stefanović</div>
                                                <div class="test_date">Jan 22 2023</div>
                                            </div>
                                            <div class="test_quote_title">" Best holliday ever "</div>
                                            <p class="test_quote_text">Nullam eu convallis tortor. Suspendisse potenti. In faucibus massa arcu, vitae cursus mi hendrerit nec.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


  <div class="trending">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h2 class="section_title">Popularni hoteli</h2>
                </div>
            </div>
            <div class="row trending_container">
                <?php
                $db = DB::getInstance();
                $broj_hotela = $db->query('SELECT COUNT(*) AS broj FROM smestaj')->first()->broj;
                $sql_hoteli = "SELECT * FROM smestaj limit ?, ?";
                srand(floor(time() / (60 * 60 * 24)));
                $random_hoteli = array();
                for ($i = 0; $i < 8; $i++) {
                    $random_hoteli[] = $db->get('smestaj', array('smestaj_id', '=', rand(1, $broj_hotela)))->first();
                }
                foreach ($random_hoteli as $hotel) {
                    $grad = $db->get('grad', array('g_id', '=', $hotel->g_id))->first();
                    $slika = $db->get('grad_ima_sliku', array('grad_id', '=', $grad->g_id))->first()->slika;
                    $drzava = $db->get('drzava', array('d_id', '=', $grad->d_id))->first();
                    echo '<div class="col-lg-3 col-sm-6">
                    <div class="trending_item clearfix">
                        <div class="trending_image"><img src="' . $slika . '" alt="imgs/proba1.jpg"></div>
                        <div class="trending_content">
                            <div class="trending_title"><a href="http://localhost:8001/ponude.php?stavke=25&strana=1&ime=' . $hotel->naziv . '&kontinent=&prevoz=&datum_polaska=&datum_odlaska=">' . $hotel->naziv . '</a></div>
                            <div class="trending_location"> ' . $grad->ime . ', ' . $drzava->ime . '</div>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>


        <div class="row d-flex justify-content-center align-items-center h-100 m-0">

        <div>
        <p class= "helloworld m-5">UPOZNAJTE NAŠ TIM</p>
        </div>
        
        <div class="card m-3" style="width: 18rem;">
    <img class="card-img-top" src="cards/djordje2.jfif" alt="Card image cap">
    <div class="card-body">
        <h5 class="card-title">Karišić Đorđe</h5>
        <h6 class="card-subtitle mb-2 text-muted">QA Engineer</h6>
        
    </div>
    </div>
    <div class="card m-3" style="width: 18rem;">
    <img class="card-img-top" src="cards/mladen.jpg" alt="Card image cap" >
    <div class="card-body">
        <h5 class="card-title">Cvetković Mladen</h5>
        <h6 class="card-subtitle mb-2 text-muted">Frontend Engineer</h6>
        
    </div>
    </div>
    <div class="card  m-3" style="width: 18rem;">
    <img class="card-img-top" src="cards/aleksa.jpg" alt="Card image cap" >
    <div class="card-body">
        <h5 class="card-title">Vuković Aleksa</h5>
        <h6 class="card-subtitle mb-2 text-muted">System Engineer</h6>
        
    </div>
    </div>

    <div class="card  m-3" style="width: 18rem;">
    <img class="card-img-top" src="cards/cole.jpg" alt="Card image cap" >
    <div class="card-body">
        <h5 class="card-title">Stefanović Filip</h5>
        <h6 class="card-subtitle mb-2 text-muted">Backend Engineer</h6>
        
    </div>
    </div>







    <footer class="bg-dark text-center text-white p-0">
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
</div>

<script src="scripts/jquery-3.2.1.min.js"></script>
<script src="scripts/bootstrap.min.js"></script>
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="scripts/custom.js"></script>


</body>
</html>
