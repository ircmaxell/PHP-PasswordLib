<?php

use CryptLib\Random\Source\OpenSSL;
use CryptLib\Core\Strength;

class Unit_Random_Source_OpenSSLTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerate() {
        $data = array();
        for ($i = 0; $i < 100; $i += 50) {
            $not = $i > 0 ? str_repeat(chr(0), $i) : chr(0);
            $data[] = array($i, $not);
        }
        return $data;
    }

    /**
     * @covers CryptLib\Random\Source\OpenSSL::getStrength
     */
    public function testGetStrength() {
        $strength = new Strength(Strength::HIGH);
        $actual = OpenSSL::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Source\OpenSSL::generate
     * @dataProvider provideGenerate
     * @group slow
     */
    public function testGenerate($length, $not) {
        $rand = new OpenSSL;
        $stub = $rand->generate($length);
        $this->assertEquals($length, strlen($stub));
        if (function_exists('openssl_random_pseudo_bytes')) {
            $this->assertNotEquals($not, $stub);
        } else {
            $this->assertEquals(str_repeat(chr(0), $length), $stub);
        }
    }

}
