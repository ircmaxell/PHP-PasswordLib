<?php

use CryptLib\Password\Implementation\APR1;

class Vectors_Password_Implementation_APR1Test extends PHPUnit_Framework_TestCase {

    public static function provideTestVerify() {
        $file = \CryptLibTest\getTestDataFile('Vectors/apr1.test-vectors');
        $nessie = new CryptLibTest\lib\VectorParser\NESSIE($file);
        $results = array();
        foreach ($nessie->getVectors() as $vector) {
            $results[] = array(
                $vector['P'],
                $vector['H'],
                (boolean) $vector['Value'],
            );
        }
        $file = \CryptLibTest\getTestDataFile('Vectors/apr1.custom.test-vectors');
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
     * @covers CryptLib\Password\Implementation\APR1::verify
     * @covers CryptLib\Password\Implementation\APR1::to64
     * @covers CryptLib\Password\Implementation\APR1::hash
     * @covers CryptLib\Password\Implementation\APR1::iterate
     * @covers CryptLib\Password\Implementation\APR1::convertToHash
     * @dataProvider provideTestVerify
     * @group Vectors
     */
    public function testVerify($pass, $expect, $value) {
        $apr = new APR1();
        $this->assertEquals($value, $apr->verify($expect, $pass));
    }

}
