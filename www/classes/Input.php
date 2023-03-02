<?php

declare(strict_types=1);

class Input
{
    public static function exists($type = 'post')
    {
        $type = strtolower($type);
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            case 'put':
                return (!empty($_PUT)) ? true : false;
                break;
            case 'head':
                return (!empty($_HEAD)) ? true : false;
                break;
            case 'cookie':
                return (!empty($_COOKIE)) ? true : false;
                break;
            case 'files':
                return (!empty($_FILES)) ? true : false;
                break;
            case 'env':
                return (!empty($_ENV)) ? true : false;
                break;
            case 'request':
                return (!empty($_REQUEST)) ? true : false;
                break;
            case 'session':
                return (!empty($_SESSION)) ? true : false;
                break;
            default:
                throw new Exception('Ne postoji takav zahtev');
                break;
        }
    }
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return escape($_POST[$item]);
        } elseif (isset($_GET[$item])) {
            return escape($_GET[$item]);
        }
        return '';
    }
}
