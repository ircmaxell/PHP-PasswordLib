<?php

use CryptLib\Key\Derivation\KDF\KDF1;

class Unit_Key_Derivation_KDF_KDF1Test extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 20, '', '3f5d118200e8061deb178398380118bef891f395'),
            array('password', 20, 'something', '46f4a5e6828a031d5db234045f836fd8a6f3ba17'),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\KDF\KDF1
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new KDF1();
        $this->assertTrue($pb instanceof KDF1);
    }

    /**
     * @covers CryptLib\Key\Derivation\KDF\KDF1
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $len, $data, $expect) {
        $pb = new KDF1();
        $actual = $pb->derive($p, $len, $data);
        $actual = bin2hex($actual);
        $this->assertEquals($expect, $actual);
    }

}
