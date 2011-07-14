<?php

use CryptLib\Key\Derivation\PBKDF\BCrypt;

class Unit_Key_Derivation_PBKDF_BCryptTest extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 'salt', 1, 20, '$2a$04$c2FsdA//$$$$$$$$$$$$$.s/cqDDqZHdfICtEE.ehrDKzSnRzlgJ2'),
            array('password', 'somelongsaltstringherewithmore', 1, 20, '$2a$04$c29tZWxvbmdzYWx0c3RyaODWXyN.i8AWP0fApQMGEDItVQbPIti6u'),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\BCrypt
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new BCrypt();
        $this->assertTrue($pb instanceof BCrypt);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\BCrypt
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $s, $c, $len, $expect) {
        $pb = new BCrypt();
        $actual = $pb->derive($p, $s, $c, $len);
        $this->assertEquals($expect, $actual);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\BCrypt
     */
    public function testGetSignature() {
        $pb = new BCrypt();
        $this->assertEquals('bcrypt', $pb->getSignature());
    }
}
