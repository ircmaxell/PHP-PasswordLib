<?php

class Unit_Cipher_Block_Cipher_DESTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $ret = array(
            array('0101010101010101', '95F8A5E5DD31D900', '8000000000000000'),
        );
        return $ret;
    }
    
    /**
     * @return array The test vectors
     */
    public static function provideTestDecryptVectors() {
        $ret = array(
            array('8001010101010101', '95A8D72813DAA94D', '0000000000000000'),
        );
        return $ret;
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncrypt($key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->encryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    /**
     * @dataProvider provideTestDecryptVectors
     */
    public function testDecrypt($key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->decryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    public function testBlockSize() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $this->assertEquals(8, $cipher->getBlockSize());
    }
    
    public function testGetCipher() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $this->assertEquals('des', $cipher->getCipher());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailure() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\DES('something');
    }

}
