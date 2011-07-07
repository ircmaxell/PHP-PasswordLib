<?php
/**
 * The Crypt_Hash Implementation.  Uses the PEAR package phpseclib/Crypt_Hash
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
 * The Crypt_Hash Implementation.  Uses the PEAR package phpseclib/Crypt_Hash
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @package    Hash
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Crypt_Hash extends \CryptLib\Hash\AbstractHash {

    protected $hash = null;

    /**
     * Get an array of supported algorithms
     *
     * @return array The list of supported algorithms
     */
    public static function getAlgos() {
        if (static::findLibrary()) {
            return array(
                'md2',
                'sha256',
                'sha384',
                'sha512',
            );
        }
        return array();
    }

    public function __construct($algo) {
        parent::__construct($algo);
        $this->hash      = new \Crypt_Hash($algo);
        $this->blockSize = $this->hash->b;
    }


    /**
     * Evaluate the hash on the given input
     *
     * @param string $data The data to hash
     *
     * @return string The hashed data
     */
    public function evaluate($data) {
        $call = '_' . ($this->algo == 'sha384' ? 'sha512' : $this->algo);
        $hash = $this->hash->$call($data);
        return substr($hash, 0, $this->hash->getLength());
    }

    /**
     * Get an HMAC of the requested data with the requested key
     *
     * @param string  $data The data to hash
     * @param string  $key  The key to hmac against
     *
     * @return string The hmac'ed data
     */
    public function hmac($data, $key) {
        $this->hash->setKey($key);
        return $this->hash->hash($data);
    }

    protected static function findLibrary() {
        if (class_exists('Crypt_Hash')) {
            return true;
        }
        //Wasn't autoloaded, so try to include it directly
        foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
            $file = $path . '/Crypt/Hash.php';
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
        return false;
    }

}
