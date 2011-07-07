<?php
/**
 * A decorator for producing Hex hash strings instead of binary ones
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Hash;

/**
 * A decorator for producing Hex hash strings instead of binary ones
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class HexDecorator extends AbstractHash {

    /**
     * @var Hash The hash object to decorate
     */
    protected $hash = null;

    /**
     * Get an array of supported algorithms
     * 
     * @return array The list of supported algorithms
     */
    public static function getAlgos() {
        return array();
    }

    /**
     * Build the instance
     *
     * @param Hash $hash The hash function to use for the decorator
     *
     * @return void
     */
    public function __construct(Hash $hash) {
        $this->hash = $hash;
    }

    /**
     * Evaluate the hash on the given input
     *
     * @param string  $data   The data to hash
     *
     * @return string The hashed data
     */
    public function evaluate($data) {
        $data = $this->hash->evaluate($data);
        return bin2hex($data);
    }

    /**
     * Get the block size used by the algorithm
     *
     * @return int The block size of the hash algorithm
     */
    public function getBlockSize() {
        return $this->hash->getBlockSize();
    }

    /**
     * Get the name of the current hash algorithm
     *
     * @return string The name of the current hash algorithm
     */
    public function getName() {
        return $this->hash->getName();
    }

    /**
     * Get the size of the hashed data
     *
     * @return int The size of the hashed string
     */
    public function getSize() {
        return $this->hash->getSize() * 2;
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
        $data = $this->hash->hmac($data, $key);
        return bin2hex($data);
    }

}
