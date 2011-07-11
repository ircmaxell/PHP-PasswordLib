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
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Cipher\Block;

/**
 * The interface that all block cipher modes must implement
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @codeCoverageIgnore
 */
interface Mode {

    /**
     * Decrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to decrypt
     * @param BlockCipher $cipher The cipher to use for decrypting the data
     * @param string      $initv  The initialization vector to use
     * @param string      $adata  Any additional authenticated data to decrypt with
     *
     * @return string The decrypted data
     */
    public function decrypt(
        $data,
        \CryptLib\Cipher\Block\BlockCipher $cipher,
        $initv,
        $adata = ''
    );

    /**
     * Encrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to encrypt
     * @param BlockCipher $cipher The cipher to use for encrypting the data
     * @param string      $initv  The initialization vector to use
     * @param string      $adata  Any additional authenticated data to encrypt with
     *
     * @return string The encrypted data
     */
    public function encrypt(
        $data,
        \CryptLib\Cipher\Block\BlockCipher $cipher,
        $initv,
        $adata = ''
    );

    /**
     * Get the name of the current mode implementation
     *
     * @return string The current mode name
     */
    public function getMode();

}
