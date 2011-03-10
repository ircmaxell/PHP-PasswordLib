<?php
/**
 * The DES high strength mixer class
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

use \CryptLib\Cipher\Factory     as CipherFactory;
use \CryptLib\Core\Strength\High as HighStrength;

/**
 * The DES high strength mixer class
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
class DES implements \Cryptography\Random\Mixer {

    /**
     * An instance of a DES symmetric encryption cipher
     *
     * @var Cipher The DES cipher instance
     */
    protected $cipher = null;

    /**
     * Return an instance of Strength indicating the strength of the source
     *
     * @return Strength An instance of one of the strength classes
     */
    public static function getStrength() {
        return new HighStrength();
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
     * Build a new instance of the DES mixing function
     *
     * @param Factory $factory The optional encryption factory to use
     *
     * @return void
     */
    public function __construct(\CryptLib\Cipher\Factory $factory = null) {
        if (is_null($factory)) {
            $factory = new CipherFactory();
        }
        $this->cipher = $factory->getCipher('des');
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
        $blockSize = $this->cipher->getBlockSize();
        foreach ($parts as &$part) {
            $part = str_split($part, $blockSize - 1);
        }
        $stringSize = count($part);
        $partsSize = count($parts);
        unset($part);
        $hash = '';
        $offset = 0;
        for ($i = 0; $i < $stringSize; $i++) {
            $stub = $parts[$offset][$i];
            for ($j = 1; $j < $partsSize; $j++) {
                $newKey = $parts[($j + $offset) % $partsSize][$i];
                //Alternately encrypt and decrypt the output for each source
                if ($i % 2 == 1) {
                    $stub ^= $this->cipher->encryptBlock($stub, $newKey);
                } else {
                    $stub ^= $this->cipher->encryptBlock($stub, $newKey);
                }
            }
            $hash .= $stub;
            $offset = $offset + 1 % $partsSize;
        }
        return substr($hash, 0, $len);
    }

}
