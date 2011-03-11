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
        $nchars    = strlen($characters);
        $nparts    = ceil(log(256, $nchars));
        $shiftBits = floor(8 / $nparts);
        // Split the string into 4 byte parts (for a 32 bit integer)
        $parts  = str_split($string, 1);
        $result = '';
        foreach ($parts as $part) {
            $len = strlen($part);
            $n = 0;
            $n = ord($part[0]);
            // Convert the single integer into the result string
            for ($i = 0; $i < $nparts; $i++) {
                $result .= $characters[$n % $nchars];
                $n     >>= $shiftBits;
            }
        }
        return $result;
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

    }

}
