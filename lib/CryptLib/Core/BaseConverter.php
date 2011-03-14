<?php
/**
 * A Utility class for converting between raw binary strings and a given 
 * list of characters 
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Core
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 */

namespace CryptLib\Core;

/**
 * The number of bytes in a 32 bit int
 */
define('BYTES8', 1);

/**
 * A Utility class for converting between raw binary strings and a given
 * list of characters
 *
 * @category   PHPCryptLib
 * @package    Core
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class BaseConverter {

    /**
     * Convert from a raw binary string to a string of characters
     *
     * @param string $string     The string to convert from
     * @param string $characters The list of characters to convert to
     *
     * @return string The converted string
     */
    public static function convertFromBinary($string, $characters) {
        if (empty($string) || empty($characters)) {
            return '';
        }
        $string = str_split($string);
        $string = array_map(function($str) { return ord($str);}, $string);
        $converted = static::baseConvert($string, 256, strlen($characters));
        $callback = function ($num) use ($characters) {
            return $characters[$num];
        };
        return implode('', array_map($callback, $converted));
    }

    /**
     * Convert to a raw binary string from a string of characters
     *
     * @param string $string     The string to convert from
     * @param string $characters The list of characters to convert to
     *
     * @return string The converted string
     */
    public static function convertToBinary($string, $characters) {
        if (empty($string) || empty($characters)) {
            return '';
        }
        $string = str_split($string);
        $callback = function($str) use ($characters) {
            return $characters[$str];
        };
        $string = array_map($callback, $string);
        $converted = static::baseConvert($string, 256, strlen($characters));
        $callback = function ($num) {
            return chr($num);
        };
        return implode('', array_map($callback, $converted));

    }

    public static function baseConvert(array $source, $srcBase, $dstBase) {
        $result = array();
        $divmod = function($a, $b) {
            return array(
                floor($a / $b),
                $a % $b
            );
        };
        $callback = function($source, $src, $dst) use ($divmod) {
            $div = array();
            $remainder = 0;
            foreach ($source as $n) {
                list ($e, $remainder) = $divmod($n + $remainder * $src, $dst);
                if ($div || $e) {
                    $div[] = $e;
                }
            }
            return array($div, $remainder);
        };
        while ($source) {
            list ($source, $remainder) = $callback($source, $srcBase, $dstBase);
            $result[] = $remainder;
        }
        return $result;
    }

}