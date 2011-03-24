<?php

require_once 'vfsStream/vfsStream.php';

use CryptLibTest\Mocks\Core\Factory;

class Unit_Core_AbstractFactoryTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        vfsStreamWrapper::register();
        $root = new vfsStreamDirectory('CryptLibTest');
        $core = vfsStream::newDirectory('Core')->at($root);
        $af = vfsStream::newDirectory('AbstractFactory')->at($core);
        vfsStream::newFile('test.php')->at($af);
        vfsStream::newFile('Some234Foo234Bar98Name.php')->at($af);
        vfsStream::newFile('Invalid.csv')->at($af);

        vfsStreamWrapper::setRoot($root);
    }

    /**
     * @covers CryptLib\Core\AbstractFactory::registerType
     */
    public function testRegisterType() {
        $factory = new Factory;
        $factory->registerType('test', 'iteratoraggregate', 'foo', 'ArrayObject', false); 
    }

    /**
     * @covers CryptLib\Core\AbstractFactory::registerType
     * @expectedException InvalidArgumentException
     */
    public function testRegisterTypeFail() {
        $factory = new Factory;
        $factory->registerType('test', 'iterator', 'foo', 'ArrayObject', false); 
    }

    /**
     * @covers CryptLib\Core\AbstractFactory::registerType
     */
    public function testRegisterTypeInstantiate() {
        $factory = new Factory;
        $factory->registerType('test', 'iteratoraggregate', 'foo', 'ArrayObject', true); 
    }

    public function testLoadFiles() {
        $dir = vfsStream::url('CryptLibTest/Core/AbstractFactory');
        $result = array();
        $factory = new Factory(array('storeResult' => function($name, $class) use (&$result) { $result[$name] = $class; }));
        $factory->loadFiles($dir, 'foo\\', 'storeResult');
        $this->assertEquals(array('test' => 'foo\\test', 'Some234Foo234Bar98Name' => 'foo\\Some234Foo234Bar98Name'), $result);
    }


}
