<?php

class Vectors_Cipher_Block_Cipher_RijndaelTest extends PHPUnit_Framework_TestCase {


    public static function provideTestEncryptVectors() {
        $file = \CryptLibTest\getTestDataFile('Vectors/rijndael-256-192.unverified.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
        $data = array('rijndael-192' => $nessie->getVectors());
        $file = \CryptLibTest\getTestDataFile('Vectors/rijndael-256-128.unverified.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
        $data['rijndael-128'] = $nessie->getVectors();
        $file = \CryptLibTest\getTestDataFile('Vectors/rijndael-256-256.unverified.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
        $data['rijndael-256'] = $nessie->getVectors();
        $results = array();
        foreach ($data as $cipher => $vectors) {
            foreach ($vectors as $vector) {
                $results[] = array(
                    $cipher,
                    $vector['key'],
                    $vector['plain'],
                    $vector['cipher'],
                );
            }
        }
        return $results;
    }

    /**
     * @dataProvider provideTestEncryptVectors
     * @group Vectors
     */
    public function testEncrypt($cipher, $key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael($cipher);
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->encryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

    /**
     * @dataProvider provideTestEncryptVectors
     * @group Vectors
     */
    public function testDecrypt($cipher, $key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\Rijndael($cipher);
        $cipher->setKey(pack('H*', $key));
        $enc = $cipher->decryptBlock(pack('H*', $data));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }


}
