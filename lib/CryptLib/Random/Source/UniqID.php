<?php
/**
 * The UniqID Random Number Source
 *
 * This uses the internal `uniqid()` function to generate low strength random
 * numbers.
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

use CryptLib\Strength\Low as LowStrength;

/**
 * The UniqID Random Number Source
 *
 * This uses the internal `uniqid()` function to generate low strength random
 * numbers.
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Source
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class UniqID implements \CryptLib\Random\Source {

    /**
     * Return an instance of Strength indicating the strength of the source
     *
     * @return Strength An instance of one of the strength classes
     */
    public static function getStrength() {
        return new LowStrength();
    }

    /**
     * Generate a random string of the specified size
     *
     * @param int $size The size of the requested random string
     *
     * @return string A string of the requested size
     */
    public function generate($size) {
        $result = '';
        while (strlen($result) < $size) {
            $result = uniqid($result, true);
        }
        return substr($result, 0, $size);
    }
    
}
