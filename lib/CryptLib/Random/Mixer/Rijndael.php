<?php
/**
 * The Rijndael-128 based high strength mixer class
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
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Random\Mixer;

use \CryptLib\Cipher\Factory     as CipherFactory;
use \CryptLib\Core\Strength\High as HighStrength;

/**
 * The Rijndael-128 based high strength mixer class
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
class Rijndael extends \CryptLib\Random\AbstractMixer {

    /**
     * An instance of a Rijndael symmetric encryption cipher
     *
     * @var Cipher The Rijndael cipher instance
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
     * Build a new instance of the Rijndael mixing function
     *
     * @param Factory $factory The optional encryption factory to use
     *
     * @return void
     */
    public function __construct(\CryptLib\Cipher\Factory $factory = null) {
        if (is_null($factory)) {
            $factory = new CipherFactory();
        }
        $this->cipher = $factory->getBlockCipher('rijndael-128');
    }

    /**
     * Get the block size (the size of the individual blocks used for the mixing)
     * 
     * @return int The block size
     */
    protected function getPartSize() {
        return $this->cipher->getBlockSize(str_repeat(chr(0), 16));
    }

    /**
     * Mix 2 parts together using one method
     *
     * @param string $part1 The first part to mix
     * @param string $part2 The second part to mix
     * 
     * @return string The mixed data
     */
    protected function mixParts1($part1, $part2) {
        return $this->cipher->encryptBlock($part1, $part2);
    }

    /**
     * Mix 2 parts together using another different method
     *
     * @param string $part1 The first part to mix
     * @param string $part2 The second part to mix
     * 
     * @return string The mixed data
     */
    protected function mixParts2($part1, $part2) {
        return $this->cipher->decryptBlock($part1, $part2);
    }

}
