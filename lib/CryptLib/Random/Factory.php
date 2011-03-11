<?php
/**
 * The Random Factory
 *
 * Use this factory to instantiate random number generators, sources and mixers.
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Random
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 */

namespace CryptLib\Random;

use CryptLib\Core\Strength\High   as HighStrength;
use CryptLib\Core\Strength\Low    as LowStrength;
use CryptLib\Core\Strength\Medium as MediumStrength;

/**
 * The Random Factory
 *
 * Use this factory to instantiate random number generators, sources and mixers.
 *
 * @category   PHPCryptLib
 * @package    Random
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Factory extends \CryptLib\Core\AbstractFactory {

    /**
     * @var array A list of available random number mixing strategies
     */
    protected $mixers = array();

    /**
     * @var array A list of available random number sources
     */
    protected $sources = array();

    /**
     * Build a new instance of the factory, loading core mixers and sources
     *
     * @return void
     */
    public function __construct() {
        $this->loadMixers();
        $this->loadSources();
    }

    /**
     * Get a generator for the requested strength
     *
     * @param Strength $strength The requested strength of the random number
     *
     * @return Generator The instantiated generator
     * @throws RuntimeException If an appropriate mixing strategy isn't found
     */
    protected function getGenerator(\CryptLib\Core\Strength $strength) {
        $sources    = $this->getSources();
        $newSources = array();
        foreach ($sources as $key => $source) {
            if ($strength->compare($source::getStrength()) <= 0) {
                $newSources[] = new $source;
            }
        }
        $newMixer = null;
        $fallback = null;
        foreach ($this->getMixers() as $mixer) {
            if ($strength->compare($mixer::getStrength()) == 0) {
                $newMixer = new $mixer;
            } elseif ($strength->compare($mixer::getStrength()) == 1) {
                $fallback = new $mixer;
            }
        }
        if (is_null($newMixer)) {
            if (is_null($fallback)) {
                throw new \RuntimeException('Could not find mixer');
            }
            $newMixer = $fallback;
        }
        return new Generator($newSources, $newMixer);
    }

    /**
     * Get a high strength random number generator
     *
     * High Strength keys should ONLY be used for generating extremely strong
     * cryptographic keys.  Generating them is very resource intensive and may
     * take several minutes or more depending on the requested size.
     * 
     * @return Generator The instantiated generator
     */
    public function getHighStrengthGenerator() {
        return $this->getGenerator(new HighStrength());
    }

    /**
     * Get a low strength random number generator
     *
     * Low Strength should be used anywhere that random strings are needed in a
     * non-cryptographical setting.  They are not strong enough to be used as
     * keys or salts.  They are however useful for one-time use tokens.
     *
     * @return Generator The instantiated generator
     */
    public function getLowStrengthGenerator() {
        return $this->getGenerator(new LowStrength());
    }

    /**
     * Get a medium strength random number generator
     *
     * Medium Strength should be used for most needs of a cryptographic nature.
     * They are strong enough to be used as keys and salts.  However, they do
     * take some time and resources to generate, so they should not be over-used
     *
     * @return Generator The instantiated generator
     */
    public function getMediumStrengthGenerator() {
        return $this->getGenerator(new MediumStrength());
    }

    /**
     * Get all loaded mixing strategies
     *
     * @return array An array of mixers
     */
    public function getMixers() {
        return $this->mixers;
    }

    /**
     * Get all loaded random number sources
     *
     * @return array An array of sources
     */
    public function getSources() {
        return $this->sources;
    }

    /**
     * Register a mixing strategy for this factory instance
     *
     * @param string $name  The name of the stategy
     * @param string $class The class name of the implementation
     *
     * @return Factory $this The current factory instance
     */
    public function registerMixer($name, $class) {
        $this->registerType(
            'mixers',
            __NAMESPACE__ . '\\Mixer',
            $name,
            $class
        );
        return $this;
    }

    /**
     * Register a random number source for this factory instance
     *
     * Note that this class must implement the Source interface
     *
     * @param string $name  The name of the stategy
     * @param string $class The class name of the implementation
     *
     * @return Factory $this The current factory instance
     */
    public function registerSource($name, $class) {
        $this->registerType(
            'sources',
            __NAMESPACE__ . '\\Source',
            $name,
            $class
        );
        return $this;
    }

    /**
     * Load all core mixing strategies
     *
     * @return void
     */
    protected function loadMixers() {
        $this->loadFiles(
            __DIR__ . '/Mixer',
            __NAMESPACE__ . '\\Mixer\\',
            'registerMixer'
        );
    }

    /**
     * Load all core random number sources
     *
     * @return void
     */
    protected function loadSources() {
        $this->loadFiles(
            __DIR__ . '/Source',
            __NAMESPACE__ . '\\Source\\',
            'registerSource'
        );
    }

}

