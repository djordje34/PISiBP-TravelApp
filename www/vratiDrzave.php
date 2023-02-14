<?php

require_once 'core/init.php';
if (Input::get('vratiDrzave') && $_POST['vratiDrzave']) {
    $db = DB::getInstance();
    $kontinent_id = $db->get('kontinent', array('ime', '=', Input::get('vratiDrzave')))->first()->k_id;
    $drzave = $db->get('drzava', array('k_id', '=', $kontinent_id))->results();
    $return_value = '<select name="drzava" id="drzava" class="form-control">
                    <option class=option value="">Sve</option>';
    foreach ($drzave as $drzava) {
        $return_value .= '<option class=option value="' . $drzava->ime . '">' . $drzava->ime . '</option>';
    }
    $return_value .= '</select>';
    echo $return_value;
}
if (!$_POST['vratiDrzave']) {
    $db = DB::getInstance();
    $drzave = $db->query('SELECT ime FROM drzava')->results();
    $return_value = '<select name="drzava" id="drzava" class="form-control">
                    <option class=option value="">Sve</option>';
    foreach ($drzave as $drzava) {
        $return_value .= '<option class=option value="' . $drzava->ime . '">' . $drzava->ime . '</option>';
    }
    $return_value .= '</select>';
    echo $return_value;
}
