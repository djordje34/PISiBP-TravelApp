<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function duplicateTableUser()
    {
        $this->deleteTableUser();
        $sql = "CREATE TABLE korisnikFake(korisnik_id INT AUTO_INCREMENT PRIMARY KEY,
         tip VARCHAR(255))";
         $db = DB::getInstance();
         $db->query($sql);
    }
    public function deleteTableUser()
    {
        $sql = "DROP TABLE IF EXISTS korisnikFake";
        $db = DB::getInstance();
        $db->query($sql);
    }

    public static function provideFalseInfo()
    {
        return [
            ['admin',[]],
            ['worker',[1,2]],
            ['randomstring',[3,4]],
            [0,'0'],
        ];
    }
    /**
    * @dataProvider provideFalseInfo
    */
    public function test_if_create_foolproof($tip, $arr)
    {
        $this->expectException(Exception::class);
        $user = new User();
        $user->create($tip, $arr);
    }
    public function test_if_finds_fake_table()
    {
        $this->deleteTableUser();
        $this->duplicateTableUser();
        $user = new User();
        $this->expectExceptionMessage('Nepostojeca tabela');
        $table = "korisnikFake";
        $fields = ["tip" => "string", "rand" => "neki"];
        $user->create($table, $fields);
        $this->deleteTableUser();
    }
    public static function provideInvalidCredentials()
    {
        return [
            ['invalid@mail.address','123'],
            ['',''],
            ['randomstring','123456789'],
            [0,'0'],
            ['129000dd@ds..','4096543####l;']
        ];
    }
    /**
    * @dataProvider provideInvalidCredentials
    */
    public function test_login_invalid_credentials($username, $pw)
    {
        $user = new User();

        $response = $user->login($username, $pw);
        $this->assertEquals($response, false);
    }
    public function test_permission_level()
    {
        $user = new User();

        $this->assertNull($user->permissionLevel());
    }
    public function test_login_valid_credentials()
    {
        $user = new User();

        $response = $user->login('djordje00karisic@gmail.com', '12345678');
        $this->assertEquals($response, true);
    }

    public function test_logged_not_in_db_update()
    {
        $user = new User();
        $DBMock = $this->getMockBuilder('DB')->getMock();
        $DBMock->expects($this->any())->method('updateUser')->will($this->returnValue(false));
        $user->login('djordje00karisic@gmail.com', '12345678');
        $user->setDB($DBMock);
        $this->expectExceptionMessage('Desio se problem tokom azuriranja.');
        $user->update('admin', 1, [1]);
    }
    public static function provideUserInfoForFind()
    {
        $db = DB::getInstance();
        $res = $db->query("SELECT korisnik_id,tip FROM `korisnik` WHERE korisnik_id in (SELECT MIN(korisnik_id) from korisnik);");
        $idN = $res->first()->korisnik_id;
        $res = $db->query("SELECT email FROM `admin` WHERE korisnik_id =?;", $params = [$idN]);
        $email = $res->first()->email;

        return [
            [$idN],
            [$email]
        ];
    }
    /**
    * @dataProvider provideUserInfoForFind
    */
    public function test_user_exists($idN)
    {
        $user = new User();
        $exists = $user->find($idN);

        $this->assertEquals(true, $exists);
    }

    public function test_if_user_information()
    {
        $this->deleteTableUser();
        $user = new User();
        $check = $user->login('djordje00karisic@gmail.com', '12345678');
        $this->assertNotEquals(false, $check);
    }
    public function test_database_error_insert()
    {
        $DBMock = $this->getMockBuilder('DB')->getMock();
        $DBMock->expects($this->any())->method('insert')->will($this->returnValue(false));
        $user = new User();
        $this->expectExceptionMessage('Desio se problem tokom kreiranja naloga.');
        $user->create('zaposleni', [1,2], $DBMock);
    }


    public function test_logout()
    {
        $user = new User();

        $user->login('djordje00karisic@gmail.com', '12345678');
        $user->logout();
        $this->assertFalse($user->isLoggedIn());
    }
}
