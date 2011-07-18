<?php

use CryptLib\CryptLib;

class Unit_CryptLibTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $crypt = new CryptLib;
    }

    public function testCreatePasswordHash() {
        $password = 'foobar';
        $prefix = '$2a$';
        $crypt = new CryptLib;
        $test = $crypt->createPasswordHash($password, $prefix);
        $this->assertTrue($test == crypt($password, $test));
    }

    public function testVerifyPasswordHash() {
        $password = 'foobar';
        $prefix = '$2a$';
        $crypt = new CryptLib;
        $test = $crypt->createPasswordHash($password, $prefix);
        $this->assertTrue($crypt->verifyPasswordHash($password, $test));
    }

    public function testGetRandomArrayElement() {
        $array = range(1, 100);
        $crypt = new CryptLib;
        $el = $crypt->getRandomArrayElement($array);
        $this->assertTrue(in_array($el, $array));
    }

    public function testGetRandomNumber() {
        $crypt = new CryptLib;
        $max = 100;
        $min = 0;
        $number = $crypt->getRandomNumber($min, $max);
        $this->assertGreaterThanOrEqual($min, $number);
        $this->assertLessThanOrEqual($max, $number);
    }

    public function testGetRandomBytes() {
        $crypt = new CryptLib;
        $string = $crypt->getRandomBytes(10);
        $this->assertEquals(10, strlen($string));
    }

    public function testGetRandomToken() {
        $crypt = new CryptLib;
        $string = $crypt->getRandomToken(10);
        $this->assertEquals(10, strlen($string));
    }
}