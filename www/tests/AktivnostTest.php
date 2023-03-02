<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class AktivnostTest extends TestCase
{
    public function getNumOfAkt()
    {
        $db = DB::getInstance();
        $sql = "SELECT *
        FROM akt_u_gradu;";
        $res = $db->query($sql)->count();
        return $res;
    }
    public function getAllAkt()
    {
        $db = DB::getInstance();
        $sql = "SELECT *
        FROM akt_u_gradu;";
        $res  = $db->query($sql)->results();
        return $res;
    }
    public function getNumOfHotels()
    {
        $db = DB::getInstance();
        $sql = "SELECT smestaj_id
        FROM smestaj;";
        $res = $db->query($sql)->count();
        return $res;
    }
    public function getNumOfGrad()
    {
        $db = DB::getInstance();
        $sql = "SELECT g_id
        FROM grad;";
        $res = $db->query($sql)->count();
        return $res;
    }
    public function getNumOfPossibleAkt()
    {
        $db = DB::getInstance();
        $sql = "SELECT akt_id
        FROM aktivnosti;";
        $res = $db->query($sql)->count();
        return $res;
    }

    public function test_if_all_smestaj_has_akt()
    {
        $akts = $this->getNumOfAkt();
        $htls = $this->getNumOfHotels();
        $this->AssertTrue($akts === $htls);
    }
    public function test_if_all_akt_possible()
    {
        $tester = true;
        $cap = $this->getNumOfPossibleAkt();
        $akts = range(1, $cap);
        $allAkts = $this->getAllAkt();

        foreach ($allAkts as $v_akt) {
            if (!in_array($v_akt->akt_id, $akts)) {
                $tester = false;
                break;
            }
        }
        $this->assertTrue($tester, "Ne nalaze se svi tipovi aktivnosti u tabeli grad_ima_aktivnost.");
    }

    public function test_if_grad_in_range()
    {
        $tester = true;
        $cap = $this->getNumOfGrad();
        $g_ids = range(1, $cap);
        $allAkts = $this->getAllAkt();

        foreach ($allAkts as $akt) {
            if (!in_array($akt->g_id, $g_ids)) {
                $tester = false;
                break;
            }
        }
        $this->assertTrue($tester, "Anomalije u tabeli aktivnost_u_gradu, g_id.");
    }
}
