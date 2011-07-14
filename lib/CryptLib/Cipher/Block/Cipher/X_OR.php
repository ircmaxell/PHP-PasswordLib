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
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Cipher\Block\Cipher;



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
class X_OR extends \CryptLib\Cipher\Block\AbstractCipher {

    /**
     * Get a list of supported ciphers for this class implementation
     *
     * @return array A list of supported ciphers
     */
    public static function getSupportedCiphers() {
        return array('xor');
    }

    public function setKey($key) {
        $this->key         = $key;
        $this->keySize     = strlen($key);
        $this->blockSize   = $this->keySize;
        $this->initialized = $this->initialize();
    }

    /**
     * Decrypt a block of data using the supplied string key
     *
     * Note that the supplied data should be the same size as the block size of
     * the cipher being used.
     *
     * @param string $data The data to decrypt
     *
     * @return string The result decrypted data
     */
    protected function decryptBlockData($data) {
        return $data ^ $this->key;
    }

    /**
     * Encrypt a block of data using the supplied string key
     *
     * Note that the supplied data should be the same size as the block size of
     * the cipher being used.
     *
     * @param string $data The data to encrypt
     *
     * @return string The result encrypted data
     */
    protected function encryptBlockData($data) {
        return $data ^ $this->key;
    }

}
