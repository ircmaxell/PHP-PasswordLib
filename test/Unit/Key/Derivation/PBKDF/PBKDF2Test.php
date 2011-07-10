<?php

use CryptLibTest\Mocks\Hash\Hash as MockHash;
use CryptLib\Key\Derivation\PBKDF\PBKDF2;

class Unit_Key_Derivation_PBKDF_PBKDF2Test extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 'salt', 1, 20, 'sha1', pack('H*', '0c60c80f961f0e71f3a9b524af6012062fe037a6')),
            array('password', 'salt', 1, 20, 'sha256', pack('H*', '120fb6cffcf8b32c43e7225256c4f837a86548c9')),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\PBKDF2
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new PBKDF2();
        $this->assertTrue($pb instanceof PBKDF2);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\PBKDF2::derive
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $s, $c, $len, $hash, $expect) {
        $pb = new PBKDF2(array('hash'=>$hash));
        $actual = $pb->derive($p, $s, $c, $len);
        $this->assertEquals($expect, $actual);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\PBKDF2::getSignature
     */
    public function testGetSignature() {
        $hash = new MockHash(array('getName' => function() { return 'test'; }));
        $pb = new PBKDF2(array('hash' => $hash));
        $this->assertEquals('pbkdf2-test', $pb->getSignature());
    }
}
