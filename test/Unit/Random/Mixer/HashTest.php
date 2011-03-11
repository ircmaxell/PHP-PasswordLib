<?php

require_once 'PHPUnit/Framework.php';

use CryptLib\Random\Mixer\Hash;
use CryptLib\Core\Strength\Medium as MediumStrength;
use CryptLibTest\Mocks\Hash\Hash as MockHash;

class Unit_Random_Mixer_HashTest extends PHPUnit_Framework_TestCase {

    public static function provideMix() {
        $data = array(
            array(array(), ''),
            array(array('', ''), ''),
            array(array('a'), 'a'),
            // This expects 'b' because of how the mock hmac function works
            array(array('a', 'b'), 'b'),
            array(array('aa', 'b'), 'b'),
            array(array('a', 'bb'), 'b'),
            array(array('aa', 'bb'), 'bb'),
            array(array('aa', 'bb', 'cc'), 'cc'),
            array(array('aabbcc', 'bbccdd', 'ccddee'), 'ccbbdd'),
        );
        return $data;
    }

    public function testConstructWithoutArgument() {
        $hash = new Hash;
        $this->assertTrue($hash instanceof \CryptLib\Random\Mixer);
    }

    public function testGetStrength() {
        $strength = new MediumStrength;
        $actual = Hash::getStrength();
        $this->assertEquals($actual, $strength);
    }

    public function testTest() {
        $actual = Hash::test();
        $this->assertTrue($actual);
    }

    /**
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
