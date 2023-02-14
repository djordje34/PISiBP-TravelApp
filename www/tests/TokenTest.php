<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function test_token_integrity()
    {
        $new_token = Token::generate();
        $isIt = Token::check($new_token);
        $this->assertTrue($isIt);
    }
    public function test_fake_token()
    {
        $fake_token = password_hash(random_bytes(256), PASSWORD_BCRYPT);
        $isIt = Token::check($fake_token);
        $this->assertFalse($isIt);
    }
}
