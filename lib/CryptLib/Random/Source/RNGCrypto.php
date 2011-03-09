<?php
/**
 * The RNGCrypto Random Number Source
 *
 * This uses the Windows RNGCrypt .NET class to generate high strength numbers
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
 */

namespace CryptLib\Random\Source;

use CryptLib\Strength\High as HighStrength;

/**
 * The RNGCrypto Random Number Source
 *
 * This uses the Windows RNGCrypt .NET class to generate high strength numbers
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Source
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class RNGCrypto implements \CryptLib\Random\Source {

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
        if (!class_exists('\\DOTNET', false)) {
            return str_repeat(chr(0), $size);
        }
        try {
            $util = new \DOTNET(
                'mscorlib',
                'System.Security.Cryptography.RNGCryptoServiceProvider'
            );
            $varient = new \VARIENT(array_fill(0, $size, chr(0)), VT_UT1 | VT_ARRAY | VT_BYREF);
            $util->GetBytes($varient);
            return implode('', (array) $varient);
        } catch (Exception $e) {
            return str_repate(chr(0), $size);
        }
    }
    
}
