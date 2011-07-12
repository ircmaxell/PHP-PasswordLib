<?php

class Vectors_Cipher_Block_Cipher_TripleDESTest extends PHPUnit_Framework_TestCase {

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
        $file = \CryptLibTest\getTestDataFile('Vectors/triple-des-2-key-128-64.unverified.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
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
     * @group Vectors
     */
    public function testEncrypt($key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\TripleDES('tripledes');
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->encryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

    /**
     * @dataProvider provideTestEncryptVectors
     * @group Vectors
     */
    public function testDecrypt($key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\TripleDES('tripledes');
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->decryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

}
