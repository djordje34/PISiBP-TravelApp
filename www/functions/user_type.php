<?php

function User_type($tip)
{
    $table = '';
    if ($tip == 1) {
        $table = 'zaposleni';
    } elseif ($tip == 2) {
        $table = 'admin';
    }
    return $table;
}
