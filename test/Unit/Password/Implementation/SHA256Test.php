<?php

use PasswordLib\Core\Strength\Medium as MediumStrength;
use PasswordLibTest\Mocks\Hash\Hash as MockHash;
use PasswordLibTest\Mocks\Hash\Factory as MockFactory;
use PasswordLibTest\Mocks\Random\Generator as MockGenerator;
use PasswordLib\Password\Implementation\SHA256;

require_once 'Password_TestCase.php';

class Unit_Password_Implementation_SHA256Test extends Unit_Password_Implementation_Password_TestCase {

    protected $class = 'PasswordLib\Password\Implementation\SHA256';

    public static function provideTestDetect() {
        return array(
            array(SHA256::getPrefix(), false),
            array('$6$', false),
            array(SHA256::getPrefix() . '................$0123456789012345678901234567890123456789123', true),
            array('$2$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),
            array(SHA256::getPrefix() . '07$usesome illystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),
            array(SHA256::getPrefix() . '01$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),

        );
    }

    public static function provideTestCreate() {
        return array(
            array(4, 'foo', SHA256::getPrefix() . 'rounds=1000$................$expjG7P4AN4svmCMHxzkAc.s8gNGp0fu4bYVVY8iQo1'),
            array(6, 'bar', SHA256::getPrefix() . 'rounds=1000$................$NYlBKYVTrvSD1CYbsBDngbAm7kyAJk/D9XX.3528r05'),
            array(8, 'baz', SHA256::getPrefix() . 'rounds=1000$................$sN32z5PIeyCOerA52tXRmNvbdcwPd/FqWAmZelaX9z6'),
        );
    }

    public static function provideTestVerifyFail() {
        return array(
            array(10, 'foo', SHA256::getPrefix() . 'rounds=1000$................$dxpjG7P4AN4svmCMHxzkAc.s8gNGp0fu4bYVVY8iQo1'),
            array(12, 'bar', SHA256::getPrefix() . 'rounds=1001$................$NYlBKYVTrvSD1CYbsBDngbAm7kyAJk/D9XX.3528r05'),
            array(14, 'baz', SHA256::getPrefix() . 'rounds=1000$...............1$sN32z5PIeyCOerA52tXRmNvbdcwPd/FqWAmZelaX9z6'),
        );
    }

    public static function provideTestVerifyFailException() {
        return array(
            array(10, 'foo', SHA256::getPrefix() . 'rounds=1000$................$dxpjG7P4AN4svmCMHxzkAc.s8gNGp0fu4bYVVY'),
            array(12, 'bar', '$2b$04$......................wy8Ny4IYV94XATD85vz/zPNKyDLSamC'),
            array(14, 'baz', SHA256::getPrefix() . '02$......................wy8Ny4IYV94XATD85vz/zPNKyDLSamC'),
        );
    }

    public function testGetPrefix() {
        $this->assertEquals('$5$', SHA256::getPrefix());
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     * @dataProvider provideTestDetect
     */
    public function testDetect($from, $expect) {
        $this->assertEquals($expect, SHA256::detect($from));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     */
    public function testLoadFromHash() {
        $test = SHA256::loadFromHash('$5$rounds=1000$................$0123456789012345678901234567890123456789123');
        $this->assertTrue($test instanceof SHA256);
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     * @expectedException InvalidArgumentException
     */
    public function testLoadFromHashFail() {
        SHA256::loadFromHash('foo');
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     */
    public function testConstruct() {
        $hash = new SHA256();
        $this->assertTrue($hash instanceof SHA256);
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     */
    public function testConstructArgs() {
        $iterations = 10;
        $gen = $this->getRandomGenerator(function($size) {});
        $apr = new SHA256($iterations, $gen);
        $this->assertTrue($apr instanceof SHA256);
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailFail() {
        $hash = new SHA256(40);
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     */
    public function testCreateAndVerify() {
        $hash = new SHA256(10);
        $test = $hash->create('Foobar');
        $this->assertTrue($hash->verify('Foobar', $test));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     * @dataProvider provideTestCreate
     */
    public function testCreate($iterations, $pass, $expect) {
        $apr = $this->getSHA256MockInstance($iterations);
        $this->assertEquals($expect, $apr->create($pass));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     * @dataProvider provideTestCreate
     */
    public function testVerify($iterations, $pass, $expect) {
        $apr = $this->getSHA256MockInstance($iterations);
        $this->assertTrue($apr->verify($pass, $expect));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     * @dataProvider provideTestVerifyFail
     */
    public function testVerifyFail($iterations, $pass, $expect) {
        $apr = $this->getSHA256MockInstance($iterations);
        $this->assertFalse($apr->verify($pass, $expect));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA256
     * @dataProvider provideTestVerifyFailException
     * @expectedException InvalidArgumentException
     */
    public function testVerifyFailException($iterations, $pass, $expect) {
        $apr = $this->getSHA256MockInstance($iterations);
        $apr->verify($pass, $expect);
    }

    protected function getSHA256MockInstance($iterations) {
        $gen = $this->getRandomGenerator(function($size) {
            return str_repeat(chr(0), $size);
        });
        return new SHA256($iterations, $gen);
    }

    protected function getSHA256Instance($evaluate, $hmac, $generate) {
        $generator = $this->getRandomGenerator($generate);
        return new SHA256(10, $generator);
    }

    protected function getRandomGenerator($generate) {
        return new MockGenerator(array(
            'generate' => $generate
        ));
    }

}
