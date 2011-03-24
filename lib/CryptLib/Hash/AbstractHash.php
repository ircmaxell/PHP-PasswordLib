<?php
/**
 * A base class implementation of the hash library
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 * @version    Build @@version@@
 */

namespace CryptLib\Hash;

/**
 * A base class implementation of the hash library
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
abstract class AbstractHash implements Hash {

    /**
     * @var string The name of the current algorithm
     */
    protected $algo = '';

    /**
     * @var int The block size for the current algorithm
     */
    protected $blockSize = 64;

    /**
     * Build the instance of the hash
     *
     * @param string $algo The name of the algorithm to use for this instance
     *
     * @return void
     * @throws InvalidArgumentException if the algorithm isn't supported
     */
    public function __construct($algo) {
        $class = get_class($this);
        if (!in_array($algo, $class::getAlgos())) {
            $message = sprintf('Unsupported Algorithm: %s', $algo);
            throw new \InvalidArgumentException($message);
        }
        $this->algo = $algo;
    }

    /**
     * Make the hash invokable (`$hash($data, $binary = false)`)
     *
     * This is a proxy for Hash::evaluate();
     *
     * @param string $data The data to be hashed
     *
     * @see Hash::evaluate()
     * @return string The hashed value
     */
    public function __invoke($data) {
        return $this->evaluate($data);
    }

    /**
     * Get the block size used by the algorithm
     *
     * @return int The block size of the hash algorithm
     */
    public function getBlockSize() {
        return $this->blockSize;
    }

    /**
     * Get the name of the current hash algorithm
     *
     * @return string The name of the current hash algorithm
     */
    public function getName() {
        return $this->algo;
    }

    /**
     * Get the size of the hashed data
     *
     * @return int The size of the hashed string
     */
    public function getSize() {
        return strlen($this->evaluate('empty'));
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
        $blockSize = $this->getBlockSize();
        if (strlen($key) > $blockSize) {
            $key = $this->evaluate($key);
        }
        if (strlen($key) < $blockSize) {
            $key = str_pad($key, $blockSize, chr(0));
        }
        $okey = str_repeat(chr(0x5c), $blockSize) ^ $key;
        $ikey = str_repeat(chr(0x36), $blockSize) ^ $key;
        $sub  = $this->evaluate($ikey . $data);
        return $this->evaluate($okey . $sub);
    }

}
