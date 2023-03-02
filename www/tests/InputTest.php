<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    public static function provideRequestMethods()
    {
        return [
            ['POST'],
            ['GET'],
            ['PUT'],
            ['HEAD'],
            ['COOKIE'],
            ['FILES'],
            ['ENV'],
            ['REQUEST'],
            ['SESSION']
        ];
    }
        /**
    * @dataProvider provideRequestMethods
    */
    public function test_request_methods($req)
    {
        $this->assertContains(Input::exists($req), [true,false], $req . "");
    }
    public function test_fake_request_methods()
    {
        $this->expectExceptionMessage('Ne postoji takav zahtev');
        Input::exists('stuff');
    }
    public function test_input_post_values()
    {
        $_POST['ime'] = 'Djordje';
        $this->assertSame('Djordje', Input::get('ime'));
    }
    public function test_input_get_values()
    {
        $_POST = array();
        $_GET['ime'] = 'Djordje';
        $this->assertSame('Djordje', Input::get('ime'));
    }
    public function test_input_get()
    {
        $_POST = array();
        $_GET = array();
        $this->assertSame('', Input::get(random_bytes(256)));
    }
}
