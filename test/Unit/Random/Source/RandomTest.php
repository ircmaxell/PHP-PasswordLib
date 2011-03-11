<?php

require_once 'PHPUnit/Framework.php';

use CryptLib\Random\Source\Random;
use CryptLib\Core\Strength\High as HighStrength;



class Unit_Random_Source_RandomTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerate() {
        $data = array();
        for ($i = 0; $i < 10; $i += 5) {
            $not = $i > 0 ? str_repeat(chr(0), $i) : chr(0);
            $data[] = array($i, $not);
        }
        return $data;
    }

    public function testGetStrength() {
        $strength = new HighStrength;
        $actual = Random::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @dataProvider provideGenerate
     */
    public function testGenerate($length, $not) {
        $rand = new Random;
        $stub = $rand->generate($length);
        $this->assertEquals($length, strlen($stub));
        if (file_exists('/dev/random')) {
            $this->assertNotEquals($not, $stub);
        } else {
            $this->assertEquals(str_repeat(chr(0), $length), $stub);
        }
    }

}
