<?php

use CryptLib\Password\Implementation\PHPASS;

class Vectors_Password_Implementation_PHPASSTest extends PHPUnit_Framework_TestCase {

    public static function provideTestVerify() {
        $results = array();
        $file = \CryptLibTest\getTestDataFile('Vectors/phpass.custom.test-vectors');
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
     * @covers CryptLib\Password\Implementation\PHPASS::verify
     * @dataProvider provideTestVerify
     * @group Vectors
     */
    public function testVerify($pass, $expect, $value) {
        $apr = new PHPASS();
        $this->assertEquals($value, $apr->verify($pass, $expect));
    }

}
