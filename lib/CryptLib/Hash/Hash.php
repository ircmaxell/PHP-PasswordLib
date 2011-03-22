<?php
/**
 * The interface that all hash implementations must implement
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
 * The interface that all hash implementations must implement
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
interface Hash {

    /**
     * Get an array of supported algorithms
     * 
     * @return array The list of supported algorithms
     */
    public static function getAlgos();

    /**
     * Make the hash invokable (`$hash($data, $binary = false)`)
     *
     * This is a proxy for Hash::evaluate();
     *
     * @param array $args The arguments for the invoked method
     *
     * @see Hash::evaluate()
     * @return string The hashed value
     */
    public function __invoke(array $args);

    /**
     * Evaluate the hash on the given input
     *
     * @param string  $data   The data to hash
     *
     * @return string The hashed data
     */
    public function evaluate($data);

    /**
     * Get the block size used by the algorithm
     *
     * @return int The block size of the hash algorithm
     */
    public function getBlockSize();

    /**
     * Get the name of the current hash algorithm
     *
     * @return string The name of the current hash algorithm
     */
    public function getName();

    /**
     * Get the size of the hashed data
     *
     * @return int The size of the hashed string
     */
    public function getSize();

    /**
     * Get an HMAC of the requested data with the requested key
     *
     * @param string  $data   The data to hash
     * @param string  $key    The key to hmac against
     *
     * @return string The hmac'ed data
     */
    public function hmac($data, $key);

}
