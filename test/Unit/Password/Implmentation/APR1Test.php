<?php

use CryptLib\Core\Strength\Medium as MediumStrength;
use CryptLibTest\Mocks\Hash\Hash as MockHash;
use CryptLibTest\Mocks\Hash\Factory as MockFactory;
use CryptLibTest\Mocks\Random\Generator as MockGenerator;
use CryptLib\Password\Implementation\APR1;

class Unit_Hash_Implementation_APR1Test extends PHPUnit_Framework_TestCase {

    public static function provideTestCreate() {
        return array(
            array('foobar', '$apr1$6mq6m/..$n7e/M1BvwwixR9jcPrB9I.'),
            array('foobaz', '$apr1$6mq6m/..$bsmcmj32fUcKerAIJFDFF1'),
            array('foobarbazbizbuz1234567890', '$apr1$6mq6m/..$46tsm5uassQZB.j.Q5WTI.'),
        );
    }

    public static function provideTestVerify() {
        return array(
            array('foobar', '$apr1$6mq6m/..$n7e/M1BvwwixR9jcPrB9I.'),
            array('foobaz', '$apr1$6mq6m/..$bsmcmj32fUcKerAIJFDFF1'),
            array('foobarbazbizbuz1234567890', '$apr1$6mq6m/..$46tsm5uassQZB.j.Q5WTI.'),
            array('foobarbizbuz', '$apr1$z/W7/...$SeG7ikf6IE1sQhd9yMjf//'),
            array('1234', '$apr1$.O.9/...$jZe.HQ2I/ewbGCnSl0wTS.'),
        );
    }

    public static function provideTestVerifyFail() {
        return array(
            array('foo', 'bar'),
            //Salt Change
            array('foobar', '$apr1$6mi6m/..$n7e/M1BvwwixR9jcPrB9I.'),
            //Password Change
            array('foobuz', '$apr1$6mq6m/..$n7e/M1BvwwixR9jcPrB9I.'),
            //Hash Change
            array('foobarbazbizbuz1234567890', '$apr1$6mq6m/..$46tsm5uAssQZB.j.Q5WTI.'),
        );
    }

    public static function provideTestDetect() {
        return array(
            array('$apr1$foo', true),
            array('$apr2$bar', false),
            array(md5('test'), false),
        );
    }

    /**
     * @dataProvider provideTestDetect
     */
    public function testDetect($from, $expect) {
        $this->assertEquals($expect, APR1::detect($from));
    }

    public function testLoadFromHash() {
        $test = APR1::loadFromHash('$apr1$foo');
        $this->assertTrue($test instanceof APR1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testLoadFromHashFail() {
        APR1::loadFromHash('foo');
    }

    public function testConstruct() {
        $apr = new APR1();
        $this->assertTrue($apr instanceof APR1);
    }

    public function testConstructArgs() {
        $gen = $this->getRandomGenerator(function($size) {});
        $fac = $this->getHashFactory(function() {}, function() {});
        $apr = new APR1($gen, $fac);
        $this->assertTrue($apr instanceof APR1);
    }

    /**
     * @dataProvider provideTestCreate
     */
    public function testCreate($pass, $expect) {
        $apr = $this->getAPR1MockInstance();
        $this->assertEquals($expect, $apr->create($pass));
    }

    /**
     * @dataProvider provideTestVerify
     */
    public function testVerify($pass, $expect) {
        $apr = $this->getAPR1MockInstance();
        $this->assertTrue($apr->verify($expect, $pass));
    }

    /**
     * @dataProvider provideTestVerifyFail
     */
    public function testVerifyFail($pass, $expect) {
        $apr = $this->getAPR1MockInstance();
        $this->assertFalse($apr->verify($expect, $pass));
    }

    protected function getAPR1MockInstance() {
        $gen = $this->getRandomGenerator(function($min, $max) {
            return 1914924168;
        });
        $fac = $this->getHashFactory(
            function($data) {
                return md5($data, true);
            },
            function($data, $key) {
		return hash_hmac('md5', $data, $key, true);
            }
        );
        return new APR1($gen, $fac);
    }

    protected function getAPR1Instance($evaluate, $hmac, $generate) {
        $generator = $this->getRandomGenerator($generate);
        $factory = $this->getHashFactory($evaluate, $hmac);
        return new APR1($generator, $factory);
    }

    protected function getRandomGenerator($generate) {
        return new MockGenerator(array(
            'generateInt' => $generate
        ));
    }

    protected function getHashFactory($evaluate, $hmac) {
        $mock = new MockHash(array(
            'getName' => function() { return 'md5'; },
            'getSize' => function () { return 16; },
            'evaluate' => $evaluate,
            'hmac' => $hmac 
        ));
        $factory = new MockFactory(array(
            'getHash' => function($name) use ($mock) { return $mock; },
        ));
        return $factory;
    }


}
