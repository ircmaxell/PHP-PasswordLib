<?php

use CryptLib\Key\Derivation\PBKDF\Schneier;

class Unit_Key_Derivation_PBKDF_SchneierTest extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 'salt', 1, 20, 'sha1', 'c88e9c67041a74e0357befdff93f87dde0904214'),
            array('password', 'salt', 2, 20, 'sha256', 'fb4c44fa4483a3aa6c756f73c0126d3d1cc88794'),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\Schneier
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new Schneier();
        $this->assertTrue($pb instanceof Schneier);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\Schneier::derive
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $s, $c, $len, $hash, $expect) {
        $pb = new Schneier(array('hash'=>$hash));
        $actual = $pb->derive($p, $s, $c, $len);
        $this->assertEquals($expect, bin2hex($actual));
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\Schneier
     * @expectedException InvalidArgumentException
     */
    public function testDeriveFail() {
        $pb = new Schneier(array('hash'=>'sha1'));
        $pb->derive('pass', 'salt', 1, strlen(sha1('', true)) + 1);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\Schneier::getSignature
     */
    public function testGetSignature() {
        $pb = new Schneier(array('hash' => 'test'));
        $this->assertEquals('schneier-test', $pb->getSignature());
    }
}
