<?php

use CryptLibTest\Mocks\Core\Enum;

class Unit_Core_EnumTest extends PHPUnit_Framework_TestCase {

    public static function provideTestCompare() {
        return array(
            array(new Enum(Enum::Value1), new Enum(Enum::Value1), 0),
            array(new Enum(Enum::Value2), new Enum(Enum::Value1), -1),
            array(new Enum(Enum::Value1), new Enum(Enum::Value2), 1),
        );
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testConstructFail() {
        $obj = new Enum();
    }
    public function testConstruct() {
        $obj = new Enum(Enum::Value3);
        $this->assertTrue($obj instanceof \CryptLib\Core\Enum);
    }

    /**
     * @covers CryptLib\Core\Enum::compare
     * @dataProvider provideTestCompare
     */
    public function testCompare(Enum $from, Enum $to, $expected) {
        $this->assertEquals($expected, $from->compare($to));
    }
}