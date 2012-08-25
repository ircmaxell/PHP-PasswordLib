<?php

use PasswordLib\Core\Strength\Medium as MediumStrength;
use PasswordLibTest\Mocks\Hash\Hash as MockHash;
use PasswordLibTest\Mocks\Hash\Factory as MockFactory;
use PasswordLibTest\Mocks\Random\Generator as MockGenerator;
use PasswordLib\Password\Implementation\SHA512;

require_once 'Password_TestCase.php';

class Unit_Password_Implementation_SHA512Test extends Unit_Password_Implementation_Password_TestCase {

    protected $class = 'PasswordLib\Password\Implementation\SHA512';

    public static function provideTestDetect() {
        return array(
            array(SHA512::getPrefix(), false),
            array('$6$', false),
            array(SHA512::getPrefix() . '................$12345678901234567890123456789012345678901234567890123456789012345678901234567890123456', true),
            array('$2$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),
            array(SHA512::getPrefix() . '07$usesome illystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),
            array(SHA512::getPrefix() . '01$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),

        );
    }

    public static function provideTestCreate() {
        return array(
            array(4, 'foo', SHA512::getPrefix() . 'rounds=1000$................$DzEAWetj/cXAPD/tGmEgpqyosAIZjLaRQI5DKcZYKSGFbk.mGzvRkDy3skMGqnkS4jRvrFjObXjiv.i5Bnob41'),
            array(6, 'bar', SHA512::getPrefix() . 'rounds=1000$................$lKPnJbXtGAHAid5g7OPcHO3GZjaKv4osoaSPnNAq./mZ4dyGoq9IbAG8d9fcTJ1cxvEALMPki.mbzmNEHjY9b1'),
            array(8, 'baz', SHA512::getPrefix() . 'rounds=1000$................$WZTe6NH6a0MA4vcOjJ9nKZP2hLvr9GhPvYqlOargbJNpzQaluc5sEe.Ep/PF2D79haaMPsFRGsnA2YEW3d7wx1'),
        );
    }

    public static function provideTestVerifyFail() {
        return array(
            array(10, 'foo', SHA512::getPrefix() . 'rounds=1000$................$DzEAWetj/cXAPD/tfmEgpqyosAIZjLaRQI5DKcZYKSGFbk.mGzvRkDy3skMGqnkS4jRvrFjObXjiv.i5Bnob41'),
            array(12, 'bar', SHA512::getPrefix() . 'rounds=1001$................$lKPnJbXtGAHAid5g7OPcHO3GZjaKv4osoaSPnNAq./mZ4dyGoq9IbAG8d9fcTJ1cxvEALMPki.mbzmNEHjY9b1'),
            array(14, 'baz', SHA512::getPrefix() . 'rounds=1000$...............1$WZTe6NH6a0MA4vcOjJ9nKZP2hLvr9GhPvYqlOargbJNpzQaluc5sEe.Ep/PF2D79haaMPsFRGsnA2YEW3d7wx1'),
        );
    }

    public static function provideTestVerifyFailException() {
        return array(
            array(10, 'foo', SHA512::getPrefix() . '04$......................wy8 y4IYV94XATD85vz/zPNKyDLSamC'),
            array(12, 'bar', '$2b$04$......................wy8Ny4IYV94XATD85vz/zPNKyDLSamC'),
            array(14, 'baz', SHA512::getPrefix() . '02$......................wy8Ny4IYV94XATD85vz/zPNKyDLSamC'),
        );
    }

    public function testGetPrefix() {
        $this->assertEquals('$6$', SHA512::getPrefix());
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     * @dataProvider provideTestDetect
     */
    public function testDetect($from, $expect) {
        $this->assertEquals($expect, SHA512::detect($from));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     */
    public function testLoadFromHash() {
        $test = SHA512::loadFromHash('$6$rounds=1000$................$DzEAWetj/cXAPD/tGmEgpqyosAIZjLaRQI5DKcZYKSGFbk.mGzvRkDy3skMGqnkS4jRvrFjObXjiv.i5Bnob41');
        $this->assertTrue($test instanceof SHA512);
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     * @expectedException InvalidArgumentException
     */
    public function testLoadFromHashFail() {
        SHA512::loadFromHash('foo');
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     */
    public function testConstruct() {
        $hash = new SHA512();
        $this->assertTrue($hash instanceof SHA512);
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     */
    public function testConstructArgs() {
        $iterations = 10;
        $gen = $this->getRandomGenerator(function($size) {});
        $apr = new SHA512($iterations, $gen);
        $this->assertTrue($apr instanceof SHA512);
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailFail() {
        $hash = new SHA512(40);
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     */
    public function testCreateAndVerify() {
        $hash = new SHA512(10);
        $test = $hash->create('Foobar');
        $this->assertTrue($hash->verify('Foobar', $test));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     * @dataProvider provideTestCreate
     */
    public function testCreate($iterations, $pass, $expect) {
        $apr = $this->getSHA512MockInstance($iterations);
        $this->assertEquals($expect, $apr->create($pass));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     * @dataProvider provideTestCreate
     */
    public function testVerify($iterations, $pass, $expect) {
        $apr = $this->getSHA512MockInstance($iterations);
        $this->assertTrue($apr->verify($pass, $expect));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     * @dataProvider provideTestVerifyFail
     */
    public function testVerifyFail($iterations, $pass, $expect) {
        $apr = $this->getSHA512MockInstance($iterations);
        $this->assertFalse($apr->verify($pass, $expect));
    }

    /**
     * @covers PasswordLib\Password\Implementation\SHA512
     * @dataProvider provideTestVerifyFailException
     * @expectedException InvalidArgumentException
     */
    public function testVerifyFailException($iterations, $pass, $expect) {
        $apr = $this->getSHA512MockInstance($iterations);
        $apr->verify($pass, $expect);
    }

    protected function getSHA512MockInstance($iterations) {
        $gen = $this->getRandomGenerator(function($size) {
            return str_repeat(chr(0), $size);
        });
        return new SHA512($iterations, $gen);
    }

    protected function getSHA512Instance($evaluate, $hmac, $generate) {
        $generator = $this->getRandomGenerator($generate);
        return new SHA512(10, $generator);
    }

    protected function getRandomGenerator($generate) {
        return new MockGenerator(array(
            'generate' => $generate
        ));
    }

}
