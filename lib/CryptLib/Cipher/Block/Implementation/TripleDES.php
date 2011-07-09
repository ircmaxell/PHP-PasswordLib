<?php
/**
 * An implementation of the TripleDES cipher
 * 
 * This was forked from phpseclib and modified to use CryptLib conventions
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @author     Jim Wigginton <terrafrost@php.net>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Cipher\Block\Implementation;

/**
 * An implementation of the TripleDES Cipher
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class TripleDES extends DES {

    /**
     * Get a list of supported ciphers for this class implementation
     *
     * @return array A list of supported ciphers
     */
    public static function getSupportedCiphers() {
        return array('tripledes');
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
        if ($cipher != 'tripledes') {
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
        if (!in_array(strlen($key), array(16, 24))) {
            throw new \InvalidArgumentException(
                'The supplied key is not a valid length'
            );
        } elseif (strlen($key) == 16) {
            $key .= substr($key, 0, 8);
        }
        $data = parent::decryptBlock($data, substr($key, 16, 8));
        $data = parent::encryptBlock($data, substr($key, 8, 8));
        $data = parent::decryptBlock($data, substr($key, 0, 8));
        return $data;
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
        if (!in_array(strlen($key), array(16, 24))) {
            throw new \InvalidArgumentException(
                'The supplied key is not a valid length'
            );
        } elseif (strlen($key) == 16) {
            $key .= substr($key, 0, 8);
        }
        $data = parent::encryptBlock($data, substr($key, 0, 8));
        $data = parent::decryptBlock($data, substr($key, 8, 8));
        $data = parent::encryptBlock($data, substr($key, 16, 8));
        return $data;
    }

    /**
     * Get the string name of the current cipher instance
     *
     * @return string The current instantiated cipher
     */
    public function getCipher() {
        return 'tripledes';
    }

}
