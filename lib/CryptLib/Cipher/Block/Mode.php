<?php
/**
 * The interface that all block cipher modes must implement
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

namespace CryptLib\Cipher\Block;

/**
 * The interface that all block cipher modes must implement
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
interface Mode {

    /**
     * Decrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to decrypt
     * @param string      $key    The key to use for decrypting the data
     * @param BlockCipher $cipher The cipher to use for decrypting the data
     * @param string      $iv     The initialization vector to use
     *
     * @return string The decrypted data
     */
    public function decrypt(
        $data,
        $key,
        \CryptLib\Cipher\Block\BlockCipher $cipher,
        $iv
    );

    /**
     * Encrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to encrypt
     * @param string      $key    The key to use for encrypting the data
     * @param BlockCipher $cipher The cipher to use for encrypting the data
     * @param string      $iv     The initialization vector to use
     *
     * @return string The encrypted data
     */
    public function encrypt(
        $data,
        $key,
        \CryptLib\Cipher\Block\BlockCipher $cipher,
        $iv
    );

    /**
     * Get the name of the current mode implementation
     *
     * @return string The current mode name
     */
    public function getMode();

}
