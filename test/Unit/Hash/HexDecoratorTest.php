<?php

use CryptLibTest\Mocks\Hash\Hash;
use CryptLib\Hash\HexDecorator;

class Unit_Hash_HexDecoratorTest extends PHPUnit_Framework_TestCase {

    public static function provideTestEvaluate() {
        return array(
            array(chr(1), '01'),
            array(chr(1) . chr(16), '0110'),
            array(chr(255), 'ff'),
        );
    }

    public static function provideTestHMAC() {
        return array(
            array(chr(1), chr(1), '0101'),
            array(chr(1), chr(16), '0110'),
            array(chr(255), chr(1), 'ff01'),
        );
    }

    /**
     * @covers CryptLib\Hash\HexDecorator::getAlgos
     */
    public function testGetAlgos() {
        $this->assertEquals(array(), HexDecorator::getAlgos());
    }

    /**
     * @covers CryptLib\Hash\HexDecorator::__construct
     */
    public function testConstruct() {
        $hash = new Hash(array());
        $hex = new HexDecorator($hash);
        $this->assertTrue($hex instanceof HexDecorator);
    }

    /**
     * @covers CryptLib\Hash\HexDecorator::evaluate
     * @dataProvider provideTestEvaluate
     */
    public function testEvaluate($data, $expect) {
        $hash = new Hash(array(
            'evaluate' => function($data) { return $data; }
        ));
        $hex = new HexDecorator($hash);
        $this->assertEquals($expect, $hex->evaluate($data));
    }

    /**
     * @covers CryptLib\Hash\HexDecorator::getBlockSize
     */
    public function testGetBlockSize() {
        $hash = new Hash(array(
            'getBlockSize' => function() { return 'size'; }
        ));
        $hex = new HexDecorator($hash);
        $this->assertEquals('size', $hex->getBlockSize());
    }

    /**
     * @covers CryptLib\Hash\HexDecorator::getSize
     */
    public function testGetSize() {
        $hash = new Hash(array(
            'getSize' => function() { return 42; }
        ));
        $hex = new HexDecorator($hash);
        $this->assertEquals(84, $hex->getSize());
    }

    /**
     * @covers CryptLib\Hash\HexDecorator::getName
     */
    public function testGetName() {
        $hash = new Hash(array(
            'getName' => function() { return 141; }
        ));
        $hex = new HexDecorator($hash);
        $this->assertEquals(141, $hex->getName());
    }

    /**
     * @covers CryptLib\Hash\HexDecorator::hmac
     * @dataProvider provideTestHMAC
     */
    public function testHMAC($data, $key, $expect) {
        $hash = new Hash(array(
            'hmac' => function($data, $key) { return $data . $key; }
        ));
        $hex = new HexDecorator($hash);
        $this->assertEquals($expect, $hex->hmac($data, $key));
    }

}
