<?php
/**
 * The Hash Factory
 *
 * Use this factory to instantiate hashes based upon their names. You can
 * register new hash implementations by simply calling the appropriate methods.
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 */

namespace CryptLib\Hash;

use CryptLib\Hash\Implementation\Core;

/**
 * The Hash Factory
 *
 * Use this factory to instantiate hashes based upon their names. You can
 * register new hash implementations by simply calling the appropriate methods.
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Factory extends \CryptLib\Core\AbstractFactory  {

    /**
     * @var array The loaded and supported hash algorithms
     */
    protected $algos = array();


    /**
     * Instantiate the factory
     *
     * This automatically loads and registers the default hash implementations.
     *
     * @return void
     */
    public function __construct() {
        $this->loadImplementations();
    }

    /**
     * Get an instance of the Hash interface by algorithm name
     *
     * @param string|Hash $algo The name of the algorithm, or instance of Hash
     *
     * @return Hash The created instance
     * @throws RuntimeException If the algorithm cannot be created
     */
    public function getHash($algo) {
        if (is_object($algo) && $algo instanceof Hash) {
            return $algo;
        }
        $algo = strtolower($algo);

        //use Core if possible:
        if (in_array($algo, Core::getAlgos())) {
            return new Core($algo);
        }
        if (isset($this->algos[$algo])) {
            $class = $this->algos[$algo];
            return new $class($algo);
        }
        $message = sprintf('Unsupported Algorithm: %s', $algo);
        throw new \RuntimeException($message);
    }

    public function getPasswordInstance($name) {
        if (isset($this->passwords[$name])) {
            $class = $this->passwords[$name];
            return new $class;
        }
        $message = sprintf('Unsupported Password Type: %s', $name);
        throw new \RuntimeException($message);
    }

    public function verifyPassword($hash, $password) {
        foreach ($this->passwords as $class) {
            if ($class::detect($hash)) {
                $impl = $class::loadFromHash($hash);
                return $impl->verify($hash, $password);
            }
        }
        return false;
    }

    /**
     * Register an algorithm for this factory to use if the core hash function
     * is not available
     *
     * @param string $name  The name of the algorithm
     * @param string $class The class name of the implementation
     *
     * @return Factory $this The current factory instance
     */
    public function registerAlgo($name, $class) {
        $refl      = new \ReflectionClass($class);
        $interface = '\\'. __NAMESPACE__ . '\\Hash';
        if (!$refl->implementsInterface($interface)) {
            $message = sprintf('Class must implement %s', $interface);
            throw new \InvalidArgumentException($message);
        }
        foreach ($class::getAlgos() as $algo){
            $algo               = strtolower($algo);
            $this->algos[$algo] = $class;
        }
        return $this;
    }

    /**
     * Load all core hash implementations
     *
     * @return void
     */
    protected function loadImplementations() {
        $this->loadFiles(
            __DIR__ . '/Implementation',
            __NAMESPACE__ . '\\Implementation\\',
            'registerAlgo'
        );
    }


}
