<?php

use CryptLib\Core\BaseConverter;

class Unit_Core_BaseConverterTest extends PHPUnit_Framework_TestCase {

    public static function provideConvertFromBinary() {
        return array(
            array('', '', ''),
            array(chr(0), '012', '0'),
            array(chr(1), '012', '1'),
            array(chr(2), '01', '10'),
            array(chr(4), '01', '100'),
            array(chr(9), '01', '1001'),
            array(chr(255), '0123456', '513'),
            array(chr(1) . chr(2) . chr(3), '0123456789', '66051'),
            array(chr(155) . chr(255), '0123456789abcdef', '9bff'),
        );
    }

    /**
     * @dataProvider provideConvertFromBinary
     */
    public function testConvertFromBinary($from, $to, $expect) {
        $result = BaseConverter::convertFromBinary($from, $to, $expect);
        $this->assertEquals($expect, $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBaseConvertFailure() {
        BaseConverter::baseConvert(array(1), 1, 1);
    }
}
