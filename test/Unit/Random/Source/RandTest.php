<?php

use CryptLib\Random\Source\Rand;
use CryptLib\Core\Strength\VeryLow as VeryLowStrength;
use CryptLib\Core\Strength\Low     as LowStrength;

class Unit_Random_Source_RandTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerate() {
        $data = array();
        for ($i = 0; $i < 100; $i += 5) {
            $not = $i > 0 ? str_repeat(chr(0), $i) : chr(0);
            $data[] = array($i, $not);
        }
        return $data;
    }

    public function testGetStrength() {
        if (defined('S_ALL')) {
            $strength = new LowStrength;
        } else {
            $strength = new VeryLowStrength;
        }
        $actual = Rand::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @dataProvider provideGenerate
     */
    public function testGenerate($length, $not) {
        $rand = new Rand;
        $stub = $rand->generate($length);
        $this->assertEquals($length, strlen($stub));
        $this->assertNotEquals($not, $stub);
    }

}
