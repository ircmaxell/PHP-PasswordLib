<?php
/**
 * The CCM (Counter CBC-MAC) mode implementation
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
 * @see        http://tools.ietf.org/html/rfc3610
 */

namespace CryptLib\Cipher\Block\Mode;

/**
 * The CCM (Counter CBC-MAC) mode implementation
 *
 * @category   PHPCryptLib
 * @package    Cipher
 * @subpackage Block
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @see        http://tools.ietf.org/html/rfc3610
 */
class CCM implements \CryptLib\Cipher\Block\Mode {

    /**
     * @var int The number of octets in the length field
     */
    protected $lSize = 4;

    /**
     * @var int The number of octets in the Authentication field
     */
    protected $authFieldSize = 8;

    /**
     * Set the auth field size to a different value.  
     * 
     * Valid values: 4, 6, 8, 10, 12, 14, 16
     * 
     * Note that increasing this size will make it harder for an attacker to 
     * modify the message payload
     *
     * @param int $new The new size of auth field to append
     * 
     * @return void
     * @throws InvalidArgumentException If the number is outside of the range
     */
    public function setAuthFieldSize($new) {
        if (!in_array($new, array(4, 6, 8, 10, 12, 14, 16))) {
            throw new \InvalidArgumentException(
                'The Auth Field must be one of: 4, 6, 8, 10, 12, 14, 16'
            );
        }
        $this->authFieldSize = (int) $new;
    }

    /**
     * Set the size of the length field.  This is a tradeoff between the maximum
     * message size and the size of the initialization vector
     *
     * Valid values are 2, 3, 4, 5, 6, 7, 8
     * 
     * @param int $new The new LSize to use
     * 
     * @return void
     * @throws InvalidArgumentException If the number is outside of the range
     */
    public function setLSize($new) {
        if ($new < 2 || $new > 8) {
            throw new \InvalidArgumentException(
                'The LSize must be between 2 and 8 inclusive'
            );
        }
        $this->lSize = (int) $new;
    }

    /**
     * Decrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to decrypt
     * @param string      $key    The key to use for decrypting the data
     * @param BlockCipher $cipher The cipher to use for decrypting the data
     * @param string      $iv     The initialization vector to use
     * @param string      $adata  Any additional authenticated data to decrypt with
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
        $initv      = $this->extractInitv($initv, $cipher->getBlockSize($key));
        $message    = substr($data, 0, -1 * $this->authFieldSize);
        $uValue     = substr($data, -1 * $this->authFieldSize);
        $data       = $this->encryptMessage(
            $initv,
            $key,
            $message,
            $uValue,
            $cipher
        );
        $computedT  = substr($data, -1 * $this->authFieldSize);
        $data       = substr($data, 0, -1 * $this->authFieldSize);
        $authFieldT = $this->computeAuthField($initv, $key, $data, $adata, $cipher);
        if ($authFieldT != $computedT) {
            return false;
        }
        return rtrim($data, chr(0));
    }

    /**
     * Encrypt the data using the supplied key, cipher and initialization vector
     *
     * @param string      $data   The data to encrypt
     * @param string      $key    The key to use for encrypting the data
     * @param BlockCipher $cipher The cipher to use for encrypting the data
     * @param string      $iv     The initialization vector to use
     * @param string      $adata  Any additional authenticated data to encrypt with
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
        $blockSize  = $cipher->getBlockSize($key);
        $initv      = $this->extractInitv($initv, $blockSize);
        $authFieldT = $this->computeAuthField($initv, $key, $data, $adata, $cipher);
        $data       = $this->encryptMessage(
            $initv,
            $key,
            $data,
            $authFieldT,
            $cipher
        );
        return $data;
    }

    /**
     * Get the name of the current mode implementation
     *
     * @return string The current mode name
     */
    public function getMode() {
        return 'ccm';
    }

    /**
     * Compute the authentication field
     *
     * @param string      $initv  The initalization vector
     * @param string      $key    The key to use
     * @param string      $data   The data to compute with
     * @param string      $adata  The authentication data to use
     * @param BlockCipher $cipher The cipher implementation
     * 
     * @return string The computed MAC Authentication Code 
     */
    protected function computeAuthField(
        $initv,
        $key,
        $data,
        $adata,
        \CryptLib\Cipher\Block\BlockCipher $cipher
    ) {
        $blockSize = $cipher->getBlockSize($key);
        $flags     = pack(
            'C',
            64 * (empty($adata) ? 0 : 1)
                + 8 * (($this->authFieldSize - 2) / 2)
                + ($this->lSize - 1)
        );
        $blocks    = array(
            $flags . $initv . pack($this->getLPackString(), strlen($data))
        );
        if (strlen($data) % $blockSize != 0) {
            $data .= str_repeat(chr(0), $blockSize - (strlen($data) % $blockSize));
        }

        $blocks   = array_merge($blocks, $this->processAData($adata, $blockSize));
        $blocks   = array_merge($blocks, str_split($data, $blockSize));
        $crypted  = array(
            1 => $cipher->encryptBlock($blocks[0], $key)
        );
        $blockLen = count($blocks);
        for ($i = 1; $i < $blockLen; $i++) {
            $crypted[$i + 1] = $cipher->encryptBlock(
                $crypted[$i] ^ $blocks[$i],
                $key
            );
        }
        return substr(end($crypted), 0, $this->authFieldSize);
    }

    /**
     * Encrypt the data using the supplied method
     *
     * @param string      $initv     The initialization Vector
     * @param string      $key       The key to encrypt with
     * @param string      $data      The data to encrypt
     * @param string      $authValue The auth value field 
     * @param BlockCipher $cipher    The cipher to use
     * 
     * @return string The encrypted data with authfield payload
     */
    protected function encryptMessage(
        $initv,
        $key,
        $data,
        $authValue,
        \CryptLib\Cipher\Block\BlockCipher $cipher
    ) {
        $blockSize = $cipher->getBlockSize($key);
        $flags     = pack('C', ($this->lSize - 1));
        $blocks    = str_split($data, $blockSize);
        $sblocks   = array();
        $blockLen  = count($blocks);
        for ($i = 0; $i <= $blockLen; $i++) {
            $sblocks[] = $cipher->encryptBlock(
                $flags . $initv . pack($this->getLPackString(), $i),
                $key
            );
        }
        $encrypted = '';
        foreach ($blocks as $key => $value) {
            if (strlen($value) < $blockSize) {
                $sblocks[$key + 1] = substr($sblocks[$key + 1], 0, strlen($value));
            }
            $encrypted .= $sblocks[$key + 1] ^ $value;
        }
        $sValue = substr($sblocks[0], 0, $this->authFieldSize);
        $uValue = $authValue ^ $sValue;
        return $encrypted . $uValue;
    }

    /**
     * Extract the nonce from the initialization vector
     *
     * @param string $initv     The initialization Vector to trim
     * @param int    $blockSize The size of the final nonce
     * 
     * @return string The sized nonce
     * @throws InvalidArgumentException if the IV is too short
     */
    protected function extractInitv($initv, $blockSize) {
        $initSize = $blockSize - 1 - $this->lSize;
        if (strlen($initv) < $initSize) {
            throw new \InvalidArgumentException(sprintf(
                'Supplied Initialization Vector is too short, should be %d bytes',
                $initSize
            ));
        }
        return substr($initv, 0, $initSize);
    }

    /**
     * Get a packing string related to the instance lSize variable
     *
     * @return string The pack() string to use to pack the length variables
     * @see pack()
     */
    protected function getLPackString() {
        if ($this->lSize <= 3) {
            return str_repeat('x', $this->lSize - 2) . 'n';
        }
        return str_repeat('x', $this->lSize - 4) . 'N';
    }

    /**
     * Process the Authentication data for authenticating
     *
     * @param string $adata     The data to authenticate with
     * @param int    $blockSize The block size for the cipher
     * 
     * @return array An array of strings bound by the supplied blocksize
     */
    protected function processAData($adata, $blockSize) {
        if (!empty($adata)) {
            if (strlen($adata) < ((1 << 16) - (1 << 8))) {
                $len = pack('n', strlen($adata));
            } else {
                $len = chr(0xff) . chr(0xfe) . pack('N', strlen($adata));
            }
            $temp = $len . $adata;
            if (strlen($temp) % $blockSize != 0) {
                //Pad the string to exactly mod16
                $temp .= str_repeat(
                    chr(0),
                    $blockSize - (strlen($temp) % $blockSize)
                );
            }
            return str_split($temp, $blockSize);
        }
        return array();
    }

}
