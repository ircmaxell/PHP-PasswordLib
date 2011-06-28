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
class BitString {

    protected $bits = '';

    public function __construct($data, $bitform = false) {
        if ($bitform) {
            $data       = substr($data, 0, 64);
            $this->bits = str_pad($data, 64, '0', STR_PAD_LEFT);
        } else {
            $data = str_pad(
                substr($data, 0, 8),
                8,
                chr(0),
                STR_PAD_LEFT
            );
            $func = function($val) {
                return str_pad(decbin(ord($val)), 8, '0', STR_PAD_LEFT);
            };
            $this->bits = implode(
                '',
                array_map($func, str_split($data, 1))
            );
        }
    }

    public function __toString() {
        return $this->toString(64);
    }

    public function add(BitString $stream) {
        $result = '';
        $rem    = 0;
        for ($i = 63; $i >= 0; $i--) {
            $stub   = $rem + $this->bits[$i] + $stream->bits[$i];
            $rem    = floor($stub / 2);
            $result = ($stub % 2) . $result;
        }
        return new static($result, true);
    }
    public function logicalAnd(BitString $stream) {
        $part1 = $this->bits;
        $part2 = $stream->bits;
        $new   = '';
        for ($i = 0; $i < 64; $i++) {
            $new .= $part1[$i] == '1' && $part2[$i] == '1' ? '1' : '0';
        }
        return new static($new, true);
    }

    public function logicalNot() {
        $stub = str_replace('1', '2', $this->bits);
        $stub = str_replace('0', '1', $stub);
        $stub = str_replace('2', '0', $stub);
        return new static($stub, true);
    }

    public function logicalOr(BitString $stream) {
        $part1 = $this->bits;
        $part2 = $stream->bits;
        $new   = '';
        for ($i = 0; $i < 64; $i++) {
            $new .= $part1[$i] == '1' || $part2[$i] == '1' ? '1' : '0';
        }
        return new static($new, true);
    }

    public function logicalXOR(BitString $stream) {
        $part1 = $this->bits;
        $part2 = $stream->bits;
        $new   = '';
        for ($i = 0; $i < 64; $i++) {
            $new .= $part1[$i] == $part2[$i] ? '1' : '0';
        }
        return new static($new, true);
    }

    public function modulo($bits = 32) {
        return new static(substr($this->bits, -1 * $bits, $bits), true);
    }

    public function rotateLeft($bits, $size = 64) {
        $stub = substr($this->bits, -1 * $size);
        $new  = substr($stub, $bits) . substr($stub, 0, $bits);
        return new static($new, true);
    }

    public function rotateRight($bits, $size = 64) {
        $stub = substr($this->bits, -1 * $size);
        $new  = substr($stub, $size - $bits)
            . substr($stub, 0, $size - $bits);
        return new static($new, true);
    }

    public function shiftLeft($bits) {
        $new = substr($this->bits . str_repeat('0', $bits), -64);
        return new static($new, true);
    }

    public function shiftRight($bits) {
        $new = substr($this->bits, 0, -1 * $bits);
        return new static($new, true);
    }

    public function toString($bits = 64) {
        if ($bits != 64) {
            $data = substr($this->bits, -1 * $bits);
        } else {
            $data = $this->bits;
        }
        if ($bits % 8 != 0) {
            $data = str_pad(
                $data,
                strlen($data) + 8 - ($bits % 8),
                '0',
                STR_PAD_LEFT
            );
        }
        $parts = str_split($data, 8);
        $func  = function($val) {
            return chr(bindec($val));
        };
        return implode('', array_map($func, $parts));
    }

}
