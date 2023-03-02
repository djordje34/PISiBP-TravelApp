<?php
declare(strict_types=1);

function GET_to_link()
{
    $search_parameters = $_GET;
    $temp = '';
    foreach ($search_parameters as $name => $value) {
        if (!in_array($name, array('stavke', 'strana'))) {
            $temp .= '&' . $name . '=' . $value;
        }
    }
    return $temp;
}
