<?php
declare(strict_types=1);

function escape($string)
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}
