<?php
declare(strict_types=1);

class Redirect
{
    public static function to($location = null)
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php';
                        break;
                    default:
                        throw new RuntimeException();
                        break;
                }
            }
            header('Location: ' . $location);
            exit();
        }
    }

    #Uprostena prethodna funkcija->phpunit ne supportuje static fn mock/stub
    public static function mimicTo($location = null)
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        return 404;
                    default:
                        throw new RuntimeException();
                }
            }
            return $location;
        }
    }
}
