<?php

class Vectors_Cipher_Block_Implementation_TripleDESTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $file = \CryptLibTest\getTestDataFile('Vectors/triple-des-3-key-192-64.unverified.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
        $results = array();
        foreach ($nessie->getVectors() as $vector) {
            $results[] = array(
                $vector['key'],
                $vector['plain'],
                $vector['cipher'],
            );
        }
        return $results;
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncrypt($key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\TripleDES('tripledes');
        $enc = $cipher->encryptBlock(pack('H*', $data), pack('H*', $key));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testDecrypt($key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\TripleDES('tripledes');
        $enc = $cipher->decryptBlock(pack('H*', $data), pack('H*', $key));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

}
