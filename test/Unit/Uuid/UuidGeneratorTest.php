<?php

use PasswordLib\Uuid\UuidGenerator;

/**
 * Tests the functionality of the UuidGenerator class.
 *
 * @package
 * @author ChASM <info@chasm.gr>
 */
class Unit_Uuid_UuidGeneratorTest extends PHPUnit_Framework_TestCase {

    /**
     * The string denoting the regular expression pattern for a character in
     * hexadecimal notation.
     *
     * @var string
     */
    private static $hexCharPattern = '[0-9a-f]';

    /**
     * The string denoting the regular expression pattern of the UUID variant.
     *
     * @var string
     */
    private static $variantPattern = '[89ab]';

    /**
     * The string denoting the regular expression pattern of the UUID version.
     *
     * @var string
     */
    private static $versionPattern = '4';

    /**
     * @var UuidGenerator
     */
    private $uuidGenerator;

    /**
     * Tests if the *generateVersion4Uuid* method produces any duplicates.
     *
     * It goes without saying that there should be zero tolerance for duplicates.
     *
     * The testing algorithm goes as follows:
     *
     *  <ol>
     *   <li>Create an empty list where the new UUIDs will be stored. Let's call
     *       it *list*.</li>
     *   <li>Repeat 10,000 times:
     *    <ol>
     *     <li>Create a new UUID. Let's call it *uuid*.</li>
     *     <li>Check if *uuid* is in *list*.
     *      <ol>
     *       <li>If *yes*, then declare failure.</li>
     *       <li>If *no*, then add *uuid* to *list*.</li>
     *      </ol>
     *     </li>
     *    </ol>
     *   </li>
     *  </ol>
     */
    function testGenerateVersion4UuidForUniqueness() {
        $uuidArr = array();
        for ($i = 1; $i <= 10000; $i++) {
            $uuid = $this->uuidGenerator->generateVersion4UUID();
            if ( in_array($uuid, $uuidArr) ) {
                $this->fail("Duplicate UUID: {$uuid} (iteration: {$i})");
            }

            $uuidArr[] = $uuid;
        }
    }

    /**
     * Tests the "generateVersion4Uuid" method for valid [version 4 UUIDs][1].
     *
     *
     * [1]: http://tinyurl.com/3znd239 "Version 4 UUID"
     */
    function testGenerateVersion4UuidForValidity() {
        $uuid = $this->uuidGenerator->generateVersion4UUID();
        $pattern = "/^" . static::$hexCharPattern . "{8}-"
            . static::$hexCharPattern . "{4}-"
            . static::$versionPattern . static::$hexCharPattern . "{3}-"
            . static::$variantPattern . static::$hexCharPattern . "{3}-"
            . static::$hexCharPattern . "{12}$/";
        $valid = (boolean) preg_match($pattern, $uuid);
        $this->assertTrue($valid);
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        $this->uuidGenerator = UuidGenerator::getInstance();
    }
}
