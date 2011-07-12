<?php

class Vectors_MAC_CMACTest extends PHPUnit_Framework_TestCase {


    public static function provideTestGenerateVectors() {
        $file = \CryptLibTest\getTestDataFile('Vectors/cmac-aes.sp-800-38b.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
        $data = $nessie->getVectors();
        foreach ($data as $vector) {
            $results[] = array(
                $vector['mode'],
                $vector['key'],
                $vector['plain'],
                $vector['tlen'],
                strtolower($vector['mac']),
            );
        }
        return $results;
    }

    /**
     * @dataProvider provideTestGenerateVectors
     * @group Vectors
     */
    public function testGenerate($cipher, $key, $data, $size, $expect) {
        $cmac = new \CryptLib\MAC\Implementation\CMAC(array('cipher' => $cipher));
        $result = $cmac->generate(pack('H*', $data), pack('H*', $key), $size);
        $this->assertEquals($expect, bin2hex($result));
    }

}
