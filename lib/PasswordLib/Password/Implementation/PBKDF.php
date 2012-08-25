<?php
/**
 * The PBKDF based password hashing implementation
 *
 * Use this class to generate and validate PBKDF hashed passwords.
 *
 * PHP version 5.3
 *
 * @see        http://httpd.apache.org/docs/2.2/misc/password_encryptions.html
 * @category   PHPPasswordLib
 * @package    Password
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace PasswordLib\Password\Implementation;

use PasswordLib\Key\Factory                 as KeyFactory;
use PasswordLib\Random\Factory              as RandomFactory;
use PasswordLib\Key\Derivation\PBKDF\PBKDF2 as PBKDF2;

/**
 * The PBKDF based password hashing implementation
 *
 * Use this class to generate and validate PBKDF hashed passwords.
 *
 * PHP version 5.3
 *
 * @see        http://httpd.apache.org/docs/2.2/misc/password_encryptions.html
 * @category   PHPPasswordLib
 * @package    Password
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class PBKDF extends \PasswordLib\Password\AbstractPassword {

    /**
     * @var PBKDF The PBKDF derivation implementation to use for this instance
     */
    protected $derivation = null;

    /**
     * @var Generator The Random Number Generator to use for making salts
     */
    protected $generator = null;

    /**
     * @var int The number of iterations to perform on the password
     */
    protected $iterations = 5000;

    /**
     * @var int The length in bytes of the generated password hash
     */
    protected $size = 40;

    /**
     * @var string The prefix for the generated hash
     */
    protected static $prefix = '$pbkdf$';

    /**
     * Load an instance of the class based upon the supplied hash
     *
     * @param string $hash The hash to load from
     *
     * @return Password the created instance
     * @throws InvalidArgumentException if the hash wasn't created here
     */
    public static function loadFromHash($hash) {
        if (!static::detect($hash)) {
            throw new \InvalidArgumentException('Hash Not Created Here');
        }
        $parts = explode('$', $hash);
        if (count($parts) != 7) {
            throw new \InvalidArgumentException('Hash Not Created Here');
        }
        $signature  = $parts[2];
        $factory    = new KeyFactory();
        $hash       = $factory->getPBKDFFromSignature($signature);
        $iterations = $parts[3];
        $size       = $parts[4];
        return new static($hash, $size, $iterations);
    }

    /**
     * Build a new instance of the PBKDF password class
     *
     * @param PBKDF     $derivation The derivation class to use
     * @param int       $size       The size of hash to generate
     * @param int       $iterations The number of iterations to perform
     * @param Generator $generator  The Random Generator to use
     *
     * @return void;
     */
    public function __construct(
        \PasswordLib\Key\Derivation\PBKDF $derivation = null,
        $size = 40,
        $iterations = 5000,
        \PasswordLib\Random\Generator $generator = null
    ) {
        if (is_null($derivation)) {
            $derivation = new PBKDF2();
        }
        $this->derivation = $derivation;
        $this->size       = $size < 40 ? 40 : (int) $size;
        $this->iterations = $iterations > 0 ? (int) $iterations : 1;
        if (is_null($generator)) {
            $factory   = new RandomFactory;
            $generator = $factory->getMediumStrengthGenerator();
        }
        $this->generator = $generator;
    }

    /**
     * Create a password hash for a given plain text password
     *
     * @param string $password The password to hash
     *
     * @return string The formatted password hash
     */
    public function create($password) {
        $password = $this->checkPassword($password);
        $size     = $this->size - 8; // remove size of stored bits
        $saltSize = floor($size / 5);  //Use 20% of the size for the salt
        $hashSize = $size - $saltSize;
        $salt     = $this->generator->generate($saltSize);
        return $this->hash($password, $salt, $this->iterations, $hashSize);
    }

    /**
     * Verify a password hash against a given plain text password
     *
     * @param string $password The password to hash
     * @param string $hash     The supplied ahsh to validate
     *
     * @return boolean Does the password validate against the hash
     */
    public function verify($password, $hash) {
        $password = $this->checkPassword($password);
        if (strlen($hash) <= 16 || strpos($hash, '$') === false) {
            return false;
        }
        $parts = explode('$', $hash);
        if (count($parts) != 7) {
            return false;
        } elseif ($parts[2] != $this->derivation->getSignature()) {
            return false;
        }
        $iterations = $parts[3];
        $size       = $parts[4];
        $salt       = base64_decode($parts[5]);
        $tmp        = $this->hash($password, $salt, $iterations, $size);
        return $this->compareStrings($tmp, $hash);
    }

    /**
     * Perform the hashing of the password
     *
     * @param string $password   The plain text password to hash
     * @param string $salt       The 8 byte salt to use
     * @param int    $iterations The number of iterations to use
     *
     * @return string The hashed password
     */
    protected function hash($password, $salt, $iterations, $size) {
        $bit = $this->derivation->derive($password, $salt, $iterations, $size);
        $sig = $this->derivation->getSignature();
        $sig = '$pbkdf$' . $sig . '$' . $iterations . '$' . $size;
        return $sig . '$' . base64_encode($salt) . '$' . base64_encode($bit);
    }

}
