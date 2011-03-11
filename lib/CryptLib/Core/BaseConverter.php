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
        $nchars    = strlen($characters);
        $shiftBits = (int)floor(log($nchars, 2));
        $result    = '';
        $seed      = ord($string[0]);
        $string    = substr($string, 1);
        $added     = 8;
        $shifted   = 0;
        while ($added - $shifted > 0) {
            $result  .= $characters[$seed % $nchars];
            $seed   >>= $shiftBits;
            $shifted += $shiftBits;
            if ($added - $shifted <= $shiftBits && isset($string[0])) {
                $seed |= (ord($string[0]) << ($added - $shifted ));
                $string = substr($string, 1);
                $added += 8;
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
