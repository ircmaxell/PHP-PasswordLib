<?php

use CryptLib\Hash\Implementation\SHA1;

class Unit_Hash_Implementation_SHA1Test extends PHPUnit_Framework_TestCase {

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
     * @covers CryptLib\Hash\Implementation\SHA1::getAlgos
     */
    public function testGetAlgos() {
        $this->assertEquals(array('sha1'), SHA1::getAlgos());
    }

    /**
     * @covers CryptLib\Hash\Implementation\SHA1::__construct
     */
    public function testConstruct() {
        $hash = new SHA1('sha1');
        $this->assertTrue($hash instanceof SHA1);
    }

    /**
     * @covers CryptLib\Hash\Implementation\SHA1::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFailure() {
        $hash = new SHA1('foo');
    }

    /**
     * @covers CryptLib\Hash\Implementation\SHA1::getSize
     */
    public function testGetSize() {
        $hash = new SHA1('sha1');
        $this->assertEquals(20, $hash->getSize());
    }

    /**
     * @covers CryptLib\Hash\Implementation\SHA1::evaluate
     * @dataProvider provideTestEvaluate
     */
    public function testEvaluate($data) {
        $hash = new SHA1('sha1');
        $expect = sha1($data, true);
        $this->assertEquals($expect, $hash->evaluate($data));
    }

    /**
     * @covers CryptLib\Hash\Implementation\SHA1::__invoke
     * @dataProvider provideTestEvaluate
     */
    public function testInvoke($data) {
        $hash = new SHA1('sha1');
        $expect = sha1($data, true);
        $this->assertEquals($expect, $hash($data));
    }

    /**
     * @covers CryptLib\Hash\Implementation\SHA1::getBlockSize
     */
    public function testGetBlockSize() {
        $hash = new SHA1('sha1');
        $this->assertEquals(64, $hash->getBlockSize());
    }

    /**
     * @covers CryptLib\Hash\Implementation\SHA1::getName
     */
    public function testGetName() {
        $hash = new SHA1('sha1');
        $this->assertEquals('sha1', $hash->getName());
    }

    /**
     * @covers CryptLib\Hash\Implementation\SHA1::hmac
     * @dataProvider provideTestHMAC
     */
    public function testHMAC($data, $key) {
        if (!function_exists('hash')) {
            $this->markTestSkipped('Hash Extension Is Required To Test HMAC');
            return false;
        }
        $hash = new SHA1('sha1');
        $expect = hash_hmac('sha1', $data, $key, true);
        $this->assertEquals($expect, $hash->hmac($data, $key));
    }
}
