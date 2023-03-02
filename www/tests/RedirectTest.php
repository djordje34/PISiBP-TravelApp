<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    public static $headers;

    public function test_redirect_no_param()
    {
        $this->assertNotTrue(Redirect::to());
    }
    public function test_redirect_num_param()
    {
        $this->expectException(RuntimeException::class);
        Redirect::to(2023);
    }

    public static function providePossibleScenarios()
    {
        return [
            [404],
            ['neki_location'],
        ];
    }
    /**
    * @dataProvider providePossibleScenarios
    */
    public function test_mimic_redirect_multiple_param($p)
    {
        $this->assertSame($p, Redirect::mimicTo($p));
    }
    public function test_mimic_redirect_exception()
    {
        $this->expectException(RuntimeException::class);
        Redirect::mimicTo(403);
    }
}
