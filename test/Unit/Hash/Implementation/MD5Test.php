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

    public function testGetAlgos() {
        $this->assertEquals(array('md5'), MD5::getAlgos());
    }

    public function testConstruct() {
        $hash = new MD5('md5');
        $this->assertTrue($hash instanceof MD5);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFailure() {
        $hash = new MD5('foo');
    }

    public function testGetSize() {
        $hash = new MD5('md5');
        $this->assertEquals(16, $hash->getSize());
    }

    /**
     * @dataProvider provideTestEvaluate
     */
    public function testEvaluate($data) {
        $hash = new MD5('md5');
        $expect = md5($data, true);
        $this->assertEquals($expect, $hash->evaluate($data));
    }

    /**
     * @dataProvider provideTestEvaluate
     */
    public function testInvoke($data) {
        $hash = new MD5('md5');
        $expect = md5($data, true);
        $this->assertEquals($expect, $hash($data));
    }

    public function testGetBlockSize() {
        $hash = new MD5('md5');
        $this->assertEquals(64, $hash->getBlockSize());
    }

    public function testGetName() {
        $hash = new MD5('md5');
        $this->assertEquals('md5', $hash->getName());
    }

    /**
     * @dataProvider provideTestHMAC
     */
    public function testHMAC($data, $key) {
        $hash = new MD5('md5');
        $expect = hash_hmac('md5', $data, $key, true);
        $this->assertEquals($expect, $hash->hmac($data, $key));
    }
}
