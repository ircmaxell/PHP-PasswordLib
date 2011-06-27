<?php
/**
 * An implementation of the DES cipher, using the phpseclib implementation
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
 * An implementation of the DES cipher, using the phpseclib implementation
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class DES implements \CryptLib\Cipher\Block\BlockCipher {

    /**
     * Get a list of supported ciphers for this class implementation
     *
     * @return array A list of supported ciphers
     */
    public static function getSupportedCiphers() {
        return array('des');
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
        if ($cipher != 'des') {
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
        $des = new \Crypt_DES(\CRYPT_DES_MODE_ECB);
        $des->disablePadding();
        $des->setKey($key);
        return $des->decrypt($data);
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
        $des = new \Crypt_DES();
        $des->disablePadding();
        $des->setKey($key);
        return $des->encrypt($data);
    }

    /**
     * Get the block size for the current initialized cipher
     *
     * @param string $key The key the data will be encrypted with
     *
     * @return int The block size for the current cipher
     */
    public function getBlockSize($key) {
        return 8;
    }

    /**
     * Get the string name of the current cipher instance
     *
     * @return string The current instantiated cipher
     */
    public function getCipher() {
        return 'des';
    }

}
