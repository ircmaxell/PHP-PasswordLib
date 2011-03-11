<?php
/**
 * The core hash implementation (Uses the `hash` default extension if enabled)
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
 * The core hash implementation (Uses the `hash` default extension if enabled)
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @package    Hash
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Core extends \CryptLib\Hash\AbstractHash {

    /**
     * Get an array of supported algorithms
     *
     * @return array The list of supported algorithms
     */
    public static function getAlgos() {
        if (!function_exists('hash')) {
            return array();
        }
        return \hash_algos();
    }

    /**
     * Evaluate the hash on the given input
     *
     * @param string  $data   The data to hash
     *
     * @return string The hashed data
     */
    public function evaluate($data) {
        return \hash($this->algo, $data, true);
    }

    /**
     * Get an HMAC of the requested data with the requested key
     *
     * @param string  $data   The data to hash
     * @param string  $key    The key to hmac against
     *
     * @return string The hmac'ed data
     */
    public function hmac($data, $key) {
        return \hash_hmac($this->algo, $data, $key, true);
    }

}
