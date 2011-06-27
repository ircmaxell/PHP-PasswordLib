<?php
/**
 * The MCrypt Random Number Source
 *
 * This uses the OS's secure generator to generate high strength numbers
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Source
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 * @version    Build @@version@@
 */

namespace CryptLib\Random\Source;

use CryptLib\Core\Strength\High as HighStrength;

/**
 * The MCrypt Random Number Source
 *
 * This uses the OS's secure generator to generate high strength numbers
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Source
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class MCrypt implements \CryptLib\Random\Source {

    /**
     * Return an instance of Strength indicating the strength of the source
     *
     * @return Strength An instance of one of the strength classes
     */
    public static function getStrength() {
        return new HighStrength();
    }

    /**
     * Generate a random string of the specified size
     *
     * @param int $size The size of the requested random string
     *
     * @return string A string of the requested size
     */
    public function generate($size) {
        if (!function_exists('mcrypt_create_iv') || $size < 1) {
            return str_repeat(chr(0), $size);
        }
        /**
         * Note, The mcrypt_create_iv method internally calls the function 
         * CryptGenRandom on the Win32 API which is basically the same as 
         * using RNGCrypto on Windows
         */
        return mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
    }

}
