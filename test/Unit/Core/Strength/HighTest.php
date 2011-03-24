<?php

use CryptLib\Core\Strength\High;

class Unit_Core_Strength_HighTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers CryptLib\Core\Strength\High
     */
    public function testConstruct() {
        $strength = new High;
        $this->assertTrue($strength instanceof CryptLib\Core\Strength);
    }
}
