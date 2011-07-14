<?php

use CryptLib\MAC\Implementation\CMAC;

class Unit_MAC_Implementation_CMACTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $cmac = new CMAC;
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testConstructWithArgs() {
        $cmac = new CMAC(array('foo' => 'bar'));
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testConstructWithFactory() {
        $factory = new \CryptLib\Cipher\Factory;
        $cmac = new CMAC(array('cipherFactory' => $factory));
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     * @expectedException RuntimeException
     */
    public function testConstructWithArgsFailure() {
        $cmac = new CMAC(array('cipher' => 'foobar'));
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     * @expectedException RuntimeException
     */
    public function testGenerateWith256BitCipher() {
        $cmac = new CMAC(array('cipher' => 'rijndael-256'));
        $data = 'foobar';
        $key = str_repeat('barbazbi', 4);
        $result = $cmac->generate($data, $key);
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerateWith64BitCipher() {
        $cmac = new CMAC(array('cipher' => 'des'));
        $data = 'foobar';
        $key = 'barbazbi';
        $result = $cmac->generate($data, $key);
        $result = bin2hex($result);
        $this->assertEquals('771c1d71ce2bd619', $result);
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerate() {
        $data = 'foobar';
        $key = 'barbazbizbuzbozb';
        $cmac = new CMAC;
        $result = $cmac->generate($data, $key);
        $result = bin2hex($result);
        $this->assertEquals('ba15028b18f0a8cbfd1f3089b73fbcb9', $result);
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerateSize() {
        $data = 'foobar';
        $key = 'barbazbizbuzbozb';
        $cmac = new CMAC;
        $result = $cmac->generate($data, $key, 5);
        $result = bin2hex($result);
        $this->assertEquals('ba15028b18', $result);
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerateSizeZero() {
        $data = 'foobar';
        $key = 'barbazbizbuzbozb';
        $cmac = new CMAC;
        $result = $cmac->generate($data, $key, 0);
        $result = bin2hex($result);
        $this->assertEquals('ba15028b18f0a8cbfd1f3089b73fbcb9', $result);
    }

    /**
     * @expectedException OutOfRangeException
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerateSizeFailure() {
        $data = 'foobar';
        $key = 'barbazbizbuzbozb';
        $cmac = new CMAC;
        $result = $cmac->generate($data, $key, 512);
    }

    /**
     * @covers CryptLib\MAC\Implementation\CMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerateSize2() {
        $data = 'foobarbazbizbazb';
        $key = 'this is c test k';

        $cmac = new CMAC;
        $result = $cmac->generate($data, $key, 5);
        $result = bin2hex($result);
        $this->assertEquals('b1b6b414dc', $result);
    }
}
