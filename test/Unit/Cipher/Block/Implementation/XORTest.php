<?php

class Unit_Cipher_Block_Implementation_X_ORTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $ret = array(
            array(
                'xor',
                '00000000000000000000000000000000', 
                '00000000000000000000000000000000', 
                '00000000000000000000000000000000'
            ),
            array(
                'xor',
                '00000000000000000000000000000000',
                'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF',
                'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF',
            ),

        );
        return $ret;
    }

    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncrypt($cipher, $key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\X_OR($cipher);
        $enc = $cipher->encryptBlock(pack('H*', $data), pack('H*', $key));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testDecrypt($cipher, $key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\X_OR($cipher);
        $enc = $cipher->decryptBlock(pack('H*', $data), pack('H*', $key));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncryptThenDecrypt($cipher, $key, $data) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\X_OR($cipher);
        $enc = $cipher->encryptBlock(pack('H*', $data), pack('H*', $key));
        $dec = $cipher->decryptBlock($enc, pack('H*', $key));
        $this->assertEquals($data, strtoupper(bin2hex($dec)));
    }
    
    public function testBlockSize() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\X_OR('xor');
        $this->assertEquals(3, $cipher->getBlockSize('foo'));
    }
    
    public function testGetCipher() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\X_OR('xor');
        $this->assertEquals('xor', $cipher->getCipher());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailure() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\X_OR('rijndael-128');
    }

}
