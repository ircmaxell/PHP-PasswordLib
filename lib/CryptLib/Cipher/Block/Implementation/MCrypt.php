<?php
/**
 * The mcrypt block cipher implementation
 *
 * This class is used above all other implementations since it uses a core PHP
 * library if it is available.
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
 */

namespace CryptLib\Cipher\Block\Implementation;

/**
 * The mcrypt block cipher implementation
 *
 * This class is used above all other implementations since it uses a core PHP
 * library if it is available.
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class MCrypt implements \CryptLib\Cipher\Block\BlockCipher {

    /**
     * @var string $cipher The Cipher name for this instance
     */
    protected $cipher = '';

    /**
     * Get a list of supported ciphers for this class implementation
     * 
     * @return array A list of supported ciphers
     */
    public static function getSupportedCiphers() {
        if (!function_exists('mcrypt_list_algorithms')) {
            return array();
        }
        return \mcrypt_list_algorithms();
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
        $ciphers = static::getSupportedCiphers();
        if (in_array($cipher, $ciphers)) {
            $this->cipher = $cipher;
        } else {
            throw new \InvalidArgumentException('Unsupported Cipher Supplied');
        }
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
        return \mcrypt_decrypt(
            $this->cipher,
            $key,
            $data,
            \MCRYPT_MODE_ECB
        );
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
        return \mcrypt_encrypt(
            $this->cipher,
            $key,
            $data,
            \MCRYPT_MODE_ECB
        );
    }

    /**
     * Get the block size for the current initialized cipher
     *
     * @param string $key The key the data will be encrypted with
     *
     * @return int The block size for the current cipher
     */
    public function getBlockSize($key) {
        return \mcrypt_get_block_size($this->cipher, \MCRYPT_MODE_ECB);
    }

    /**
     * Get the string name of the current cipher instance
     *
     * @return string The current instantiated cipher
     */
    public function getCipher() {
        return $this->cipher;
    }

}
