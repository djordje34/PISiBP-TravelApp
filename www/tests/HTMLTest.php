<?php

declare (strict_types = 1);

require_once ("core/init.php");

use PHPUnit\Framework\TestCase;
class HTMLTest extends TestCase{

protected $traceError = TRUE;

public function test_if_undesirable_output(){
    $dir = 'C:/wamp64/www/PISiBP-TravelApp/';
    $appDir = new RecursiveDirectoryIterator($dir);
    $iterator = new RecursiveIteratorIterator($appDir);
    $matches = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
    $chck = True;
    $where='';
    $what='';
    foreach ($matches as $phpFile) {
        $contents = file_get_contents($phpFile[0]);
        if(preg_match('/var_dump(\S*)/s', $contents) && !ctype_upper(end(explode('\\',$phpFile[0]))[0])){
            $where = $phpFile[0];
            $what=$contents;
            $chck = False;
        }
    }
    $this->assertTrue($chck,"".$where);
    }

    public function test_if_basic_tags_closed(){
        $dir = 'C:/wamp64/www/PISiBP-TravelApp/';
        $appDir = new RecursiveDirectoryIterator($dir);
        $iterator = new RecursiveIteratorIterator($appDir);
        $matches = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        $chck = True;
        $where='';
        $what='';
        foreach ($matches as $phpFile) {
            
            $contents = file_get_contents($phpFile[0]);
            if(preg_match('/<html/', $contents)){
            if((!preg_match('/<\/body(.*?)>/', $contents) && !ctype_upper(end(explode('\\',$phpFile[0]))[0]))){
                $where = $phpFile[0];
                $what=$contents;
                $chck = False;
                #$this->assertFalse(true);
            }
        }
        }
        $this->assertTrue($chck,"".$where);
        }
}
?>