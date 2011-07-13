<?php

class Vectors_MAC_HMACTest extends PHPUnit_Framework_TestCase {


    public static function provideTestGenerateVectors() {
        $file = \CryptLibTest\getTestDataFile('Vectors/hmac.rfc4231.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
        $data = $nessie->getVectors();
        $results = array();
        foreach ($data as $vector) {
            $results[] = array(
                'sha224',
                $vector['Key'],
                $vector['Data'],
                $vector['Len-SHA224'],
                $vector['SHA224']
            );
            $results[] = array(
                'sha256',
                $vector['Key'],
                $vector['Data'],
                $vector['Len-SHA256'],
                $vector['SHA256']
            );
            $results[] = array(
                'sha384',
                $vector['Key'],
                $vector['Data'],
                $vector['Len-SHA384'],
                $vector['SHA384']
            );
            $results[] = array(
                'sha512',
                $vector['Key'],
                $vector['Data'],
                $vector['Len-SHA512'],
                $vector['SHA512']
            );
        }
        return $results;
    }

    /**
     * @dataProvider provideTestGenerateVectors
     * @group Vectors
     */
    public function testGenerate($hash, $key, $data, $size, $expect) {
        $cmac = new \CryptLib\MAC\Implementation\HMAC(array('hash' => $hash));
        $result = $cmac->generate(pack('H*', $data), pack('H*', $key), $size);
        $this->assertEquals($expect, bin2hex($result));
    }

}
