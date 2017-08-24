<?php

namespace PasswordLib\Uuid;

use PasswordLib\Random\Factory;
use PasswordLib\Random\Generator;

/**
 * Creates [UUIDs][1] utilizing the PasswordLib library.
 *
 * The implementation suggested by this class is heavily based on [the code provided
 * by Andrew Moore][2] in the official PHP manual of the *uniqid* function. The
 * essential difference here is that PasswordLib is used for generating the random
 * bytes, as opposed to *mt_rand*.
 *
 * This class utilizes the [Singleton Pattern][3].
 *
 *
 * [1]: http://en.wikipedia.org/wiki/Universally_unique_identifier
 *      "Universally Unique Identifier | Wikipedia"
 * [2]: http://www.php.net/manual/en/function.uniqid.php#94959 *
 * [3]: http://en.wikipedia.org/wiki/Singleton_pattern
 *      "Singleton Pattern | Wikipedia"
 *
 * @package PasswordLib\Uuid
 * @author ChASM <info@chasm.gr>
 */
class UuidGenerator {

    /**
     * The integer in hexadecimal notation that denotes the version of UUIDs that
     * are comprised of random numbers.
     *
     * @var int
     */
    const UUID_VERSION_4 = 0x40;

    /**
     * A list of integers that correspond to the indices where the hyphens (-)
     * are expected to be present in a formatted UUID.
     *
     * Notice that the indices are zero-based.
     *
     * @var int[]
     */
    protected static $separatorsIdxs = array(8, 12, 16, 20);

    /**
     * The size of the UUID in bytes.
     *
     * @var int
     */
    protected static $uuidByteSize = 16;

    /**#@+
     * The integers denoting the index of the byte to be manipulated.
     *
     * A big-endian byte representation is assumed.
     *
     * @var int
     */

    /**
     * The index of the byte that indicates the variant of the UUID.
     *
     */
    protected static $variantByteIndex = 9;

    /**
     * The index of the byte that indicates the version of the UUID.
     *
     */
    protected static $versionByteIndex = 7;
    /**#@-*/

    /**
     * The singleton instance of this class.
     *
     * @var UuidGenerator
     */
    private static $instance = null;

    /**
     * The random numbers factory.
     *
     * @var Factory
     */
    private $factory;

    /**
     * The random number generator utilized by this UuidGenerator.
     *
     * @var Generator
     */
    private $rng = null;

    /**
     * Returns the singleton UuidGenerator for the current process.
     *
     * @return UuidGenerator The singleton instance of this class.
     */
    public static function getInstance() {
        if (static::$instance === null) {
            static::$instance = new static(new Factory());
        }

        return static::$instance;
    }

    /**
     * Generates a new version 4 UUID utilizing a medium strength generator of
     * the PasswordLib.
     *
     * If the {@link Generator} has yet to be instantiated, then it is constructed
     * here. Through this tactic we achieve [lazy loading][1] of the generator.
     *
     *
     * [1]: http://en.wikipedia.org/wiki/Lazy_loading
     *      "Lazy Loading | Wikipedia"
     *
     * @return string The new version 4 UUID.
     */
    public function generateVersion4Uuid() {
        if ($this->rng === null) {
            $this->rng = $this->factory->getMediumStrengthGenerator();
        }

        $randomString = $this->rng->generate(static::$uuidByteSize);
        $bytes = $this->getBytesOf($randomString);
        $this->setVersionToUuid($bytes, static::UUID_VERSION_4);
        $this->setVariantToUuid($bytes);
        $hexString = $this->getHexadecimalOf($bytes);

        return $this->formatUuid($hexString);
    }

    /**
     * Returns the factory utilized by this UuidGenerator for the creation of
     * random numbers generators.
     *
     * @return Factory The random numbers generator factory.
     */
    public function getFactory() {
        return $this->factory;
    }

    /**
     * Returns the random numbers generator utilized by this UuidGenerator.
     *
     * This is a medium strength {@link Generator}. In addition, it is instantiated
     * only once during the first call to the {@link #generateVersion4Uuid} method.
     *
     * @return Generator
     */
    public function getRandomNumberGenerator() {
        return $this->rng;
    }

    /**
     * Creates a new UuidGenerator.
     *
     * The {@link Factory factory} is utilized by this UuidGenerator for the
     * construction of a medium strength {@link Generator}.
     *
     * @param Factory $factory The factory of random number.
     * @throws \InvalidArgumentException if `$factory` is null.
     */
    protected function __construct(Factory $factory) {
        if ($factory === null) {
            throw new \InvalidArgumentException('No factory was provided.');
        }
        $this->factory = $factory;
    }

    /**
     * Formats the hexadecimal representation of a UUID to the official form as
     * described in [RFC 4122][1].
     *
     * The algorithm used can be described by the following code:
     *
     *      $uuid = substr($hexString, 0, 8) . '-'
     *          . substr($hexString, 8, 4) . '-'
     *          . substr($hexString, 12, 4) . '-'
     *          . substr($hexString, 16, 4) . '-'
     *          . substr($hexString, 20);
     *
     *
     * [1]: http://tools.ietf.org/html/rfc4122
     *      "RFC 4122: A Universally Unique IDentifier (UUID) URN Namespace"
     *
     * @param string $hexString The string containing the hexadecimal representation
     * of the UUID.
     * @return string The string representation of the UUID in the official
     * format.
     */
    protected function formatUuid($hexString) {
        $uuid = '';
        $lastIdx = 0;
        foreach (static::$separatorsIdxs as $sepIdx) {
            $numOfCharsToCut = $sepIdx - $lastIdx;
            $uuid .= substr($hexString, $lastIdx, $numOfCharsToCut) . '-';
            $lastIdx = $sepIdx;
        }
        $uuid .= substr($hexString, $lastIdx);

        return $uuid;
    }

    /**
     * Calculates the byte representation of a string.
     *
     * This method uses the PHP [unpack][1] function in order to get the byte
     * representation of `$string`.
     *
     * Every character of `$string` is expected to be one byte long. That goes
     * to say that this method does not support multi-byte strings.
     *
     * Moreover, the format of the array returned is the following:
     *
     *      array(
     *          index: int => byte representation: int
     *      )
     *
     * The indices start from one. So, the first byte is given by code such as:
     *
     *      $bytes[1]
     *
     * Furthermore, the representation is an integer in the space [0, 255].
     *
     * ### Example ###
     *
     * The code below:
     *
     *      $bytes = getBytesOf("Hello, world!");
     *      print_r($bytes);
     *
     * would result in the following output:
     *
     *      Array (
     *          [1] => 72
     *          [2] => 101
     *          [3] => 108
     *          [4] => 108
     *          [5] => 111
     *          [6] => 32
     *          [7] => 119
     *          [8] => 111
     *          [9] => 114
     *          [10] => 108
     *          [11] => 100
     *          [12] => 33
     *      )
     *
     *
     * [1]: http://php.net/manual/en/function.unpack.php "PHP unpack function"
     *
     * @param string $string The string whose byte representation this method
     * calculates.
     * @return array The array containing the bytes of the `$string`. Pay attention
     * to the fact that the array's first index is one (and not zero).
     * @throws \InvalidArgumentException if `$string` is null.
     */
    protected function getBytesOf($string) {
        if ($string === null) {
            throw new \InvalidArgumentException('No string was provided.');
        }

        return unpack('C*', $string);
    }

    /**
     * Calculates the hexadecimal string representation of an array of bytes.
     *
     * This method performs the reverse operation of the {@link #getBytesOf}
     * method.
     *
     * @param array $bytes The array of bytes whose hexadecimal string representation
     * this method creates.
     * @return string The string representation of `$bytes` in hexadecimal notation.
     */
    protected function getHexadecimalOf(array $bytes) {
        $binaryString = call_user_func_array(
            'pack',
            array_merge(
                array('C*'),
                $bytes
            )
        );

        return bin2hex($binaryString);
    }

    /**
     * Sets the variant to the UUID provided.
     *
     * The UUID is expected to be given in big-endian byte representation.
     *
     * The variant is set by manipulating the `$uuidBytes` with bitwise operations.
     *
     * @param array $uuidBytes The byte representation of the UUID.
     * @return void
     * @see http://tinyurl.com/bcd69xo
     */
    protected function setVariantToUuid(array &$uuidBytes) {
        $uuidBytes[self::$variantByteIndex] &= 0x3f;
        $uuidBytes[self::$variantByteIndex] |= 0x80;
    }

    /**
     * Sets the version number to a UUID.
     *
     * The UUID is expected to be given in big-endian byte representation.
     *
     * `$version` is set by manipulating the `$uuidBytes` with bitwise operations.
     *
     * It is due to the use of bitwise operators that `$version` is expected in
     * hexadecimal format. That is, version four should be given like this:
     *
     *      0x40
     *
     * @param array $uuidBytes The byte representation of the UUID.
     * @param int $version The integer in hexadecimal notation that denotes the
     * UUID version to be set.
     * @return void
     */
    protected function setVersionToUuid(array &$uuidBytes, $version) {
        $uuidBytes[self::$versionByteIndex] &= 0x0f;
        $uuidBytes[self::$versionByteIndex] |= $version;
    }
}