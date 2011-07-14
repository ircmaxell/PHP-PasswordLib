<?php

use CryptLib\Key\Factory;

class Unit_Key_FactoryTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $factory = new Factory;
    }

    public function testGetKDF() {
        $factory = new Factory;
        $this->assertTrue($factory->getKDF() instanceof \CryptLib\Key\Derivation\KDF\KDF3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetKDFFail() {
        $factory = new Factory;
        $factory->getKDF('someGibberish');
    }

    public function testGetPBKDF() {
        $factory = new Factory;
        $this->assertTrue($factory->getPBKDF() instanceof \CryptLib\Key\Derivation\PBKDF\PBKDF2);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetPBKDFFail() {
        $factory = new Factory;
        $factory->getPBKDF('someGibberish');
    }

    public function testGetSymmetricKeyGenerator() {
        $factory = new Factory;
        $this->assertTrue(is_null($factory->getSymmetricKeyGenerator()));
    }
}
