<?php

class Vectors_Cipher_Block_Mode_CTRTest extends PHPUnit_Framework_TestCase {


    public static function provideTestEncryptVectors() {
        $file = \CryptLibTest\getTestDataFile('Vectors/aes-ctr.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
        $data = $nessie->getVectors();
        $ciphers = array();
        foreach ($data as $vector) {
            if (!isset($ciphers[$vector['mode']])) {
                $ciphers[$vector['mode']] = array(array());
            }
            $ciphers[$vector['mode']][0][] = array(
                $vector['mode'],
                $vector['key'],
                $vector['iv'],
                strtoupper($vector['plain']),
                strtoupper($vector['cipher']),
            );
        }
        return $ciphers;
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncrypt(array $vectors) {
        list ($cipher, $key, $iv) = $vectors[0];
       
        $cipher = new \CryptLib\Cipher\Block\Cipher\AES($cipher);
        $cipher->setKey(pack('H*', $key));
        $mode = new \CryptLib\Cipher\Block\Mode\CTR($cipher, pack('H*', $iv), '');
        foreach ($vectors as $vector) {
            list (,,, $data, $expected) = $vector;
            $enc = $mode->encrypt(pack('H*', $data));
            $this->assertEquals($expected, strtoupper(bin2hex($enc)));
        }
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testDecrypt(array $vectors) {
        list ($cipher, $key, $iv) = $vectors[0];
        $cipher = new \CryptLib\Cipher\Block\Cipher\AES($cipher);
        $cipher->setKey(pack('H*', $key));
        $mode = new \CryptLib\Cipher\Block\Mode\CTR($cipher, pack('H*', $iv), '');
        foreach ($vectors as $vector) {
            list (,,, $data, $expected) = $vector;
            $dec = $mode->decrypt(pack('H*', $data));
            $dec .= $mode->finish();
            $this->assertEquals($expected, strtoupper(bin2hex($dec)));
        }
    }
    

}
