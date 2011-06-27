<?php

use CryptLib\Random\Source\MCrypt;
use CryptLib\Core\Strength\High as HighStrength;

class Unit_Random_Source_MCryptTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerate() {
        $data = array();
        for ($i = 0; $i < 100; $i += 5) {
            $not = $i > 0 ? str_repeat(chr(0), $i) : chr(0);
            $data[] = array($i, $not);
        }
        return $data;
    }

    /**
     * @covers CryptLib\Random\Source\MCrypt::getStrength
     */
    public function testGetStrength() {
        $strength = new HighStrength;
        $actual = MCrypt::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Source\MCrypt::generate
     * @dataProvider provideGenerate
     */
    public function testGenerate($length, $not) {
        $rand = new MCrypt;
        $stub = $rand->generate($length);
        $this->assertEquals($length, strlen($stub));
        if (function_exists('mcrypt_create_iv')) {
            $this->assertNotEquals($not, $stub);
        } else {
            $this->assertEquals(str_repeat(chr(0), $length), $stub);
        }
    }

}
