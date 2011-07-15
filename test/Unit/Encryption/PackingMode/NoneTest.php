<?php

use CryptLib\Encryption\PackingMode\None;

class Unit_Encryption_PackingMode_NoneTest extends PHPUnit_Framework_TestCase {

    public static function provideTestPad() {
        return array(
            array('aabbcc', 4, 'aabbcc'),
            array('aabbcc', 6, 'aabbcc'),
            array('aabbccddeeff', 6, 'aabbccddeeff'),
        );
    }

    public function testConstruct() {
        $mode = new None;
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testPad($data, $blockSize, $expect) {
        $mode = new None;
        $result = $mode->pad(pack('H*', $data), $blockSize);
        $this->assertEquals($expect, bin2hex($result));
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testStrip($data, $blockSize, $expect) {
        $mode = new None;
        $result = $mode->strip(pack('H*', $expect));
        $this->assertEquals($data, bin2hex($result));
    }
}