<?php
/**
 * The URandom Random Number Source
 *
 * This uses the *nix /dev/urandom device to generate medium strength numbers
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

use CryptLib\Core\Strength\Low    as LowStrength;
use CryptLib\Core\Strength\Medium as MediumStrength;

/**
 * The URandom Random Number Source
 *
 * This uses the *nix /dev/urandom device to generate medium strength numbers
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Source
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class URandom implements \CryptLib\Random\Source {

    /**
     * Return an instance of Strength indicating the strength of the source
     *
     * @return Strength An instance of one of the strength classes
     */
    public static function getStrength() {
        //This source is over-used by Suhosin patch, the strength is lowered
        if (defined('S_ALL')) {
            return new LowStrength();
        } else {
            return new MediumStrength();
        }
    }

    /**
     * Generate a random string of the specified size
     *
     * @param int $size The size of the requested random string
     *
     * @return string A string of the requested size
     */
    public function generate($size) {
        if (!file_exists('/dev/urandom')) {
            return str_repeat(chr(0), $size);
        }
        $f = fopen('/dev/urandom', 'r');
        if (!$f) {
            return str_repeat(chr(0), $size);
        }
        $result = fread($f, $size);
        fclose($f);
        return $result;
    }
    
}
