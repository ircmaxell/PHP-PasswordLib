<?php

class Vectors_Cipher_Block_Cipher_DESTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $file = \CryptLibTest\getTestDataFile('Vectors/des.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\SSV($file);
        return $nessie->getVectors();
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
     * @dataProvider provideTestEncryptVectors
     */
    public function testDecrypt($key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\DES('des');
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->decryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

}
