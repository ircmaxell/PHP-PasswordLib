<?php

use CryptLib\Key\Derivation\PBKDF\SHA256;

class Unit_Key_Derivation_PBKDF_SHA256Test extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 'salt', 1, 20, '$5$rounds=1000$salt$p.wiWs2zrZ7irikO2AL64QDIJo00A3KDq2xWHpLJGgB'),
            array('password', 'somelongsaltstringherewithmore', 1, 20, '$5$rounds=1000$somelongsaltstri$V4NC27JbKMYAhaGPwcNEaKThDhlymIbVUPUKM14GNN6'),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\SHA256
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new SHA256();
        $this->assertTrue($pb instanceof SHA256);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\SHA256
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $s, $c, $len, $expect) {
        $pb = new SHA256();
        $actual = $pb->derive($p, $s, $c, $len);
        $this->assertEquals($expect, $actual);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\SHA256
     */
    public function testGetSignature() {
        $pb = new SHA256();
        $this->assertEquals('sha256', $pb->getSignature());
    }
}
