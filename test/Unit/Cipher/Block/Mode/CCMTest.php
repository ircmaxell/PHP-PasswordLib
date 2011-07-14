<?php

use CryptLib\Core\BitString;

class Unit_Cipher_Block_Mode_CCMTest extends PHPUnit_Framework_TestCase {

    public static function provideTestEncryptVectors() {
        $ret = array(
            1 => array(
                '08 09 0A 0B 0C 0D 0E 0F 10 11 12 13 14 15 16 17
                 18 19 1A 1B 1C 1D 1E',
                'C0 C1 C2 C3 C4 C5 C6 C7 C8 C9 CA CB CC CD CE CF
                 C0 C1 C2 C3 C4 C5 C6 C7 C8 C9 CA CB CC CD CE CF',
                '00 00 00 03 02 01 00 A0 A1 A2 A3 A4 A5',
                '00 01 02 03 04 05 06 07',
                '216163DECF74E00CAB0456FF45CDA7171FA596D70F7691CA8AFAA23F223E64',
                2,
                8
            ),
            101 => array(
                'AB F2 1C 0B 02 FE B8 8F 85 6D F4 A3 73 81 BC E3
                 CC 12 85 17 D4',
                'D7 82 8D 13 B2 B0 BD C3 25 A7 62 36 DF 93 CC 6B
                 D7 82 8D 13 B2 B0 BD C3 25 A7 62 36 DF 93 CC 6B',
                '00 8D 49 3B 30 AE 8B 3C 96 96 76 6C FA',
                str_repeat('04', 1<<16),
                '23687A56540951B0134F65E9CDE6AC57225928E345596E70AFC2D0EAAA3D01',
                2,
                10
            ),
            102 => array(
                'AB F2 1C 0B 02 FE B8 8F 85 6D F4 A3 73 81 BC E3
                 CC 12 85 17 D4',
                'D7 82 8D 13 B2 B0 BD C3 25 A7 62 36 DF 93 CC 6B
                 D7 82 8D 13 B2 B0 BD C3 25 A7 62 36 DF 93 CC 6B',
                '00 8D 49 3B 30 AE 8B 3C 96 96 76 6C  FA',
                '',
                '23687A56540951B0134F65E9CDE6AC57225928E34591E43925EFD9A8A7EA5B',
                2,
                10
            ),
        );
        foreach ($ret as &$case) {
            foreach ($case as &$row) {
                if (empty($row) || is_int($row)) continue;
                $row = preg_replace('/\s/', '', $row);
            }
        }
        return $ret;
    }

    /**
     * @dataProvider provideTestEncryptVectors
     * @group slow
     */
    public function testEncrypt($data, $key, $initv, $adata, $expected, $lSize, $aSize) {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $aes->setKey(pack('H*', $key));
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, pack('H*', $initv), array('adata' => pack('H*', $adata)));
        $mode->setLSize($lSize);
        $mode->setAuthFieldSize($aSize);
        $mode->encrypt(pack('H*', $data));
        $actual = $mode->finish();
        $this->assertEquals($expected, strtoupper(bin2hex($actual)));
    }

    public function testGetMode() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, 'foobarbazbizbuz');
        $this->assertEquals('ccm', $mode->getMode());
    }

    public function testDecrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $iv = 'FEDCBA9876543210';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));

        $key = '0123456789ABCDEFGHIJKLMNOPQRSTUV';
        $aes->setKey($key);
        $data = 'Foo Bar Baz Biz ';

        $mode->encrypt($data);
        $enc = $mode->finish();
        $mode->reset();
        $mode->decrypt($enc);
        $dec = $mode->finish();
        $this->assertEquals($data, $dec);
    }

    /**
     * @expectedException LogicException
     */
    public function testDecryptAndDecryptTogetherFailure() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $iv = 'FEDCBA9876543210';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));

        $key = '0123456789ABCDEFGHIJKLMNOPQRSTUV';
        $aes->setKey($key);
        $data = 'Foo Bar Baz Biz ';

        $mode->encrypt($data);
        $mode->decrypt($data);
        $enc = $mode->finish();
    }

    public function testDecryptFailure() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $key = '0123456789ABCDEFGHIJKLMNOPQRSTUV';
        $aes->setKey($key);
        $iv = 'FEDCBA9876543210';
        $data = 'Foo Bar Baz Biz ';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));
        $mode->encrypt($data);
        $enc = $mode->finish();
        $mode->reset();
        $enc = chr(255) . substr($enc, 1);
        $mode->decrypt($enc);
        $dec = $mode->finish();
        $this->assertFalse($dec);
    }

    public function testDecryptAuthFailure() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $key = '0123456789ABCDEFGHIJKLMNOPQRSTUV';
        $aes->setKey($key);
        $iv = 'FEDCBA9876543210';
        $data = 'Foo Bar Baz Biz ';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));
        $mode->encrypt($data);
        $enc = $mode->finish();
        $mode->reset();
        $enc = substr($enc, 0, -1) . chr(255);
        $mode->decrypt($enc);
        $this->assertFalse($mode->finish());
    }

    public function testSetAuthField() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $iv = 'FEDERATEDBLOCKDATATOOLONG';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));
        $mode->setAuthFieldSize(14);
        $ref = new ReflectionProperty('CryptLib\Cipher\Block\Mode\CCM', 'authFieldSize');
        $ref->setAccessible(true);
        $this->assertEquals(14, $ref->getValue($mode));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetAuthFieldFailure() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $iv = 'FEDERATEDBLOCKDATATOOLONG';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));
        $mode->setAuthFieldSize(13);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIVTooSmallFailure() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $iv = 'FED';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));
    }

    public function testSetLSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $iv = 'FEDERATEDBLOCKDATATOOLONG';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));
        $mode->setLSize(5);
        $ref = new ReflectionProperty('CryptLib\Cipher\Block\Mode\CCM', 'lSize');
        $ref->setAccessible(true);
        $this->assertEquals(5, $ref->getValue($mode));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetLSizeFailure() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $iv = 'FEDERATEDBLOCKDATATOOLONG';
        $adata = 'Some Other Text';
        $mode = new CryptLib\Cipher\Block\Mode\CCM($aes, $iv, array('adata' => $adata));
        $mode->setLSize(13);
    }

}
