<?php
/**
 * The Random Number Generator Class
 *
 * Use this factory to generate cryptographic quality random numbers (strings)
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Random
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Random;

use CryptLib\Core\BaseConverter;

/**
 * The number of bits in each byte.
 */
define('BITS_PER_BYTE', 8);

/**
 * The Random Number Generator Class
 *
 * Use this factory to generate cryptographic quality random numbers (strings)
 *
 * @category   PHPCryptLib
 * @package    Random
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Generator {

    /**
     * @var Mixer The mixing strategy to use for this generator instance
     */
    protected $mixer = null;

    /**
     * @var array An array of random number sources to use for this generator
     */
    protected $sources = array();

    /**
     * Build a new instance of the generator
     *
     * @param array $sources An array of random data sources to use
     * @param Mixer $mixer   The mixing strategy to use for this generator
     */
    public function __construct(array $sources, Mixer $mixer) {
        foreach ($sources as $source) {
            $this->addSource($source);
        }
        $this->mixer = $mixer;
    }

    /**
     * Add a random number source to the generator
     *
     * @param Source $source The random number source to add
     *
     * @return Generator $this The current generator instance
     */
    public function addSource(Source $source) {
        $this->sources[] = $source;
        return $this;
    }

    /**
     * Generate a random number (string) of the requested size
     *
     * @param int $size The size of the requested random number
     *
     * @return string The generated random number (string)
     */
    public function generate($size) {
        $seeds = array();
        foreach ($this->sources as $source) {
            $seeds[] = $source->generate($size);
        }
        return $this->mixer->mix($seeds);
    }

    /**
     * Generate a random integer with the given range
     *
     * @param int $min The lower bound of the range to generate
     * @param int $max The upper bound of the range to generate
     *
     * @return int The generated random number within the range
     */
    public function generateInt($min = 0, $max = \PHP_INT_MAX) {
        $tmp   = (int) max($max, $min);
        $min   = (int) min($max, $min);
        $max   = $tmp;
        $range = $max - $min;
        if ($range == 0) {
            return $max;
        }
        $bytes    = max(ceil(log($range, 2) / 8), 1);
        $rand     = $this->generate($bytes);
        $number   = hexdec(bin2hex($rand));
        $numRange = pow(2, $bytes * 8) - 1;
        return (int) ($min + (($number * $range) / $numRange));
    }

    /**
     * Generate a random string of specified length.
     *
     * This uses the supplied character list for generating the new result
     * string.
     *
     * @param int    $length     The length of the generated string
     * @param string $characters An optional list of characters to use
     *
     * @return string The generated random string
     */
    public function generateString($length, $characters = '') {
        if ($length == 0 || strlen($characters) == 1) {
            return '';
        } elseif (empty($characters)) {
            // Default to base 64
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz' .
                          'ABCDEFGHIJKLMNOPQRSTUVWXYZ./';
        }
        //determine how many bytes to generate
        $bytes  = ceil($length * log(strlen($characters), 2) / BITS_PER_BYTE);
        $rand   = $this->generate($bytes);
        $result = BaseConverter::convertFromBinary($rand, $characters);
        if (strlen($result) < $length) {
            $result = str_pad($result, $length, $characters[0], STR_PAD_LEFT);
        }
        return $result;
    }

    /**
     * Get the Mixer used for this instance
     *
     * @return Mixer the current mixer
     */
    public function getMixer() {
        return $this->mixer;
    }

    /**
     * Get the Sources used for this instance
     *
     * @return Source[] the current mixer
     */
    public function getSources() {
        return $this->sources;
    }

}
