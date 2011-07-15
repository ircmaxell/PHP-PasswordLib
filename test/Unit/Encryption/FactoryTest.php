<?php

use CryptLib\Encryption\Factory;

class Unit_Encryption_FactoryTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $factory = new Factory;
    }

    public function testGetPackingMode() {
        $factory = new Factory;
        $mode = $factory->getPackingMode('none');
        $this->assertTrue($mode instanceof \CryptLib\Encryption\PackingMode\None);
    }

    public function testGetPackingModeDefault() {
        $factory = new Factory;
        $expect = new \CryptLib\Encryption\PackingMode\None;
        $mode = $factory->getPackingMode($expect);
        $this->assertEquals($expect, $mode);
    }
    /**
     * @expectedException RuntimeException
     */
    public function testGetPackingModeFail() {
        $factory = new Factory;
        $mode = $factory->getPackingMode('foobarbaz');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRegisterPackingModeFail() {
        $factory = new Factory;
        $factory->registerPackingMode('test', 'stdClass');
    }

}
