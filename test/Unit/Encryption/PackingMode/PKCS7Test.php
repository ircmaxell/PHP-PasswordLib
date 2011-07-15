<?php

use CryptLib\Encryption\PackingMode\PKCS7;

class Unit_Encryption_PackingMode_PKCS7Test extends PHPUnit_Framework_TestCase {

    public static function provideTestPad() {
        return array(
            array('aabbcc', 4, 'aabbcc01'),
            array('aabbcc', 6, 'aabbcc030303'),
            array('aabbccddeeff', 6, 'aabbccddeeff060606060606'),
        );
    }

    public function testConstruct() {
        $mode = new PKCS7;
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testPad($data, $blockSize, $expect) {
        $mode = new PKCS7;
        $result = $mode->pad(pack('H*', $data), $blockSize);
        $this->assertEquals($expect, bin2hex($result));
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testStrip($data, $blockSize, $expect) {
        $mode = new PKCS7;
        $result = $mode->strip(pack('H*', $expect));
        $this->assertEquals($data, bin2hex($result));
    }

    public function testStripFail() {
        $mode = new PKCS7;
        $this->assertFalse($mode->strip(pack('H*', 'af42582252351723')));
    }
}