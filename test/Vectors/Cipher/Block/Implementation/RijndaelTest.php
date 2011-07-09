<?php

class Vectors_Cipher_Block_Implementation_RijndaelTest extends PHPUnit_Framework_TestCase {


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
     */
    public function testEncrypt($cipher, $key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\Rijndael($cipher);
        $enc = $cipher->encryptBlock(pack('H*', $data), pack('H*', $key));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testDecrypt($cipher, $key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\Rijndael($cipher);
        $enc = $cipher->decryptBlock(pack('H*', $data), pack('H*', $key));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    

}
