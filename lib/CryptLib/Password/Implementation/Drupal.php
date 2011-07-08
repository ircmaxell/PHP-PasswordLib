<?php
/**
 * The Drupal password hashing implementation
 *
 * Use this class to generate and validate Drupal password hashes. 
 *
 * PHP version 5.3
 *
 * @see        http://www.openwall.com/phpass/
 * @category   PHPCryptLib
 * @package    Password
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Password\Implementation;

use CryptLib\Random\Factory as RandomFactory;
use CryptLib\Hash\Factory   as HashFactory;

/**
 * The PHPASS password hashing implementation
 *
 * Use this class to generate and validate PHPASS password hashes. 
 *
 * @see        http://www.openwall.com/phpass/
 * @category   PHPCryptLib
 * @package    Password
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Drupal extends PHPASS {


    /**
     * @var string The prefix for the generated hash
     */
    protected $prefix = '$S$';

    /**
     * @var string The hash function to use for this instance
     */
    protected $hashFunction = 'sha512';

    /**
     * Determine if the hash was made with this method
     *
     * @param string $hash The hashed data to check
     *
     * @return boolean Was the hash created by this method
     */
    public static function detect($hash) {
        return 1 == preg_match('/^\$(S)\$[a-zA-Z0-9.\/]{95}$/', $hash);
    }

    /**
     * Return the prefix used by this hashing method
     * 
     * @return string The prefix used
     */
    public static function getPrefix() {
        return '$S$';
    }

}