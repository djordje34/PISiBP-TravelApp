<?php
declare(strict_types=1);

class Config
{
    public static function get($path)
    {
        $config = $GLOBALS['config'];
        $path = explode('/', $path);

        foreach ($path as $bit) {
            if (is_array($config) && array_key_exists($bit, $config)) {
                $config = $config[$bit];
            } else {
                return false;
            }
        }
        return $config;
    }
}
