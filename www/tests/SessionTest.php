<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function test_session_put()
    {
        Session::put("ime", "Djordje");

        $this->assertTrue(Session::exists('ime'));
    }
    public function test_session_delete()
    {
        Session::delete("ime");
        $this->assertFalse(Session::exists('ime'));
    }
    public function test_flash_append()
    {
        Session::flash('ime', 'Djordje');
        $this->assertTrue(Session::exists('ime'));
    }
    public function test_flash_pop()
    {
        Session::flash('ime', 'Djordje');
        $this->assertFalse(Session::exists('ime'));
    }
}
