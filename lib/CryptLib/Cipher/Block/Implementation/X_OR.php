<?php
/**
 * An implementation of the trivial XOR cipher
 *
 * This is more of an example implementation and should not be used for anything
 * requiring any level of security
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 * @version    Build @@version@@
 */

namespace CryptLib\Cipher\Block\Implementation;

/**
 * An implementation of the trivial XOR cipher
 *
 * This is more of an example implementation and should not be used for anything
 * requiring any level of security
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class X_OR implements \CryptLib\Cipher\Block\BlockCipher {

    /**
     * Get a list of supported ciphers for this class implementation
     *
     * @return array A list of supported ciphers
     */
    public static function getSupportedCiphers() {
        return array('xor');
    }

    /**
     * Construct the instance for the supplied cipher name
     *
     * @param string $cipher The cipher to implement
     *
     * @return void
     * @throws InvalidArgumentException if the cipher is not supported
     */
    public function __construct($cipher) {
        if ($cipher != 'xor') {
            $message = sprintf('Unsupported Cipher: %s', $cipher);
            throw new \InvalidArgumentException($message);
        }
        $this->cipher = $cipher;
    }

    /**
     * Decrypt a block of data using the supplied string key
     *
     * Note that the supplied data should be the same size as the block size of
     * the cipher being used.
     *
     * @param string $data The data to decrypt
     * @param string $key  The key to decrypt with
     *
     * @return string The result decrypted data
     */
    public function decryptBlock($data, $key) {
        return $data ^ $key;
    }

    /**
     * Encrypt a block of data using the supplied string key
     *
     * Note that the supplied data should be the same size as the block size of
     * the cipher being used.
     *
     * @param string $data The data to encrypt
     * @param string $key  The key to encrypt with
     *
     * @return string The result encrypted data
     */
    public function encryptBlock($data, $key) {
        return $data ^ $key;
    }

    /**
     * Get the block size for the current initialized cipher
     *
     * @param string $key The key the data will be encrypted with
     *
     * @return int The block size for the current cipher
     */
    public function getBlockSize($key) {
        return strlen($key);
    }

    /**
     * Get the string name of the current cipher instance
     *
     * @return string The current instantiated cipher
     */
    public function getCipher() {
        return 'xor';
    }

}
