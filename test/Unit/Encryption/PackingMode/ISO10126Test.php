<?php

use CryptLib\Encryption\PackingMode\ISO10126;

class Unit_Encryption_PackingMode_ISO10126Test extends PHPUnit_Framework_TestCase {

    public static function provideTestPad() {
        return array(
            array('aabbcc', 4, '/^aabbcc01$/'),
            array('aabbcc', 6, '/^aabbcc[0-9a-f]{4}03$/'),
            array('aabbccddeeff', 6, '/^aabbccddeeff[0-9a-f]{10}06$/'),
        );
    }

    public static function provideTestStrip() {
        return array(
            array('aabbcc', 4, 'aabbcc01'),
            array('aabbcc', 6, 'aabbccddeeffa5f706'),
            array('aabbccddeeff', 6, 'aabbccddeeff4158a6c4e206'),
        );
    }

    public function testConstruct() {
        $mode = new ISO10126;
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testPad($data, $blockSize, $expect) {
        $mode = new ISO10126;
        $result = $mode->pad(pack('H*', $data), $blockSize);
        $this->assertEquals(1, preg_match($expect, bin2hex($result)));
    }

    /**
     * @dataProvider provideTestStrip
     */
    public function testStrip($data, $blockSize, $expect) {
        $mode = new ISO10126;
        $result = $mode->strip(pack('H*', $expect));
        $this->assertEquals($data, bin2hex($result));
    }
}