<?php

use CryptLib\Cipher\Factory;

class Unit_Cipher_FactoryTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $factory = new Factory;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRegisterCipherFail() {
        $factory = new Factory;
        $factory->registerCipher('test', 'stdClass');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRegisterModeFail() {
        $factory = new Factory;
        $factory->registerMode('test', 'stdClass');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetBlockCipherFail() {
        $factory = new Factory;
        $factory->getBlockCipher('foobarbaz');
    }

    public function testGetBlockCipher() {
        $factory = new Factory;
        $aes = $factory->getBlockCipher('aes-128');
        $this->assertTrue($aes instanceof \CryptLib\Cipher\Block\Cipher\AES);
    }

    public function testGetBlockCipherObject() {
        $factory = new Factory;
        $des = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $actual = $factory->getBlockCipher($des);
        $this->assertEquals($des, $actual);
    }

    public function testGetMode() {
        $factory = new Factory;
        $des = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $mode = $factory->getMode('cbc', $des, 'foobarba', array());
        $this->assertTrue($mode instanceof \CryptLib\Cipher\Block\Mode\CBC);
    }

    public function testGetModeObject() {
        $factory = new Factory;
        $des = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $mode = new \CryptLib\Cipher\Block\Mode\CBC($des, 'foobarba', array());
        $actual = $factory->getMode($mode, $des, 'foobarba', array());
        $this->assertEquals($mode, $actual);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetModeFail() {
        $factory = new Factory;
        $des = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $factory->getMode('blah', $des, 'foobarba', array());
    }
}
