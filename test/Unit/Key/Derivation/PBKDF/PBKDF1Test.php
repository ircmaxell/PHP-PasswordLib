<?php

use CryptLib\Key\Derivation\PBKDF\PBKDF1;

class Unit_Key_Derivation_PBKDF_PBKDF1Test extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 'salt', 1, 20, 'sha1', 'c88e9c67041a74e0357befdff93f87dde0904214'),
            array('password', 'salt', 2, 20, 'sha256', 'a6b9d96cc74d52749372886896349c07e2137fe8'),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\PBKDF1
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new PBKDF1();
        $this->assertTrue($pb instanceof PBKDF1);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\PBKDF1::derive
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $s, $c, $len, $hash, $expect) {
        $pb = new PBKDF1(array('hash'=>$hash));
        $actual = $pb->derive($p, $s, $c, $len);
        $this->assertEquals($expect, bin2hex($actual));
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\PBKDF1
     * @expectedException InvalidArgumentException
     */
    public function testDeriveFail() {
        $pb = new PBKDF1(array('hash'=>'sha1'));
        $pb->derive('pass', 'salt', 1, strlen(sha1('', true)) + 1);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\PBKDF1::getSignature
     */
    public function testGetSignature() {
        $pb = new PBKDF1(array('hash' => 'test'));
        $this->assertEquals('pbkdf1-test', $pb->getSignature());
    }
}
