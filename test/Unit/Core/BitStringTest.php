<?php

use CryptLib\Core\BitString;

class Unit_Core_BitStringTest extends PHPUnit_Framework_TestCase {

    public static function provideTestConstruct() {
        return array(
            array('1'),
            array(1),
            array(1.5235),
            array('1safdswwcsacscwaecawef'),
        );
    }

    public static function provideTestNot() {
        return array(
            array(str_repeat(chr(0), 8), str_repeat(chr(0xff), 8)),
            array(str_repeat(chr(1), 8), str_repeat(chr(0xfe), 8)),
            array(str_repeat(chr(0xff), 8), str_repeat(chr(0x00), 8)),
            array(str_repeat(chr(0), 4), str_repeat(chr(0xff), 8)),
            array(str_repeat(chr(0xff), 4), str_repeat(chr(0xff), 4) . str_repeat(chr(0), 4)),
        );
    }

    public static function provideTestModulo() {
        $ret = array();
        for ($i = 0; $i <= 64; $i+= 8) {
            $ret[] = array(str_repeat(chr(0xff), 8), $i, str_repeat(chr(0), 8 - ($i / 8)) . str_repeat(chr(0xff),($i / 8)));
        }
        return $ret;
    }

    public static function provideTestRotateLeft() {
        return array(
            array(str_repeat(chr(0), 3) . chr(0x17), 1, 32, str_repeat(chr(0), 7) . chr(0x2e)),
            array(str_repeat(chr(0), 1) . str_repeat(chr(0x17), 3), 1, 32, str_repeat(chr(0), 5) . str_repeat(chr(0x2e), 3)),
        );
    }

    /**
     * @dataProvider provideTestConstruct
     * @covers CryptLib\Core\BitString::__construct
     */
    public function testConstruct($value) {
        $obj = new BitString($value);
    }

    /**
     * @dataProvider provideTestNot
     * @covers CryptLib\Core\BitString::logicalNot
     */
    public function testNot($value, $expect) {
        $obj = new BitString($value);
        $this->assertEquals($expect, (string) $obj->logicalNot());
    }

    /**
     * @dataProvider provideTestModulo
     * @covers CryptLib\Core\BitString::modulo
     */
    public function testModulo($value, $mod, $expect) {
        $obj = new BitString($value);
        $this->assertEquals($expect, (string) $obj->modulo($mod));
    }

    /**
     * @dataProvider provideTestRotateLeft
     * @covers CryptLib\Core\BitString::rotateLeft
     */
    public function testRotateLeft($value, $bits, $size, $expect) {
        $obj = new BitString($value);
        $actual = (string) $obj->rotateLeft($bits, $size);
        var_dump(bin2hex($expect), bin2hex($actual));
        $this->assertEquals($expect, $actual);
    }
}
