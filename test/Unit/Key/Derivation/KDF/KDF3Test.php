<?php

use CryptLib\Key\Derivation\KDF\KDF3;

class Unit_Key_Derivation_KDF_KDF3Test extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 20, '', '20c74d01389f8cbfba727e951e62fdbd206019c1'),
            array('password', 20, 'something', 'ad952bdfebba1bb06299fca88495e1d404e0c780'),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\KDF\KDF3
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new KDF3();
        $this->assertTrue($pb instanceof KDF3);
    }

    /**
     * @covers CryptLib\Key\Derivation\KDF\KDF3
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $len, $data, $expect) {
        $pb = new KDF3();
        $actual = $pb->derive($p, $len, $data);
        $actual = bin2hex($actual);
        $this->assertEquals($expect, $actual);
    }

}
