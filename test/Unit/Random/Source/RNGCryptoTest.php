<?php

use CryptLib\Random\Source\RNGCrypto;
use CryptLib\Core\Strength\High as HighStrength;

class Unit_Random_Source_RNGCryptoTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerate() {
        $data = array();
        for ($i = 0; $i < 100; $i += 5) {
            $not = $i > 0 ? str_repeat(chr(0), $i) : chr(0);
            $data[] = array($i, $not);
        }
        return $data;
    }

    /**
     * @covers CryptLib\Random\Source\RNGCrypto::getStrength
     */
    public function testGetStrength() {
        $strength = new HighStrength;
        $actual = RNGCrypto::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Source\RNGCrypto::generate
     * @dataProvider provideGenerate
     */
    public function testGenerate($length, $not) {
        $rand = new RNGCrypto;
        $stub = $rand->generate($length);
        $this->assertEquals($length, strlen($stub));
        if (strncasecmp(PHP_OS, 'Win', 3) === 0) {
            $this->assertNotEquals($not, $stub);
        } else {
            $this->assertEquals(str_repeat(chr(0), $length), $stub);
        }
    }

}
