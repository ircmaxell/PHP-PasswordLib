<?php

use CryptLib\Core\Strength\Medium;

class Unit_Core_Strength_MediumTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers CryptLib\Core\Strength\Medium
     */
    public function testConstruct() {
        $strength = new Medium;
        $this->assertTrue($strength instanceof CryptLib\Core\Strength);
    }
}
