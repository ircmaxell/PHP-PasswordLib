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
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
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
class MCrypt extends \CryptLib\Cipher\Block\AbstractBlockCipher {

    /**
     * @var resource The mcrypt resource for cipher operations
     */
    protected $mcrypt = null;

    /**
     * Get a list of supported ciphers for this class implementation
     * 
     * @return array A list of supported ciphers
     */
    public static function getSupportedCiphers() {
        // @codeCoverageIgnoreStart
        if (!function_exists('mcrypt_list_algorithms')) {
            return array();
        }
        // @codeCoverageIgnoreEnd
        return mcrypt_list_algorithms();
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
        parent::__construct($cipher);
        $this->keySize   = mcrypt_get_key_size($cipher, MCRYPT_MODE_ECB);
        $this->blockSize = mcrypt_get_block_size($cipher, MCRYPT_MODE_ECB);
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
        return mdecrypt_generic($this->mcrypt, $data);
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
        return mcrypt_generic($this->mcrypt, $data);
    }

    /**
     * Initialize the cipher by preparing the key
     *
     * @return boolean The status of the initialization
     */
    protected function initialize() {
        $this->mcrypt = mcrypt_module_open($this->cipher, '', MCRYPT_MODE_ECB, '');
        if ($this->mcrypt) {
            $initv = str_repeat(chr(0), $this->getBlockSize());
            return false !== mcrypt_generic_init($this->mcrypt, $this->key, $initv);
        }
        return false;
    }

}
