<?php

use CryptLib\Core\Strength\VeryLow;

class Unit_Core_Strength_VeryLowTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers CryptLib\Core\Strength\VeryLow
     */
    public function testConstruct() {
        $strength = new VeryLow;
        $this->assertTrue($strength instanceof CryptLib\Core\Strength);
    }
}
