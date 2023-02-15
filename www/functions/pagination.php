<?php
declare(strict_types=1);

require_once 'core/init.php';
function pagination($broj_stvari, $trenutna_strana, $link)
{
    $return_value = '';
    $return_value .= '<li class="page-item"><a href="' . $link . '?strana=' . 1 . '" class="page-link">Prva</a></li>';
    $broj_strana = floor($broj_stvari / 10);
    if ($broj_stvari % 10 != 0) {
        $broj_strana++;
    }
    if ($broj_strana < 6) {
        for ($i = 1; $i <= $broj_strana; $i++) {
            if ($i != $trenutna_strana) {
                $return_value .= '<li class="page-item"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
            } else {
                $return_value .= '<li class="page-item active"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
            }
        }
    } else {
        if ($trenutna_strana >= 3 && $trenutna_strana < $broj_strana - 3) {
            for ($i = $trenutna_strana - 1; $i <= $trenutna_strana + 1; $i++) {
                if ($i != $trenutna_strana) {
                    $return_value .= '<li class="page-item"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
                } else {
                    $return_value .= '<li class="page-item active"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
                }
            }
        } elseif ($trenutna_strana >= 3) {
            for ($i = $trenutna_strana - 2; $i < $broj_strana - 2; $i++) {
                if ($i != $trenutna_strana) {
                    $return_value .= '<li class="page-item"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
                } else {
                    $return_value .= '<li class="page-item active"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
                }
            }
        } elseif ($trenutna_strana < 3) {
            for ($i = 1; $i <= 3; $i++) {
                if ($i != $trenutna_strana) {
                    $return_value .= '<li class="page-item"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
                } else {
                    $return_value .= '<li class="page-item active"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
                }
            }
        }
        $return_value .= '...';
        for ($i = $broj_strana - 2; $i <= $broj_strana; $i++) {
            if ($i != $trenutna_strana) {
                $return_value .= '<li class="page-item"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
            } else {
                $return_value .= '<li class="page-item active"><a href="' . $link . '?strana=' . $i . '" class="page-link">' . $i . '</a></li>';
            }
        }
    }
    $return_value .= '<li class="page-item"><a href="' . $link . '?strana=' . $broj_strana . '" class="page-link">Poslednja</a></li>';
    return $return_value;
}
