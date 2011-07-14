<?php

use CryptLib\Key\Derivation\PBKDF\SHA512;

class Unit_Key_Derivation_PBKDF_SHA512Test extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            array('password', 'saltwithmoredatahere', 1, 20, '$6$rounds=1000$saltwithmoredata$hU9SPJCcaUmMVr1e97OkMHgHFJlny1TRqMuXFMqpCpLD3FQ7jzqSEvXEwCTNwWGbpN7G9qihiGXxlTgf91atl1'),
            array('password', 'somelongsaltstringherewithmore', 1, 20, '$6$rounds=1000$somelongsaltstri$jTo8w.r7me6vHE1ATu2svMaJcEzeQd63g9/exo9tQ2ACeudgrkSqF/WYqNkBM98uQcsL6mJI6DrDuyPuw/Fdv/'),
        );
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\SHA512
     * @covers CryptLib\Key\Derivation\AbstractDerivation
     */
    public function testConstruct() {
        $pb = new SHA512();
        $this->assertTrue($pb instanceof SHA512);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\SHA512
     * @dataProvider provideTestDerive
     * @group slow
     */
    public function testDerive($p, $s, $c, $len, $expect) {
        $pb = new SHA512();
        $actual = $pb->derive($p, $s, $c, $len);
        $this->assertEquals($expect, $actual);
    }

    /**
     * @covers CryptLib\Key\Derivation\PBKDF\SHA512
     */
    public function testGetSignature() {
        $pb = new SHA512();
        $this->assertEquals('sha512', $pb->getSignature());
    }
}
