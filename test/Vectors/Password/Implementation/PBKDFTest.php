<?php

use CryptLib\Password\Implementation\PBKDF;

class Vectors_Password_Implementation_PBKDFTest extends PHPUnit_Framework_TestCase {

    public static function provideTestVerify() {
        $results = array();
        $file = \CryptLibTest\getTestDataFile('Vectors/pbkdf.custom.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\SSV($file);
        foreach ($nessie->getVectors() as $vector) {
            $results[] = array(
                $vector[0],
                $vector[1],
                true
            );
        }
        return $results;
    }

    /**
     * @covers CryptLib\Password\Implementation\PBKDF::verify
     * @dataProvider provideTestVerify
     */
    public function testVerify($pass, $expect, $value) {
        $apr = new PBKDF();
        $this->assertEquals($value, $apr->verify($expect, $pass));
    }

}
