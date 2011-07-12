<?php

class Unit_Cipher_Block_Cipher_RijndaelTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $ret = array(
            array(
                'rijndael-128',
                '8000000000000000000000000000000000000000000000000000000000000000',
                '00000000000000000000000000000000',
                'E35A6DCB19B201A01EBCFA8AA22B5759'
            ),
            array(
                'rijndael-192',
                '8000000000000000000000000000000000000000000000000000000000000000',
                '000000000000000000000000000000000000000000000000',
                '06EB844DEC23F29F029BE85FDCE578CEC5C663CE0C70403C',
            ),
            array(
                'rijndael-256',
                '8000000000000000000000000000000000000000000000000000000000000000',
                '0000000000000000000000000000000000000000000000000000000000000000',
                'E62ABCE069837B65309BE4EDA2C0E149FE56C07B7082D3287F592C4A4927A277',
            ),
            array(
                'rijndael-224',
                '2B7E151628AED2A6ABF7158809CF4F3C',
                '3243F6A8885A308D313198A2E03707344A4093822299F31D0082EFA9',
                'B0A8F78F6B3C66213F792FFD2A61631F79331407A5E5C8D3793ACEB1',
            ),
        );
        return $ret;
    }

    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncrypt($cipher, $key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael($cipher);
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->encryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testDecrypt($cipher, $key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael($cipher);
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->decryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncryptThenDecrypt($cipher, $key, $data) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael($cipher);
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->encryptBlock(pack('H*', $data));
        $dec = $cipher->decryptBlock($enc, pack('H*', $key));
        $this->assertEquals($data, strtoupper(bin2hex($dec)));
    }

    public function testBlockSize() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael('rijndael-128');
        $this->assertEquals(16, $cipher->getBlockSize());
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael('rijndael-256');
        $this->assertEquals(32, $cipher->getBlockSize());
    }

    public function testGetCipher() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael('rijndael-128');
        $this->assertEquals('rijndael-128', $cipher->getCipher());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetKeyInvalidSize() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael('rijndael-128');
        $cipher->setKey(str_repeat(chr(0), 66));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailure() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael('aes-128');
    }

}
