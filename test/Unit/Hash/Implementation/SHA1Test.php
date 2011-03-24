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

    public function testGetAlgos() {
        $this->assertEquals(array('sha1'), SHA1::getAlgos());
    }

    public function testConstruct() {
        $hash = new SHA1('sha1');
        $this->assertTrue($hash instanceof SHA1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFailure() {
        $hash = new SHA1('foo');
    }

    public function testGetSize() {
        $hash = new SHA1('sha1');
        $this->assertEquals(20, $hash->getSize());
    }

    /**
     * @dataProvider provideTestEvaluate
     */
    public function testEvaluate($data) {
        $hash = new SHA1('sha1');
        $expect = sha1($data, true);
        $this->assertEquals($expect, $hash->evaluate($data));
    }

    /**
     * @dataProvider provideTestEvaluate
     */
    public function testInvoke($data) {
        $hash = new SHA1('sha1');
        $expect = sha1($data, true);
        $this->assertEquals($expect, $hash($data));
    }

    public function testGetBlockSize() {
        $hash = new SHA1('sha1');
        $this->assertEquals(64, $hash->getBlockSize());
    }

    public function testGetName() {
        $hash = new SHA1('sha1');
        $this->assertEquals('sha1', $hash->getName());
    }

    /**
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
