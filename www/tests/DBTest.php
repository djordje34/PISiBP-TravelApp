<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class DBTest extends TestCase
{
    public function test_if_returns_singleton()
    {
        #given CLASS DB, when getInstance called, then check if singleton is working
        $db =  DB::getInstance();
        $clone =  DB::getInstance();

        $this->assertEquals($db, $clone);
    }
    public function test_if_error_cleared()
    {
        $db =  DB::getInstance();
        $this->assertFalse($db->error());
    }
    public function test_if_PDO_query_concrete()
    {
        $this->expectException('TypeError');
        $db = DB::getInstance();
        $sql = "SELECT * from aranzmani WHERE aran_id = ?";
        $db->query($sql, 'abc');
    }
    public function test_if_PDO_query_checks()
    {
        $this->expectException('PDOException');
        $db = DB::getInstance();
        $sql = "SELECT * from aranzman WHERE aran_id = ";
        $db->query($sql, ['f']);
    }
    public static function provideFakeActions()
    {
        return [
            ["SELECT *","aranzmani",["aran_id","==","1"]],
            ["SELECT *","aranzmani",["aran_id","!=","jedan"]],
            ["SELECT *","aranzmani",["aran_id","-","41"]],
            ["SELECT *","aranzmani",["aran_id","=="]]
        ];
    }
    /**
    * @dataProvider provideFakeActions
    */
    public function test_if_action_foolproof($action, $table, $where)
    {
        $db = DB::getInstance();
        $check = $db->action($action, $table, $where);
        $this->assertEquals($check, false, "" . ($check));
    }
    public function test_if_insert_foolproof()
    {
        $db = DB::getInstance();
        $check = $db->insert("aranzmani", []);
        $this->assertEquals(false, $check);
    }
    public function test_if_update_user()
    {
        $db = DB::getInstance();
        $table = "korisnik";
        $fields = ["tip" => "1"];
        $db->insert($table, $fields);
        $res = $db->query("SELECT korisnik_id,tip FROM `korisnik` WHERE korisnik_id in (SELECT MAX(korisnik_id) from korisnik);");

        $id = $res->first()->korisnik_id;
        $db->updateUser("korisnik", $id, ["tip" => "2"]);

        $res = $db->query("SELECT korisnik_id,tip FROM `korisnik` WHERE korisnik_id in (SELECT MAX(korisnik_id) from korisnik);");

        $idN = $res->first()->korisnik_id;
        $db->delete("korisnik", ["korisnik_id","=",$idN]);
        $this->assertEquals($id, $idN);
    }
}
