<?php
declare(strict_types=1);

function prevoz_prevod($prevoz)
{
    $prevod = '';
    if ($prevoz == 'airplane') {
        $prevod = 'avionom';
    } elseif ($prevoz == 'bus') {
        $prevod = 'autobusom';
    } elseif ($prevoz == 'cruise') {
        $prevod = 'krstarenjem';
    } elseif ($prevoz == 'train') {
        $prevod = 'vozom';
    } elseif ($prevoz == 'lično vozilo') {
        $prevod = 'samostalno';
    } else {
        throw new Exception('Pogresan tip prevoza');
    }
    return $prevod;
}
function prevoz_lokacija($prevoz)
{
    $prevod = '';
    if ($prevoz == 'airplane') {
        $prevod = 'aerodrom';
    } elseif ($prevoz == 'bus') {
        $prevod = 'autobuske stanice';
    } elseif ($prevoz == 'cruise') {
        $prevod = 'luke';
    } elseif ($prevoz == 'train') {
        $prevod = 'perona';
    } elseif ($prevoz == 'lično vozilo') {
        $prevod = 'Preporuceno vreme dolaska ';
    } else {
        throw new Exception('Pogresan tip prevoza');
    }
    return $prevod;
}
