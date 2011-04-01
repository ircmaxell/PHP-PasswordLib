<?php
/**
 * A Utility class for representing bit streams 
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Core
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 * @version    Build @@version@@
 */

namespace CryptLib\Core;

/**
 * A Utility class for representing bit streams
 *
 * @category   PHPCryptLib
 * @package    Core
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class BitStream {

    protected $data = '';

    public function __construct($data) {
        $this->data = $data;
    }

    public function __toString() {
        return $this->data;
    }

    public function add(BitStream $stream, $bitModulo = 32) {
        $len    = max(strlen($this->data), strlen($stream->data));
        $aPart  = str_pad($this->data, $len, chr(0), STR_PAD_LEFT);
        $bPart  = str_pad($stream->data, $len, chr(0), STR_PAD_LEFT);
        $result = '';
        $carry  = 0;
        for ($i = 0; $i < $len; $i++) {
            $part = ord($aPart[$i]) + ord($bPart[$i]) + $carry;
            if ($part > 255) {
                $carry = floor($part / 256);
                $part  = $part % 256;
            } else {
                $carry = 0;
            }
            $result = chr($part) . $result;
        }
        if ($carry) {
            $result = chr($carry) . $result;
        }
        if (strlen($result) > floor($bitModulo / 8)) {
            $result = substr($result, -1 * floor($bitModulo / 8));
        }
        return new static($result);
    }

    public function length() {
        return strlen($this->data) * 8;
    }

    public function logicalAnd(BitStream $stream) {
        return new static($this->data & $stream->data);
    }

    public function logicalNot() {
        return new static(~$this->data);
    }

    public function logicalOr(BitStream $stream) {
        return new static($this->data | $stream->data);
    }

    public function logicalXOR(BitStream $stream) {
        return new static($this->data ^ $stream->data);
    }

    public function rotateLeft($bits) {
        $nbits = $this->length() - $bits;
        return new static(($this->data << $bits) | ($this->data >> $nbits));
    }

    public function rotateRight($bits) {
        $nbits = $this->length() - $bits;
        return new static(($this->data >> $bits) | ($this->data << $nbits));
    }

    public function shiftLeft($bits) {
        return new static($this->data << $bits);
    }

    public function shiftRight($bits) {
        return new static($this->data >> $bits);
    }
}
