<?php

use CryptLib\Key\Derivation\KDF\KDF2;

class Unit_Key_Derivation_KDF_KDF2Test extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 20, '', 'b4de807b408717196ca96c0c114e0f881f0b6ef8'),
            array('password', 20, 'something', '96a482a79360192704f20716016c8850890db856'),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\KDF\KDF2
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new KDF2();
        $this->assertTrue($pb instanceof KDF2);
    }

    /**
     * @covers CryptLib\Key\Derivation\KDF\KDF2
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $len, $data, $expect) {
        $pb = new KDF2();
        $actual = $pb->derive($p, $len, $data);
        $actual = bin2hex($actual);
        $this->assertEquals($expect, $actual);
    }

}
