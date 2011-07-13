<?php

use CryptLib\Password\Implementation\PHPBB;

class Vectors_Password_Implementation_PHPBBTest extends PHPUnit_Framework_TestCase {

    public static function provideTestVerify() {
        $results = array();
        $file = \CryptLibTest\getTestDataFile('Vectors/phpbb.custom.test-vectors');
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
     * @covers CryptLib\Password\Implementation\PHPBB::verify
     * @dataProvider provideTestVerify
     * @group Vectors
     */
    public function testVerify($pass, $expect, $value) {
        $apr = new PHPBB();
        $this->assertEquals($value, $apr->verify($pass, $expect));
    }

}
