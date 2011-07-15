<?php

use CryptLib\Hash\CRC32;

class Unit_Hash_CRC32Test extends PHPUnit_Framework_TestCase {

    public static function providetestCalculate() {
        return array(
            array('', '00000000'),
            array('a', dechex(crc32('a'))),
            array('ab', dechex(crc32('ab'))),
        );
    }

    public static function provideTestUpdateByte() {
        return array(
            array(dechex(crc32('a')), 'b', dechex(crc32('ab'))),
            array(dechex(crc32('def')), 'ghi', dechex(crc32('defghi'))),
        );
    }

    /**
     * @dataProvider provideTestCalculate
     */
    public function testCalculate($data, $expect) {
        $int = CRC32::calculate($data);
        $this->assertEquals($expect, dechex($int));
    }

    /**
     * @dataProvider provideTestUpdateByte
     */
    public function testUpdateByte($crc, $byte, $expect) {
        $int = CRC32::update(hexdec($crc), $byte);
        $this->assertEquals($expect, dechex($int));
    }
}
