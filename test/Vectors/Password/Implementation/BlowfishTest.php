<?php

use CryptLib\Password\Implementation\Blowfish;

class Vectors_Password_Implementation_BlowfishTest extends PHPUnit_Framework_TestCase {

    public static function provideTestVerify() {
        $results = array();
        $file = \CryptLibTest\getTestDataFile('Vectors/blowfish.custom.test-vectors');
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
     * @covers CryptLib\Password\Implementation\Blowfish::verify
     * @dataProvider provideTestVerify
     * @group Vectors
     */
    public function testVerify($pass, $expect, $value) {
        $apr = new Blowfish();
        $this->assertEquals($value, $apr->verify($expect, $pass));
    }

}
