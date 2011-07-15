<?php

use CryptLib\Encryption\PackingMode\ANSIx923;

class Unit_Encryption_PackingMode_ANSIx923Test extends PHPUnit_Framework_TestCase {

    public static function provideTestPad() {
        return array(
            array('aabbcc', 4, 'aabbcc01'),
            array('aabbcc', 6, 'aabbcc000003'),
            array('aabbccddeeff', 6, 'aabbccddeeff000000000006'),
        );
    }

    public function testConstruct() {
        $mode = new ANSIx923;
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testPad($data, $blockSize, $expect) {
        $mode = new ANSIx923;
        $result = $mode->pad(pack('H*', $data), $blockSize);
        $this->assertEquals($expect, bin2hex($result));
    }

    /**
     * @dataProvider provideTestPad
     */
    public function testStrip($data, $blockSize, $expect) {
        $mode = new ANSIx923;
        $result = $mode->strip(pack('H*', $expect));
        $this->assertEquals($data, bin2hex($result));
    }

    public function testStripFail() {
        $mode = new ANSIx923;
        $this->assertFalse($mode->strip(pack('H*', 'af425822523517230005')));
    }
}