<?php

use CryptLib\Encryption\PackingMode\Zeros;

class Unit_Encryption_PackingMode_ZerosTest extends PHPUnit_Framework_TestCase {

    public static function provideTestPad() {
        return array(
            array('aabbcc', 4, 'aabbcc00'),
            array('aabbcc', 6, 'aabbcc000000'),
            array('aabbccddeeff', 6, 'aabbccddeeff'),
        );
    }

    public function testConstruct() {
        $mode = new Zeros;
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testPad($data, $blockSize, $expect) {
        $mode = new Zeros;
        $result = $mode->pad(pack('H*', $data), $blockSize);
        $this->assertEquals($expect, bin2hex($result));
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testStrip($data, $blockSize, $expect) {
        $mode = new Zeros;
        $result = $mode->strip(pack('H*', $expect));
        $this->assertEquals($data, bin2hex($result));
    }
}