<?php
/**
 * The ECB (Electronic CodeBook) mode implementation
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

namespace CryptLib\Cipher\Block\Mode;

/**
 * The ECB (Electronic CodeBook) mode implementation
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class ECB implements \CryptLib\Cipher\Block\Mode {

    /**
     * Decrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to decrypt
     * @param BlockCipher $cipher The cipher to use for decrypting the data
     * @param string      $initv  Not Used
     * @param string      $adata  Not Used
     *
     * @return string The decrypted data
     */
    public function decrypt(
        $data,
        \CryptLib\Cipher\Block\BlockCipher $cipher,
        $initv,
        $adata = ''
    ) {
        $size       = $cipher->getBlockSize();
        $blocks     = str_split($data, $size);
        $ciphertext = '';
        foreach ($blocks as $block) {
            $ciphertext .= $cipher->decryptBlock($block);
        }
        return $ciphertext;
    }

    /**
     * Encrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to encrypt
     * @param BlockCipher $cipher The cipher to use for encrypting the data
     * @param string      $initv  Not Used
     * @param string      $adata  Not Used
     *
     * @return string The encrypted data
     */
    public function encrypt(
        $data,
        \CryptLib\Cipher\Block\BlockCipher $cipher,
        $initv,
        $adata = ''
    ) {
        $size       = $cipher->getBlockSize();
        $blocks     = str_split($data, $size);
        $ciphertext = '';
        foreach ($blocks as $block) {
            $block       = str_pad($block, $size, chr(0));
            $ciphertext .= $cipher->encryptBlock($block);
        }
        return $ciphertext;
    }

    /**
     * Get the name of the current mode implementation
     *
     * @return string The current mode name
     */
    public function getMode() {
        return 'ecb';
    }

}
