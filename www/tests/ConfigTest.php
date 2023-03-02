<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function test_error_assure()
    {
        $this->assertFalse(Config::get(implode(str_split(random_bytes(30)))));
    }
}

?>