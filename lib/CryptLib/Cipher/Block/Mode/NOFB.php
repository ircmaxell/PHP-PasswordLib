<?php
/**
 * The NOFB (Nbit Output FeedBack) mode implementation
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
 * The NOFB (Nbit Output FeedBack) mode implementation
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class NOFB implements \CryptLib\Cipher\Block\Mode {

    /**
     * Decrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to decrypt
     * @param BlockCipher $cipher The cipher to use for decrypting the data
     * @param string      $initv  The initialization vector to use
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
        return $this->encrypt($data, $cipher, $initv, $adata);
    }

    /**
     * Encrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to encrypt
     * @param BlockCipher $cipher The cipher to use for encrypting the data
     * @param string      $initv  The initialization vector to use
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
        $feedback   = $initv;
        foreach ($blocks as $block) {
            $block       = str_pad($block, $size, chr(0));
            $feedback    = $cipher->encryptBlock($feedback);
            $ciphertext .= $feedback ^ $block;
        }
        return $ciphertext;
    }

    /**
     * Get the name of the current mode implementation
     *
     * @return string The current mode name
     */
    public function getMode() {
        return 'nofb';
    }

}
