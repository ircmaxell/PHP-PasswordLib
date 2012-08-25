<?php

use PasswordLib\Core\Strength\Medium as MediumStrength;
use PasswordLibTest\Mocks\Hash\Hash as MockHash;
use PasswordLibTest\Mocks\Hash\Factory as MockFactory;
use PasswordLibTest\Mocks\Random\Generator as MockGenerator;
use PasswordLib\Password\Implementation\Crypt;

require_once 'Password_TestCase.php';

class Unit_Password_Implementation_CryptTest extends Unit_Password_Implementation_Password_TestCase {

    protected $class = 'PasswordLib\Password\Implementation\Crypt';

    public static function provideTestDetect() {
        return array(
            array(Crypt::getPrefix(), false),
            array('$2$', false),
            array(Crypt::getPrefix() . 'aaaaaaaaaaaaa', true),
            array('$2$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),
            array(Crypt::getPrefix() . '07$usesome illystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),
            array(Crypt::getPrefix() . '01$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi', false),

        );
    }

    public static function provideTestCreate() {
        return array(
            array(4, 'foo', Crypt::getPrefix() . '..XXIp7/c78Qo'),
            array(6, 'bar', Crypt::getPrefix() . '..kZqxH9.sDlQ'),
            array(8, 'baz', Crypt::getPrefix() . '..5OFGNm8a1Yk'),
        );
    }

    public static function provideTestVerifyFail() {
        return array(
            array(10, 'foo', Crypt::getPrefix() . '..XXIp7/c79Qo'),
            array(12, 'bar', Crypt::getPrefix() . '..kZqxH9.sD2Q'),
            array(14, 'baz', Crypt::getPrefix() . '..5OFGNm8a1Yl'),
        );
    }

    public static function provideTestVerifyFailException() {
        return array(
            array(10, 'foo', Crypt::getPrefix() . '04$......................wy8 y4IYV94XATD85vz/zPNKyDLSamC'),
            array(12, 'bar', '$2b$04$......................wy8Ny4IYV94XATD85vz/zPNKyDLSamC'),
            array(14, 'baz', Crypt::getPrefix() . '02$......................wy8Ny4IYV94XATD85vz/zPNKyDLSamC'),
        );
    }

    public function testGetPrefix() {
        $this->assertEquals(false, Crypt::getPrefix());
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     * @dataProvider provideTestDetect
     */
    public function testDetect($from, $expect) {
        $this->assertEquals($expect, Crypt::detect($from));
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     */
    public function testLoadFromHash() {
        $test = Crypt::loadFromHash('aaaaaaaaaaaaa');
        $this->assertTrue($test instanceof Crypt);
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     * @expectedException InvalidArgumentException
     */
    public function testLoadFromHashFail() {
        Crypt::loadFromHash('foo');
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     */
    public function testConstruct() {
        $hash = new Crypt();
        $this->assertTrue($hash instanceof Crypt);
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     */
    public function testConstructArgs() {
        $iterations = 10;
        $gen = $this->getRandomGenerator(function($size) {});
        $apr = new Crypt($iterations, $gen);
        $this->assertTrue($apr instanceof Crypt);
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailFail() {
        $hash = new Crypt(40);
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     */
    public function testCreateAndVerify() {
        $hash = new Crypt(10);
        $test = $hash->create('Foobar');
        $this->assertTrue($hash->verify('Foobar', $test));
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     * @dataProvider provideTestCreate
     */
    public function testCreate($iterations, $pass, $expect) {
        $apr = $this->getCryptMockInstance($iterations);
        $this->assertEquals($expect, $apr->create($pass));
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     * @dataProvider provideTestCreate
     */
    public function testVerify($iterations, $pass, $expect) {
        $apr = $this->getCryptMockInstance($iterations);
        $this->assertTrue($apr->verify($pass, $expect));
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     * @dataProvider provideTestVerifyFail
     */
    public function testVerifyFail($iterations, $pass, $expect) {
        $apr = $this->getCryptMockInstance($iterations);
        $this->assertFalse($apr->verify($pass, $expect));
    }

    /**
     * @covers PasswordLib\Password\Implementation\Crypt
     * @dataProvider provideTestVerifyFailException
     * @expectedException InvalidArgumentException
     */
    public function testVerifyFailException($iterations, $pass, $expect) {
        $apr = $this->getCryptMockInstance($iterations);
        $apr->verify($pass, $expect);
    }

    protected function getCryptMockInstance($iterations) {
        $gen = $this->getRandomGenerator(function($size) {
            return str_repeat(chr(0), $size);
        });
        return new Crypt($iterations, $gen);
    }

    protected function getCryptInstance($evaluate, $hmac, $generate) {
        $generator = $this->getRandomGenerator($generate);
        return new Crypt(10, $generator);
    }

    protected function getRandomGenerator($generate) {
        return new MockGenerator(array(
            'generate' => $generate
        ));
    }

}
