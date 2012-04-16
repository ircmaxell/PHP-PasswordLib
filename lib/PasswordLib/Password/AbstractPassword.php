<?php
/**
 * The base abstract password hashing implementation
 *
 * This class provides common functionality to all child implementations
 *
 * PHP version 5.3
 *
 * @category   PHPPasswordLib
 * @package    Password
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace PasswordLib\Password;

/**
 * The base abstract password hashing implementation
 *
 * This class provides common functionality to all child implementations
 *
 * @category   PHPPasswordLib
 * @package    Password
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
abstract class AbstractPassword implements \PasswordLib\Password\Password {

    /**
     * @var string The prefix for the generated hash
     */
    protected static $prefix = false;

    /**
     * Determine if the hash was made with this method
     *
     * @param string $hash The hashed data to check
     *
     * @return boolean Was the hash created by this method
     */
    public static function detect($hash) {
        $prefix = static::getPrefix();
        return strncmp($hash, $prefix, strlen($prefix)) === 0;
    }

    /**
     * Return the prefix used by this hashing method
     *
     * @return string The prefix used
     */
    public static function getPrefix() {
        return static::$prefix;
    }

    /**
     * Perform a constant time comparison between two hash strings
     *
     * @param string $hash1 The first hash to compare
     * @param string $hash2 The second hash to compare
     * 
     * @return boolean True if the strings are identical
     */
    protected function compareStrings($hash1, $hash2) {
        if (strlen($hash1) != strlen($hash2)) {
            return false;
        }
        $len    = strlen($hash1);
        $result = 0;
        for ($i = 0; $i < $len; $i++) {
            $result |= ord($hash1[$i]) ^ ord($hash2[$i]);
        }
        return $result === 0;
    }

}
