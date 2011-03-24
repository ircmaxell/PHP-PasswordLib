<?php
/**
 * The interface that all hash implementations must implement
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

namespace CryptLibTest\Mocks\Hash;

/**
 * The interface that all hash implementations must implement
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Factory extends \CryptLib\Hash\Factory {

    protected $callbacks = array();

    public static function init() {}

    public function __construct(array $callbacks = array()) {
        $this->callbacks = $callbacks;
    }

    public function __call($name, array $args = array()) {
        if (isset($this->callbacks[$name])) {
            return call_user_func_array($this->callbacks[$name], $args);
        }
        return null;
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
        return $this->__call('getHash', array($algo));
    }

    public function getPasswordInstance($name) {
        return $this->__call('getPasswordInstance', array($name));
    }
  
    public function verifyPassword($hash, $password) {
        return $this->__call('verifyPassword', array($hash, $password));
    }

    public function registerAlgo($name, $class) {
        return $this->__call('registerAlgo', array($name, $class));
    }
}
