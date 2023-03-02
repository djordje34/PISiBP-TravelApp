<?php
declare(strict_types=1);

function convertStars($broj_zvezdica)
{
    $ocena = '';
    if ((int) $broj_zvezdica < 1.5) {
        $ocena = 'Lose';
    } elseif ((int) $broj_zvezdica < 2.5) {
        $ocena = 'Dovoljno';
    } elseif ((int) $broj_zvezdica < 3.5) {
        $ocena = 'Dobro';
    } elseif ((int) $broj_zvezdica < 4.5) {
        $ocena = 'Vrlo Dobro';
    } else {
        $ocena = 'Odlicno';
    }
    return $ocena;
}
