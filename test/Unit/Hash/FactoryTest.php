<?php

use CryptLibTest\Mocks\Hash\Hash;

use CryptLib\Hash\Factory;

class Unit_Hash_FactoryTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers CryptLib\Hash\Factory::__construct
     * @covers CryptLib\Hash\Factory::loadImplementations
     * @covers CryptLib\Hash\Factory::registerAlgo
     */
    public function testConstruct() {
        $factory = new Factory;
        $this->assertTrue($factory instanceof CryptLib\Hash\Factory);
    }

    /**
     * @covers CryptLib\Hash\Factory::registerAlgo
     * @covers CryptLib\Hash\Factory::getAlgos
     */
    public function testRegisterAlgo() {
        $factory = new Factory;
        Hash::$algos = array('footest');
        $factory->registerAlgo('test', 'CryptLibTest\Mocks\Hash\Hash');
        $algos = $factory->getAlgos();
        $this->assertTrue(isset($algos['footest']));
    }

    /**
     * @covers CryptLib\Hash\Factory::registerAlgo
     * @expectedException InvalidArgumentException
     */
    public function testRegisterAlgoFail() {
        $factory = new Factory;
        $factory->registerAlgo('test', 'stdclass');
    }

    /**
     * @covers CryptLib\Hash\Factory::getHash
     */
    public function testGetHashCore() {
        if (!function_exists('hash')) {
            $this->markTestSkipped('Hash Extension Is Required To Test Core');
            return false;
        }
        $factory = new Factory;
        $this->assertTrue($factory->getHash('md5') instanceof CryptLib\Hash\Implementation\Core);
    }
 
    /**
     * @covers CryptLib\Hash\Factory::getHash
     */
    public function testGetHashNonCore() {
        $factory = new Factory;
        Hash::$algos = array('footest');
        $factory->registerAlgo('test', 'CryptLibTest\Mocks\Hash\Hash');
        $this->assertTrue($factory->getHash('footest') instanceof Hash);
    }

    /**
     * @covers CryptLib\Hash\Factory::getHash
     */
    public function testGetHashInstance() {
        $factory = new Factory;
        Hash::$algos = array('footest');
        $hash = new Hash;
        $this->assertSame($hash, $factory->getHash($hash));
    }

    /**
     * @covers CryptLib\Hash\Factory::getHash
     * @expectedException RuntimeException
     */
    public function testGetHashFailure() {
        $factory = new Factory;
        $factory->getHash('somenonexistanthash');
    }
 

}
