<?php

use CryptLib\MAC\Implementation\HMAC;

class Unit_MAC_Implementation_HMACTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $HMAC = new HMAC;
    }

    /**
     * @covers CryptLib\MAC\Implementation\HMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerate() {
        $data = 'foobar';
        $key = 'barbazbizbuzbozb';
        $HMAC = new HMAC;
        $result = $HMAC->generate($data, $key);
        $result = bin2hex($result);
        $this->assertEquals('de664624d6a4e97d21b42226e4642b33598f520b2eca6b565c12933ebba24745', $result);
    }

    /**
     * @covers CryptLib\MAC\Implementation\HMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerateSize() {
        $data = 'foobar';
        $key = 'barbazbizbuzbozb';
        $HMAC = new HMAC;
        $result = $HMAC->generate($data, $key, 5);
        $result = bin2hex($result);
        $this->assertEquals('de664624d6', $result);
    }

    /**
     * @covers CryptLib\MAC\Implementation\HMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerateSizeZero() {
        $data = 'foobar';
        $key = 'barbazbizbuzbozb';
        $HMAC = new HMAC;
        $result = $HMAC->generate($data, $key, 0);
        $result = bin2hex($result);
        $this->assertEquals('de664624d6a4e97d21b42226e4642b33598f520b2eca6b565c12933ebba24745', $result);
    }

    /**
     * @expectedException OutOfRangeException
     * @covers CryptLib\MAC\Implementation\HMAC
     * @covers CryptLib\MAC\AbstractMAC
     */
    public function testGenerateSizeFailure() {
        $data = 'foobar';
        $key = 'barbazbizbuzbozb';
        $HMAC = new HMAC;
        $result = $HMAC->generate($data, $key, 512);
    }

}
