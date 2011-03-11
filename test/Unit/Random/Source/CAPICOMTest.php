<?php

use CryptLib\Random\Source\CAPICOM;
use CryptLib\Core\Strength\Medium as MediumStrength;



class Unit_Random_Source_CAPICOMTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerate() {
        $data = array();
        for ($i = 0; $i < 100; $i += 5) {
            $not = $i > 0 ? str_repeat(chr(0), $i) : chr(0);
            $data[] = array($i, $not);
        }
        return $data;
    }

    public function testGetStrength() {
        $strength = new MediumStrength;
        $actual = CAPICOM::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @dataProvider provideGenerate
     */
    public function testGenerate($length, $not) {
        $rand = new CAPICOM;
        $stub = $rand->generate($length);
        $this->assertEquals($length, strlen($stub));
        if (strncasecmp(PHP_OS, 'Win', 3) === 0) {
            $this->assertNotEquals($not, $stub);
        } else {
            $this->assertEquals(str_repeat(chr(0), $length), $stub);
        }
    }

}
