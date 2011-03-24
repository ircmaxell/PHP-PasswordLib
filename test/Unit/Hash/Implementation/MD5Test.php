<?php

use CryptLib\Hash\Implementation\MD5;

class Unit_Hash_Implementation_MD5Test extends PHPUnit_Framework_TestCase {

    public static function provideTestEvaluate() {
        return array(
            array(''),
            array('zero'),
            array('abcabcabcabcabcabcabcabcabcabcabcabc'),
        );
    }

    public static function provideTestHMAC() {
        return array(
            array('', ''),
            array(str_repeat('a', 512), str_repeat('a', 512)),
            array(str_repeat('a', 64), str_repeat('a', 64)),
            array('abcdefghijlmnop', 'qrstuvwxyz0123456789'),
        );
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::getAlgos
     */
    public function testGetAlgos() {
        $this->assertEquals(array('md5'), MD5::getAlgos());
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::__construct
     */
    public function testConstruct() {
        $hash = new MD5('md5');
        $this->assertTrue($hash instanceof MD5);
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFailure() {
        $hash = new MD5('foo');
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::getSize
     */
    public function testGetSize() {
        $hash = new MD5('md5');
        $this->assertEquals(16, $hash->getSize());
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::evaluate
     * @dataProvider provideTestEvaluate
     */
    public function testEvaluate($data) {
        $hash = new MD5('md5');
        $expect = md5($data, true);
        $this->assertEquals($expect, $hash->evaluate($data));
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::__invoke
     * @dataProvider provideTestEvaluate
     */
    public function testInvoke($data) {
        $hash = new MD5('md5');
        $expect = md5($data, true);
        $this->assertEquals($expect, $hash($data));
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::getBlockSize
     */
    public function testGetBlockSize() {
        $hash = new MD5('md5');
        $this->assertEquals(64, $hash->getBlockSize());
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::getName
     */
    public function testGetName() {
        $hash = new MD5('md5');
        $this->assertEquals('md5', $hash->getName());
    }

    /**
     * @covers CryptLib\Hash\Implementation\MD5::hmac
     * @dataProvider provideTestHMAC
     */
    public function testHMAC($data, $key) {
        if (!function_exists('hash')) {
            $this->markTestSkipped('Hash Extension Is Required To Test HMAC');
            return false;
        }
        $hash = new MD5('md5');
        $expect = hash_hmac('md5', $data, $key, true);
        $this->assertEquals($expect, $hash->hmac($data, $key));
    }
}
