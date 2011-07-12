<?php

use CryptLib\Random\Mixer\Hash;
use CryptLib\Core\Strength;

class Unit_Random_Mixer_HashTest extends PHPUnit_Framework_TestCase {

    public static function provideMix() {
        $data = array(
            array(array(), ''),
            array(array('', ''), ''),
            array(array('a'), '61'),
            // This expects 'b' because of how the mock hmac function works
            array(array('a', 'b'), '9a'),
            array(array('aa', 'ba'), '6e84'),
            array(array('ab', 'bb'), 'b0cb'),
            array(array('aa', 'bb'), 'ae8d'),
            array(array('aa', 'bb', 'cc'), 'a14c'),
            array(array('aabbcc', 'bbccdd', 'ccddee'), 'a8aff3939934'),
        );
        return $data;
    }

    /**
     * @covers CryptLib\Random\Mixer\Hash
     * @covers CryptLib\Random\AbstractMixer
     */
    public function testConstructWithoutArgument() {
        $hash = new Hash;
        $this->assertTrue($hash instanceof \CryptLib\Random\Mixer);
    }

    /**
     * @covers CryptLib\Random\Mixer\Hash
     * @covers CryptLib\Random\AbstractMixer
     */
    public function testGetStrength() {
        $strength = new Strength(Strength::LOW);
        $actual = Hash::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Mixer\Hash
     * @covers CryptLib\Random\AbstractMixer
     */
    public function testTest() {
        $actual = Hash::test();
        $this->assertTrue($actual);
    }

    /**
     * @covers CryptLib\Random\Mixer\Hash
     * @covers CryptLib\Random\AbstractMixer
     * @dataProvider provideMix
     */
    public function testMix($parts, $result) {
        $mixer = new Hash('md5');
        $actual = $mixer->mix($parts);
        $this->assertEquals($result, bin2hex($actual));
    }


}
