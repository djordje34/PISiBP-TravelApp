<?php
declare(strict_types=1);

function convert_hours($sati)
{
    $string = '';
    if ($sati > 2 && $sati <= 8) {
        $string = 'jutarnjim';
    }
    if ($sati > 8 && $sati <= 14) {
        $string = 'prepodnevnim';
    }
    if ($sati > 14 && $sati <= 20) {
        $string = 'popodnevnim';
    }
    if ($sati > 20 || $sati <= 2) {
        $string = 'vecernjim';
    }
    return $string;
}
