<?php

use PasswordLib\Password\Factory;
use PasswordLib\Password\Implementation\Blowfish;

class Unit_Password_FactoryTest extends PHPUnit_Framework_TestCase {

    public static function provideTestCreate() {
        return array(
            array(Blowfish::getPrefix(), 60),
            array('$apr1$', 37),
            array('$S$', 98),
            array('$P$', 34),
            array('$H$', 34),
            array('$pbkdf$', 74),
        );
    }

    public function testConstruct() {
        $factory = new Factory;
    }

    /**
     * @dataProvider provideTestCreate
     */
    public function testCreate($algo, $size) {
        $factory = new Factory;
        $text = $factory->createHash('foo', $algo);
        $this->assertEquals($size, strlen($text));
    }

    /**
     * @expectedException DomainException
     */
    public function testCreateFailure() {
        $factory = new Factory;
        $text = $factory->createHash('foo', '$abcdef$');
    }

    /**
     * @expectedException DomainException
     */
    public function testCreateFailure2() {
        $factory = new Factory;
        $text = $factory->createHash('foo', false);
    }

    /**
     * @dataProvider provideTestCreate
     */
    public function testCreateThenVerify($algo) {
        $factory = new Factory;
        $text = $factory->createHash('foo', $algo);
        $this->assertTrue($factory->verifyHash('foo', $text));
    }

    /**
     * @expectedException DomainException
     */
    public function testVerifyFailure() {
        $factory = new Factory;
        $text = $factory->verifyHash('foo', 'foo');
    }

    public function testVerifyMD5() {
        $factory = new Factory;
        $this->assertTrue($factory->verifyHash('foo', md5('foo')));
    }

    public function testVerifySHA1() {
        $factory = new Factory;
        $this->assertTrue($factory->verifyHash('foo', sha1('foo')));
    }

    public function testVerifySHA256() {
        $factory = new Factory;
        $this->assertTrue($factory->verifyHash('foo', hash('sha256', 'foo')));
    }

    public function testVerifySHA512() {
        $factory = new Factory;
        $this->assertTrue($factory->verifyHash('foo', hash('sha512', 'foo')));
    }

    public function testGetNullImplementation() {
        $factory = new Factory;
        $this->assertNull($factory->getImplementation());
    }

    public function testGetValidImplementation() {
        $factory = new Factory;
        $factory->setPrefix(Blowfish::getPrefix())->setImplementation();
        $impl = $factory->getImplementation();

        $this->assertInstanceOf('\PasswordLib\Password\Implementation\Blowfish', $impl);
    }

    /**
     * @expectedException DomainException
     */
    public function testSetInvalidPrefix() {
        $factory = new Factory;
        $factory->setPrefix(false);
    }

    public function testGetSetPrefix() {
        $factory = new Factory;
        $factory->setPrefix(Blowfish::getPrefix());

        $this->assertEquals($factory->getPrefix(), Blowfish::getPrefix());
    }

    public function testGetSetImplemntation() {
        $factory = new Factory;
        $impl = new \PasswordLib\Password\Implementation\Blowfish();
        $factory->setImplementation($impl);

        $this->assertEquals($impl, $factory->getImplementation());
    }
}
