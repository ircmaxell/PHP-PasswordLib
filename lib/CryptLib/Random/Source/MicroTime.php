<?php
/**
 * The Microtime Random Number Source
 *
 * This uses the current micro-second (looped several times) for a **very** weak
 * random number source.  This is only useful when combined with several other
 * stronger sources
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

use CryptLib\Core\Strength\VeryLow as VeryLowStrength;

/**
 * The Microtime Random Number Source
 *
 * This uses the current micro-second (looped several times) for a **very** weak
 * random number source.  This is only useful when combined with several other
 * stronger sources
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Source
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class MicroTime implements \CryptLib\Random\Source {

    /**
     * Return an instance of Strength indicating the strength of the source
     *
     * @return Strength An instance of one of the strength classes
     */
    public static function getStrength() {
        return new VeryLowStrength();
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
        for ($i = 0; $i < $size; $i++) {
            $result .= chr((microtime(true) * mt_rand(100, 10000)) % 256);
            usleep(mt_rand(1, 100));
        }
        return $result;
    }

}
