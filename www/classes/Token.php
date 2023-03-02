<?php
declare(strict_types=1);

class Token
{
    public static function generate()
    {
        return Session::put(Config::get('session/token_name'), password_hash(random_bytes(256), PASSWORD_BCRYPT));
    }
    public static function check($token)
    {
        $tokenName = Config::get('session/token_name');
        if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}
