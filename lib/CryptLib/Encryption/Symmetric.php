<?php
/**
 * The core Symmetric encryption class.
 *
 * This class is used to encrypt data in a secure and verifyable manner
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Encryption
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 */

namespace CryptLib\Encryption;

use CryptLib\Hash\Factory as HashFactory;
use CryptLib\Random\Factory as RandomFactory;

/**
 * The core Symmetric encryption class.
 *
 * This class is used to encrypt data in a secure and verifyable manner
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Encryption
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Symmetric {

    /**
     * @var BlockCipher The block cipher instance to use
     */
    protected $cipher = null;

    /**
     * @var Hash The hash instance to use for HMAC verification
     */
    protected $hash = null;

    /**
     * @var Mode The cipher mode instance to use
     */
    protected $mode = null;

    /**
     * @var PackingMode The Packing Mode instance to use
     */
    protected $packingMode = null;

    /**
     * @var Factory The Random Factory used to build a generator
     */
    protected $randomFactory = null;

    /**
     * Construct the instance
     *
     * @param Block       $cipher      The cipher to use
     * @param Mode        $mode        The cipher mode to use
     * @param PackingMode $packingMode The packing mode to use
     * @param Hash        $hash        The hash implementation to use
     * @param Factory     $random      The random factory to use
     *
     * @return void
     */
    public function __construct(
        \CryptLib\Cipher\Block $cipher,
        \CryptLib\Cipher\Block\Mode $mode,
        \CryptLib\Encryption\PackingMode $packingMode = null,
        \CryptLib\Hash\Hash $hash = null,
        \CryptLib\Random\Factory $random = null
    ) {
        $this->cipher = $cipher;
        $this->mode = $mode;
        if (is_null($hash)) {
            $factory = new HashFactory();
            $hash = $factory->getHash('sha256');
        }
        $this->hash = $hash;
        if (is_null($random)) {
            $random = new RandomFactory();
        }
        $this->randomFactory = $random;
        if (is_null($packingMode)) {
            $encFactory = new Factory();
            $packingMode = $encFactory->getPackingMode('pkcs7');
        }
        $this->packingMode = $packingMode;
    }

    /**
     * Decrypt the data using the supplied key
     *
     * @param string    $data The data to decrypt
     * @param Symmetric $key  The symmetric key to use for decryption
     *
     * @return string The decrypted data
     */
    public function decrypt($data, \Cryptography\Key\Symmetric $key) {
        $key = $key->getKey();
        $size = $this->cipher->getBlockSize($key);
        $iv = \substr($data, 0, $size);
        $data = \substr($data, $size);
        $dec = $this->mode->decrypt($data, $key, $this->cipher, $iv);
        return $this->postDecrypt($dec, $key);
    }

    /**
     * Encrypt the data using the supplied key
     *
     * @param string    $data The data to encrypt
     * @param Symmetric $key  The symmetric key to use for encryption
     *
     * @return string The encrypted data
     */
    public function encrypt($data, \Cryptography\Key\Symmetric $key) {
        $key = $key->getKey();
        $newData = $this->prepareEncrypt($data, $key);
        $iv = $this->makeIv($this->cipher->getBlockSize($key));
        $enc = $this->mode->encrypt($newData, $key, $this->cipher, $iv);
        return $iv . $enc;
    }

    /**
     * Get the cipher instance used
     *
     * @return BlockCipher The block cipher instance
     */
    public function getCipher() {
        return $this->cipher;
    }

    /**
     * Get the cipher mode used
     *
     * @return Mode The block cipher mode used
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * Make a random full-byte initialization vector of the specified size
     *
     * @param int $size The size of IV to generate
     *
     * @return string An initialization vector of the specified size
     */
    protected function makeIv($size) {
        if ($size == 0) return '';
        $generator = $this->randomFactory->getMediumStrengthGenerator();
        return $generator->generate($size);
    }

    /**
     * Perform post-decryption tasks on the data
     *
     * @param string $data The raw decrypted data
     * @param string $key  The raw key used to decrypt the data
     *
     * @return string|false The decrypted data, or false if not valid
     */
    protected function postDecrypt($data, $key) {
        if (empty($data)) return false;
        $rawdata = $data;
        $rawdata = $this->packingMode->strip($rawdata);
        if (!$rawdata) return false;
        $hmac = substr($rawdata, -1 * $this->hash->getSize());
        $rawdata = substr($rawdata, 0, strlen($rawdata) - strlen($hmac));
        if ($hmac !== $this->hash->hmac($rawdata, $key)) {
            return false;
        }
        return $rawdata;
    }

    /**
     * Prepare the data to be encrypted
     *
     * @param string $data The data to encrypt
     * @param string $key  The key used to encrypt the data
     * 
     * @return string $the prepared encrypted data
     */
    protected function prepareEncrypt($data, $key) {
        $newData = $data;
        $newData .= $this->hash->hmac($data, $key);
        $size = $this->cipher->getBlockSize($key);

        return $this->packingMode->pad($newData, $size);
    }

}
