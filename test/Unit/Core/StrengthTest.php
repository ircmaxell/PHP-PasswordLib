<?php

use CryptLibTest\Mocks\Core\Strength;

class Unit_Core_StrengthTest extends PHPUnit_Framework_TestCase {

    public static function provideTestCompare() {
        return array(
            array(new Strength(1), new Strength(1), 0),
            array(new Strength(2), new Strength(1), -1),
            array(new Strength(1), new Strength(2), 1),
        );
    }

    /**
     */
    public function testConstruct() {
        $obj = new Strength;
        $this->assertTrue($obj instanceof \CryptLib\Core\Strength);
    }

    /**
     * @covers CryptLib\Core\Strength::compare
     * @dataProvider provideTestCompare
     */
    public function testCompare(Strength $from, Strength $to, $expected) {
        $this->assertEquals($expected, $from->compare($to));
    }
}
