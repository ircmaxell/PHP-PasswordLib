<?php
/**
 * The sha1 Hash implementation using the built-in SHA1 function
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Hash\Implementation;

/**
 * The sha1 Hash implementation using the built-in SHA1 function
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @package    Hash
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class SHA1 extends \CryptLib\Hash\AbstractHash {

    /**
     * Get an array of supported algorithms
     *
     * @return array The list of supported algorithms
     */
    public static function getAlgos() {
        return array('sha1');
    }

    /**
     * Evaluate the hash on the given input
     *
     * @param string  $data   The data to hash
     *
     * @return string The hashed data
     */
    public function evaluate($data) {
        return \sha1($data, true);
    }

    /**
     * Get the size of the hashed data
     *
     * @return int The size of the hashed string
     */
    public function getSize() {
        return 20;
    }

}
