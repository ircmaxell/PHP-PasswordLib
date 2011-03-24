<?php

use CryptLib\Core\Strength\Low;

class Unit_Core_Strength_LowTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers CryptLib\Core\Strength\Low
     */
    public function testConstruct() {
        $strength = new Low;
        $this->assertTrue($strength instanceof CryptLib\Core\Strength);
    }
}
