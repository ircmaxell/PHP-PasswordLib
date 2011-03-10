<?php
/**
 * The XOR low strength mixer class
 *
 * This class implements a mixer based upon the recommendations in RFC 4086
 * section 5.2.  It is a trivial mixer that's only advantage is that it runs
 * very fast.  It is only suitable for low strength keys
 *
 * PHP version 5.3
 *
 * @see        http://tools.ietf.org/html/rfc4086#section-5.2
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Mixer
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 */

namespace CryptLib\Random\Mixer;

use \CryptLib\Core\Strength\VeryLow as VeryLowStrength;

/**
 * The XOR low strength mixer class
 *
 * This class implements a mixer based upon the recommendations in RFC 4086
 * section 5.2.  It is a trivial mixer that's only advantage is that it runs
 * very fast.  It is only suitable for low strength keys
 *
 * @see        http://tools.ietf.org/html/rfc4086#section-5.2
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Mixer
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class X_OR implements \CryptLib\Random\Mixer {

    /**
     * Return an instance of Strength indicating the strength of the source
     *
     * @return Strength An instance of one of the strength classes
     */
    public static function getStrength() {
        return new VeryLowStrength();
    }

    /**
     * Test to see if the mixer is available
     *
     * @return boolean If the mixer is available on the system
     */
    public static function test() {
        return true;
    }

    /**
     * Mix the provided array of strings into a single output of the same size
     *
     * All elements of the array should be the same size.
     *
     * @param array $parts The parts to be mixed
     *
     * @return string The mixed result
     */
    public function mix(array $parts) {
        if (empty($parts)) return '';
        $bits = array_pop($parts);
        foreach ($parts as $part) {
            $bits ^= $part;
        }
        return $bits;
    }

}
