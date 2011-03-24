<?php

use CryptLib\Hash\Implementation\Core;

class Unit_Hash_Implementation_CoreTest extends PHPUnit_Framework_TestCase {

    public static function provideGetAlgos() {
        if (!function_exists('hash')) {
            return array();
        }
        $algos = hash_algos();
        $result = array();
        foreach ($algos as $algo) {
            $result[] = array($algo);
        }
        return $result;
    }

    public static function provideTestEvaluate() {
        if (!function_exists('hash')) {
            return array();
        }
        $algos = hash_algos();
        $result = array();
        foreach ($algos as $algo) {
            $result[] = array($algo, '');
            $result[] = array($algo, 'zero');
            $result[] = array($algo, 'abcabcabcabcabcabcabcabcabcabcabcabc');
        }
        return $result;
    }

    public static function provideTestHMAC() {
        if (!function_exists('hash')) {
            return array();
        }
        $algos = hash_algos();
        $result = array();
        foreach ($algos as $algo) {
            $result[] = array($algo, '', '');
            $result[] = array($algo, str_repeat('a', 512), str_repeat('a', 512));
            $result[] = array($algo, str_repeat('a', 64), str_repeat('a', 64));
            $result[] = array($algo, 'abcdefghijlmnop', 'qrstuvwxyz0123456789');
        }
        return $result;
    }

    protected function setUp() {
        if (!function_exists('hash')) {
            $this->markTestSkipped('Hash Extension is not loaded');
        }
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::getAlgos
     */
    public function testGetAlgos() {
        $expect = hash_algos();
        $this->assertEquals($expect, Core::getAlgos());
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::__construct
     * @dataProvider provideGetAlgos
     */
    public function testConstruct($algo) {
        $hash = new Core($algo);
        $this->assertTrue($hash instanceof Core);
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testConstructFailure() {
        $hash = new Core('foo');
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::getSize
     */
    public function testGetSize() {
        $hash = new Core('md5');
        $this->assertEquals(16, $hash->getSize());
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::evaluate
     * @dataProvider provideTestEvaluate
     */
    public function testEvaluate($algo, $data) {
        $hash = new Core($algo);
        $expect = hash($algo, $data, true);
        $this->assertEquals($expect, $hash->evaluate($data));
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::__invoke
     * @dataProvider provideTestEvaluate
     */
    public function testInvoke($algo, $data) {
        $hash = new Core($algo);
        $expect = hash($algo, $data, true);
        $this->assertEquals($expect, $hash($data));
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::getBlockSize
     * @dataProvider provideGetAlgos
     */
    public function testGetBlockSize($algo) {
        $hash = new Core($algo);
        $this->assertEquals(64, $hash->getBlockSize());
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::getName
     * @dataProvider provideGetAlgos
     */
    public function testGetName($algo) {
        $hash = new Core($algo);
        $this->assertEquals($algo, $hash->getName());
    }

    /**
     * @covers CryptLib\Hash\Implementation\Core::hmac
     * @dataProvider provideTestHMAC
     */
    public function testHMAC($algo, $data, $key) {
        $hash = new Core($algo);
        $expect = hash_hmac($algo, $data, $key, true);
        $this->assertEquals($expect, $hash->hmac($data, $key));
    }
}
