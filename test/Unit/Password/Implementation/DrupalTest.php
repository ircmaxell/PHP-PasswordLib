<?php

use CryptLib\Core\Strength\Medium as MediumStrength;
use CryptLibTest\Mocks\Hash\Hash as MockHash;
use CryptLibTest\Mocks\Hash\Factory as MockFactory;
use CryptLibTest\Mocks\Random\Generator as MockGenerator;
use CryptLib\Password\Implementation\Drupal;

class Unit_Hash_Implementation_DrupalTest extends PHPUnit_Framework_TestCase {

    public static function provideTestDetect() {
        return array(
            array('$P$', false),
            array('$S$', false),
            array('$S$ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz./ABCDEFGHIJKLMNOPQRSTUVWXYZ01234', true),
            array('$S$ABCDEFGHIJKLMNOPQRSTUVWXYZ012  56789abcdefghijklmnopqrstuvwxyz./ABCDEFGHIJKLMNOPQRSTUVWXYZ01234', false),
            array('$P$ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz./ABCDEFGHIJKLMNOPQRSTUVWXYZ01234', false),
            array('$H$ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz./ABCDEFGHIJKLMNOPQRSTUVWXYZ01234', false),

        );
    }
    
    public static function provideTestCreate() {
        return array(
            array(10, 'foo', '$S$8........u9PH9ZMowV1f3sR2VX.YMyU5IvKjn8XsQOo6AIIJDbKnT3bdYztQdz2R1/P7YLxxsaAoK2aM.DlN8BoZV3.Fa0'),
            array(12, 'bar', '$S$A........3QBQPGxacHssvSgTWZ4zteafujOLj8VTg52YYt7HkGgeRePmuCAe7PqrPF.WRP6mdvdH9FpkucPD1L4xMwVFw.'),
            array(14, 'baz', '$S$C........yDYVEB5.jG8aOZh/41LQ8Ntz5ABb6zfm.I/jevKDWvMhzatnR8e6SH93nxagKcEGo.y7nHYMD.IdMMbeUR6eX.'),
        );
    }
    
    public static function provideTestVerifyFail() {
        return array(
            array(10, 'foo', '$S$8...3....u9PH9ZMowV1f3sR2VX.YMyU5IvKjn8XsQOo6AIIJDbKnT3bdYztQdz2R1/P7YLxxsaAoK2aM.DlN8BoZV3.Fa0'),
            array(12, 'bar', '$S$A.F......3QBQPGxacHssvSgTWZ4zteafujOLj8VTg52YYt7HkGgeRePmuCAe7PqrPF.WRP6mdvdH9FpkucPD1L4xMwVFw.'),
            array(14, 'baz', '$S$C........yDYVEB5.jG8aOZh/41LQ8Ntz5ABb6zfm.I/jevKDWvMhzatnR8e6SH93nDagKcEGo.y7nHYMD.IdMMbeUR6eX.'),
        );
    }
    
    public static function provideTestVerifyFailException() {
        return array(
            array(10, 'foo', '$S$A........u9PH9ZMowV1f3sR2VX.YMyU5IvKjn8XsQOo6AIIJDbKnT3bdYztQdz2R1/P7YLxxsaAoK2aM.DlN8BoZV3.Fa0'),
            array(12, 'bar', '$S$C........3QBQPGxacHssvSgTWZ4zteafujOLj8VTg52YYt7HkGgeRePmuCAe7PqrPF.WRP6mdvdH9FpkucPD1L4xMwVFw.'),
            array(14, 'baz', '$S$8........yDYVEB5.jG8aOZh/41LQ8Ntz5ABb6zfm.I/jevKDWvMhzatnR8e6SH93nxagKcEGo.y7nHYMD.IdMMbeUR6eX.'),
        );
    }

    /**
     * @covers CryptLib\Password\Implementation\Drupal
     * @dataProvider provideTestDetect
     */
    public function testDetect($from, $expect) {
        $this->assertEquals($expect, Drupal::detect($from));
    }

    /**
     * @covers CryptLib\Password\Implementation\Drupal
     */
    public function testLoadFromHash() {
        $test = Drupal::loadFromHash('$S$ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz./ABCDEFGHIJKLMNOPQRSTUVWXYZ01234');
        $this->assertTrue($test instanceof Drupal);
    }

    /**
     * @covers CryptLib\Password\Implementation\Drupal
     * @expectedException InvalidArgumentException
     */
    public function testLoadFromHashFail() {
        Drupal::loadFromHash('foo');
    }

    /**
     * @covers CryptLib\Password\Implementation\Drupal
     */
    public function testConstruct() {
        $hash = new Drupal();
        $this->assertTrue($hash instanceof Drupal);
    }

    /**
     * @covers CryptLib\Password\Implementation\Drupal
     */
    public function testConstructArgs() {
        $iterations = 10;
        $gen = $this->getRandomGenerator(function($size) {});
        $fac = $this->getHashFactory(function() {}, function() {});
        $apr = new Drupal($iterations, $gen, $fac);
        $this->assertTrue($apr instanceof Drupal);
    }
    
    /**
     * @covers CryptLib\Password\Implementation\Drupal
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailFail() {
        $hash = new Drupal(40);
    }
    
    /**
     * @covers CryptLib\Password\Implementation\Drupal
     */
    public function testCreateAndVerify() {
        $hash = new Drupal(10);
        $test = $hash->create('Foobar');
        $this->assertTrue($hash->verify($test, 'Foobar'));
    }

    /**
     * @covers CryptLib\Password\Implementation\Drupal
     * @dataProvider provideTestCreate
     */
    public function testCreate($iterations, $pass, $expect) {
        $apr = $this->getDrupalMockInstance($iterations);
        $this->assertEquals($expect, $apr->create($pass));
    }

    /**
     * @covers CryptLib\Password\Implementation\Drupal
     * @dataProvider provideTestCreate
     */
    public function testVerify($iterations, $pass, $expect) {
        $apr = $this->getDrupalMockInstance($iterations);
        $this->assertTrue($apr->verify($expect, $pass));
    }

    /**
     * @covers CryptLib\Password\Implementation\Drupal
     * @dataProvider provideTestVerifyFail
     */
    public function testVerifyFail($iterations, $pass, $expect) {
        $apr = $this->getDrupalMockInstance($iterations);
        $this->assertFalse($apr->verify($expect, $pass));
    }
    
    /**
     * @covers CryptLib\Password\Implementation\Drupal
     * @dataProvider provideTestVerifyFailException
     * @expectedException InvalidArgumentException
     */
    public function testVerifyFailException($iterations, $pass, $expect) {
        $apr = $this->getDrupalMockInstance($iterations);
        $apr->verify($expect, $pass);
    }

    protected function getDrupalMockInstance($iterations) {
        $gen = $this->getRandomGenerator(function($size) {
            return str_repeat(chr(0), $size);
        });
        $fac = $this->getHashFactory(
            function($data) {
                return hash('sha512', $data, true);
            },
            function($data, $key) {
                return hash_hmac('sha512', $data, $key, true);
            }
        );
        return new Drupal($iterations, $gen, $fac);
    }

    protected function getDrupalInstance($evaluate, $hmac, $generate) {
        $generator = $this->getRandomGenerator($generate);
        $factory = $this->getHashFactory($evaluate, $hmac);
        return new Drupal($generator, $factory);
    }

    protected function getRandomGenerator($generate) {
        return new MockGenerator(array(
            'generate' => $generate
        ));
    }

    protected function getHashFactory($evaluate, $hmac) {
        $mock = new MockHash(array(
            'getName' => function() { return 'sha512'; },
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
