<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class PonudeTest extends TestCase
{
    public function getPonuda()
    {
        $db = DB::getInstance();
        $sql = "SELECT *
        FROM aranzmani
        GROUP BY smestaj_id
        HAVING MAX(aran_id);";
        $res = $db->query($sql)->results();
        return $res;
    }
    public function getAllPonude()
    {
        $db = DB::getInstance();
        $sql = "SELECT *
        FROM aranzmani;";
        $res = $db->query($sql)->results();
        return $res;
    }
    public function getAllPonudeWhere($cnd)
    {
        $db = DB::getInstance();
        $sql = "SELECT *
        FROM aranzmani WHERE " . $cnd;
        $res = $db->query($sql)->results();
        return $res;
    }
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    public function test_if_titles_correct()
    {
        $tester = true;
        $ponude = $this->getPonuda();
        $months = ["Januar","Jun","Jul","Avgust","Septembar","Oktobar"];
        $godine = [2022,2023];
        $prevoz = ["avionom","autobusom","brodom","samostalni prevoz","vozom"];
#Šangaj Januar 2023 ShangriLa Palace avionom 3 dana
        $dani = [3,5,7,10,14];
        foreach ($ponude as $ponuda) {
            $toCheck = explode(" ", $ponuda->naziv);
            if (in_array($toCheck, $months) || in_array($toCheck, $godine) || in_array($toCheck, $months) || in_array($toCheck, $prevoz) || in_array($toCheck, $dani)) {
                $tester = false;
                break;
            }
        }
        $this->assertTrue($tester, "Greska u elementima iz ponude");
    }

    public function test_if_dates_correct()
    {
        $tester = true;
        $ponude = $this->getPonuda();
        $id = -1;
        foreach ($ponude as $ponuda) {
            if (!($this->validateDate($ponuda->krece)) || !($this->validateDate($ponuda->vraca))) {
                $tester = false;
                $id = $ponuda->aran_id;
                break;
            }
        }
        $this->assertEquals(true, $tester, "Greska u datumima iz ponude, (aran_id=" . $id . ")");
    }
    public function test_if_populated()
    {
        $res = $this->getAllPonude();
        $this->assertGreaterThanOrEqual(60000, count((array)$res), count((array)$res) . "");
    }
    public function test_if_invalid_populated()
    {
        $res = $this->getAllPonudeWhere("krece<now()");
        $this->assertGreaterThanOrEqual(10000, count((array)$res), count((array)$res) . "");
    }
    public function test_if_valid_populated()
    {
        $res = $this->getAllPonudeWhere("krece>now()");
        $this->assertGreaterThanOrEqual(50000, count((array)$res), count((array)$res) . "");
    }
}
