<?php

class Unit_Cipher_Block_Implementation_AESTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $ret = array(
            array(
                'aes-128',
                '000102030405060708090A0B0C0D0E0F', 
                '00112233445566778899AABBCCDDEEFF', 
                '69C4E0D86A7B0430D8CDB78070B4C55A'
            ),
            array(
                'aes-192',
                '000102030405060708090A0B0C0D0E0F1011121314151617',
                '00112233445566778899AABBCCDDEEFF',
                'DDA97CA4864CDFE06EAF70A0EC0D7191',
            ),
            array(
                'aes-256',
                '000102030405060708090A0B0C0D0E0F101112131415161718191A1B1C1D1E1F',
                '00112233445566778899AABBCCDDEEFF',
                '8EA2B7CA516745BFEAFC49904B496089',
            ),
        );
        return $ret;
    }

    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncrypt($cipher, $key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\AES($cipher);
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->encryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testDecrypt($cipher, $key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\AES($cipher);
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->decryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncryptThenDecrypt($cipher, $key, $data) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\AES($cipher);
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->encryptBlock(pack('H*', $data));
        $dec = $cipher->decryptBlock($enc);
        $this->assertEquals($data, strtoupper(bin2hex($dec)));
    }
    
    public function testBlockSize() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\AES('aes-128');
        $this->assertEquals(16, $cipher->getBlockSize());
        $cipher = new \CryptLib\Cipher\Block\Implementation\AES('aes-256');
        $this->assertEquals(16, $cipher->getBlockSize());
    }
    
    public function testGetCipher() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\AES('aes-128');
        $this->assertEquals('aes-128', $cipher->getCipher());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailure() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\AES('rijndael-128');
    }

}
