<?php

use CryptLib\Random\Mixer\X_OR;
use CryptLib\Core\Strength;

class Unit_Random_Mixer_X_ORTest extends PHPUnit_Framework_TestCase {

    public static function provideMix() {
        $data = array(
            array(array(), ''),
            array(array('', ''), ''),
            array(array('a'), 'a'),
            array(array('a', 'b'), 'a' ^ 'b'),
            array(array('aa', 'b'), 'aa' ^ 'b'),
        );
        return $data;
    }

    /**
     * @covers CryptLib\Random\Mixer\X_OR::getStrength
     * @covers CryptLib\Random\AbstractMixer
     */
    public function testGetStrength() {
        $strength = new Strength(Strength::VERYLOW);
        $actual = X_OR::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Mixer\X_OR
     * @covers CryptLib\Random\AbstractMixer
     */
    public function testTest() {
        $actual = X_OR::test();
        $this->assertTrue($actual);
    }

    /**
     * @covers CryptLib\Random\Mixer\X_OR
     * @covers CryptLib\Random\AbstractMixer
     * @dataProvider provideMix
     */
    public function testMix($parts, $result) {
        $mixer = new X_OR;
        $actual = $mixer->mix($parts);
        $this->assertEquals($result, $actual);
    }

}
