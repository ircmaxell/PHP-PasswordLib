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
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
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
class SHA1 extends \Cryptography\Hash\AbstractHash {

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
     * @param boolean $binary Should the result be binary or a hex string
     *
     * @return string The hashed data
     */
    public function evaluate($data, $binary = false) {
        return \sha1($data, $binary);
    }

    /**
     * Get the size of the hashed data
     *
     * @param boolean $binary Should the result be for a binary or a hex string
     *
     * @return int The size of the hashed string
     */
    public function getSize($binary = false) {
        return $binary ? 20 : 40;
    }

}
