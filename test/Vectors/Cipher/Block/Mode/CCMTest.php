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

    public static function provideTestDecryptVectors() {
        $results = array();
        $file = \CryptLibTest\getTestDataFile('Vectors/ccm-cavs11-dvpt.decryption.test-vectors');
        $parser = new CryptLibTest\lib\VectorParser\CAVS($file);
        $vectors = $parser->getVectors();
        foreach ($vectors as $vector) {
            $results[] = array(
                'aes-128',
                $vector['Key'],
                $vector['Nonce'],
                isset($vector['Payload']) ? $vector['Payload'] : '',
                $vector['Adata'],
                $vector['CT'],
                $vector['Result'],
                $vector['Alen'],
                $vector['Plen'],
                $vector['Nlen'],
                $vector['Tlen'],
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
        $options = array(
            'adata' => pack('H*', $adata),
            'lSize' => $lSize,
            'aSize' => $tSize,
        );
        $mode = new \CryptLib\Cipher\Block\Mode\CCM($cipher, pack('H*', $initv), $options);
        $mode->encrypt(pack('H*', $data));
        $enc = $mode->finish();
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }

    /**
     * @dataProvider provideTestDecryptVectors
     * @group Vectors
     */
    public function testDecrypt($cipher, $key, $initv, $data, $adata, $expected, $result, $asize, $psize, $nsize, $tsize) {
        $cipher = new \CryptLib\Cipher\Block\Cipher\AES($cipher);
        $cipher->setKey(pack('H*', $key));
        if ($asize == 0) {
            $adata = '';
        }
        if ($psize == 0) {
            $data = 0;
        }
        $options = array(
            'adata' => pack('H*', $adata),
            'lSize' => 15 - $nsize,
            'aSize' => $tsize,
        );
        $mode = new \CryptLib\Cipher\Block\Mode\CCM($cipher, pack('H*', $initv), $options);
        $mode->decrypt(pack('H*', $expected));
        $enc = $mode->finish();
        if ($enc === false) {
            $this->assertEquals('Fail', $result);
        } else {
            $this->assertEquals('Pass', $result);
        }
    }

}
