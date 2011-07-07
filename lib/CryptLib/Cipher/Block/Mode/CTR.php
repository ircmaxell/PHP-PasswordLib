<?php
/**
 * The CTR (Counter) mode implementation
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
 * The CTR (Counter) mode implementation
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */

class CTR implements \CryptLib\Cipher\Block\Mode {

    /**
     * Decrypt the data using the supplied key, cipher
     *
     * @param string      $data   The data to decrypt
     * @param string      $key    The key to use for decrypting the data
     * @param BlockCipher $cipher The cipher to use for decrypting the data
     * @param string      $iv     Not Used
     * @param string      $adata  Not Used
     *
     * @return string The decrypted data
     */
    public function decrypt(
        $data,
        $key,
        \CryptLib\Cipher\Block\BlockCipher $cipher,
        $initv,
        $adata = ''
    ) {
        $size       = $cipher->getBlockSize($key);
        $blocks     = str_split($data, $size);
        $ciphertext = '';

        foreach ($blocks as $numkey => $block) {
            $data        = str_pad((string) $numkey, $size, '0', STR_PAD_LEFT);
            $stub        = $cipher->encryptBlock($data, $key);
            $ciphertext .= $stub ^ $block;
        }
        return $ciphertext;
    }

    /**
     * Encrypt the data using the supplied key, cipher
     *
     * @param string      $data   The data to encrypt
     * @param string      $key    The key to use for encrypting the data
     * @param BlockCipher $cipher The cipher to use for encrypting the data
     * @param string      $iv     Not Used
     * @param string      $adata  Not Used
     *
     * @return string The encrypted data
     */
    public function encrypt(
        $data,
        $key,
        \CryptLib\Cipher\Block\BlockCipher $cipher,
        $initv,
        $adata = ''
    ) {
        $size       = $cipher->getBlockSize($key);
        $blocks     = str_split($data, $size);
        $ciphertext = '';

        foreach ($blocks as $numkey => $block) {
            $block       = str_pad($block, $size, chr(0));
            $data        = str_pad((string) $numkey, $size, '0', STR_PAD_LEFT);
            $stub        = $cipher->encryptBlock($data, $key);
            $ciphertext .= $stub ^ $block;
        }
        return $ciphertext;
    }

    /**
     * Get the name of the current mode implementation
     *
     * @return string The current mode name
     */
    public function getMode() {
        return 'ctr';
    }

}
