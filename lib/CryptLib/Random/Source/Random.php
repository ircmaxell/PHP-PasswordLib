<?php
/**
 * The Random Random Number Source
 *
 * This uses the *nix /dev/random device to generate high strength numbers
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

use CryptLib\Random\Strength\High as HighStrength;

/**
 * The Random Random Number Source
 *
 * This uses the *nix /dev/random device to generate high strength numbers
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Source
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Random implements \Cryptography\Random\Source {

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
        if (!file_exists('/dev/random')) {
            return str_repeat(chr(0), $size);
        }
        $f = fopen('/dev/random', 'r');
        if (!$f) {
            return str_repeat(chr(0), $size);
        }
        $result = fread($f, $size);
        fclose($f);
        return str_pad($result, $size, chr(0));
    }
    
}
