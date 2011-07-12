<?php

class Vectors_Cipher_Block_Mode_CCMTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $results = array();
        $file = \CryptLibTest\getTestDataFile('Vectors/ccm-RFC3610.test-vectors');
        $parser = new CryptLibTest\lib\VectorParser\RFC3610($file);
        $vectors = $parser->getVectors();
        foreach ($vectors as $vector) {
            $results[] = array(
                'aes-128',
                $vector['AES Key'],
                $vector['Nonce'],
                $vector['Data'],
                $vector['Adata'],
                $vector['Cipher'],
                2,
                $vector['Asize'],
            );
        }
        return $results;
    }

    /**
     * @dataProvider provideTestEncryptVectors
     * @group Vectors
     */
    public function testEncrypt($cipher, $key, $initv, $data, $adata, $expected, $lSize, $tSize) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\AES($cipher);
        $cipher->setKey(pack('H*', $key));
        $mode = new \CryptLib\Cipher\Block\Mode\CCM($cipher, pack('H*', $initv), pack('H*', $adata));
        $mode->setAuthFieldSize($tSize);
        $mode->setLSize($lSize);
        $mode->encrypt(pack('H*', $data));
        $enc = $mode->finish();
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

}
