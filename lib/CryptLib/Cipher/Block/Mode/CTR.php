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

class CTR extends \CryptLib\Cipher\Block\AbstractMode {

    /**
     * Reset the mode to start over (destroying any intermediate state)
     * 
     * @return void
     */
    public function reset() {
        $this->state = 0;
    }

    /**
     * Decrypt the data using the supplied key, cipher
     *
     * @param string $data The data to decrypt
     *
     * @return string The decrypted data
     */
    protected function decryptBlock($data) {
        return $this->encryptBlock($data);
    }

    /**
     * Encrypt the data using the supplied key, cipher
     *
     * @param string $data The data to encrypt
     *
     * @return string The encrypted data
     */
    protected function encryptBlock($data) {
        $size  = $this->cipher->getBlockSize();
        $block = str_pad((string) $this->state++, $size, chr(0), STR_PAD_LEFT);
        $stub  = $this->cipher->encryptBlock($block);
        return $stub ^ $data;
    }

}
