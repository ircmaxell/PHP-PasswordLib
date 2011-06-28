<?php

use CryptLib\Random\Mixer\Hash;
use CryptLib\Core\Strength\Low   as LowStrength;
use CryptLibTest\Mocks\Hash\Hash as MockHash;

class Unit_Random_Mixer_HashTest extends PHPUnit_Framework_TestCase {

    public static function provideMix() {
        $data = array(
            array(array(), ''),
            array(array('', ''), ''),
            array(array('a'), 'a'),
            // This expects 'b' because of how the mock hmac function works
            array(array('a', 'b'), 'b'),
            array(array('aa', 'ba'), 'ba'),
            array(array('ab', 'bb'), 'bb'),
            array(array('aa', 'bb'), 'bb'),
            array(array('aa', 'bb', 'cc'), 'cc'),
            array(array('aabbcc', 'bbccdd', 'ccddee'), 'ccbbdd'),
        );
        return $data;
    }

    /**
     * @covers CryptLib\Random\Mixer\Hash::__construct
     */
    public function testConstructWithoutArgument() {
        $hash = new Hash;
        $this->assertTrue($hash instanceof \CryptLib\Random\Mixer);
    }

    /**
     * @covers CryptLib\Random\Mixer\Hash::getStrength
     */
    public function testGetStrength() {
        $strength = new LowStrength;
        $actual = Hash::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Mixer\Hash::test
     */
    public function testTest() {
        $actual = Hash::test();
        $this->assertTrue($actual);
    }

    /**
     * @covers CryptLib\Random\Mixer\Hash::mix
     * @dataProvider provideMix
     */
    public function testMix($parts, $result) {
        $mock = new MockHash(array(
            'getSize' => function () { return 2; },
            'evaluate' => function ($data) { return substr($data, 0, 2); },
            'hmac' => function ($data, $key) { return substr($data ^ $key, 0, 2); }
        ));
        $mixer = new Hash($mock);
        $actual = $mixer->mix($parts);
        $this->assertEquals($result, $actual);
    }


}
