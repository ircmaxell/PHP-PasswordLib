<?php
/**
 * The Hash medium strength mixer class
 *
 * This class implements a mixer based upon the recommendations in RFC 4086
 * section 5.2
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

use \CryptLib\Hash\Factory         as HashFactory;
use \CryptLib\Key\Symmetric\Raw    as RawKey;
use \CryptLib\Core\Strength\Medium as MediumStrength;

/**
 * The Hash medium strength mixer class
 *
 * This class implements a mixer based upon the recommendations in RFC 4086
 * section 5.2
 *
 * @see        http://tools.ietf.org/html/rfc4086#section-5.2
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Mixer
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Hash implements \Cryptography\Random\Mixer {

    /**
     * @var Hash The hash instance to use
     */
    protected $hash = null;

    /**
     * Build the hash mixer
     *
     * @param Hash $hash The hash instance to use (defaults to sha512)
     *
     * @return void
     */
    public function __construct(\CryptLib\Hash\Hash $hash = null) {
        if (is_null($hash)) {
            $factory = new HashFactory();
            $hash = $factory->getHash('sha512');
        }
        $this->hash = $hash;
    }

    /**
     * Return an instance of Strength indicating the strength of the source
     *
     * @return Strength An instance of one of the strength classes
     */
    public static function getStrength() {
        return new MediumStrength();
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
        $len = strlen($parts[0]);
        foreach ($parts as &$part) {
            $part = str_split($part, $this->hash->getSize());
        }
        $c = count($part);
        $d = count($parts);
        unset($part);
        $hash = '';
        $offset = 0;
        for ($i = 0; $i < $c; $i++) {
            $stub = $this->hash->evaluate($parts[$offset][$i]);
            for ($j = 1; $j < $d; $j++) {
                $key = $parts[($j + $offset) % $d][$i];
                $stub ^= $this->hash->hmac($stub, $key);
            }
            $hash .= $stub;
            $offset = ($offset + 1) % $d;
        }
        return substr($hash, 0, $len);
    }

}
