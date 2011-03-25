<?php

use CryptLib\Key\Derivation\PBKDF\PBKDF2;

class Unit_Key_Derivation_PBKDF_PBKDF2Test extends PHPUnit_Framework_TestCase {

    public static function provideTestDerive() {
        return array(
            // RFC 6070 SHA1 Test Vectors
            array('password', 'salt', 1, 20, 'sha1', pack('H*', '0c60c80f961f0e71f3a9b524af6012062fe037a6')),
            array('password', 'salt', 2, 20, 'sha1', pack('H*', 'ea6c014dc72d6f8ccd1ed92ace1d41f0d8de8957')),
            array('password', 'salt', 4096, 20, 'sha1', pack('H*', '4b007901b765489abead49d926f721d065a429c1')),
//            array('password', 'salt', 16777216, 20, 'sha1', pack('H*', 'eefe3d61cd4da4e4e9945b3d6ba2158c2634e984')),
            array(
                'passwordPASSWORDpassword',
                'saltSALTsaltSALTsaltSALTsaltSALTsalt',
                4096,
                25,
                'sha1',
                pack('H*', '3d2eec4fe41c849b80c8d83662c0e44a8b291a964cf2f07038')
            ),
            array("pass\0word", "sa\0lt", 4096, 16, 'sha1', pack('H*', '56fa6aa75548099dcc37d7f03425e0c3')),
            array('password', 'salt', 1, 20, 'sha256', pack('H*', '120fb6cffcf8b32c43e7225256c4f837a86548c9')),
            array('password', 'salt', 2, 20, 'sha256', pack('H*', 'ae4d0c95af6b46d32d0adff928f06dd02a303f8e')),
            array('password', 'salt', 4096, 20, 'sha256', pack('H*', 'c5e478d59288c841aa530db6845c4c8d962893a0')),
            array('password', 'salt', 1, 32, 'sha256', pack('H*', '120fb6cffcf8b32c43e7225256c4f837a86548c92ccc35480805987cb70be17b')),
            array('password', 'salt', 2, 32, 'sha256', pack('H*', 'ae4d0c95af6b46d32d0adff928f06dd02a303f8ef3c251dfd6e2d85a95474c43')),
            array('password', 'salt', 4096, 32, 'sha256', pack('H*', 'c5e478d59288c841aa530db6845c4c8d962893a001ce4e11a4963873aa98134a')),
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
     */
    public function testDerive($p, $s, $c, $len, $hash, $expect) {
        $pb = new PBKDF2(array('hash'=>$hash));
        $actual = $pb->derive($p, $s, $c, $len);
        $this->assertEquals($expect, $actual);
    }

}
