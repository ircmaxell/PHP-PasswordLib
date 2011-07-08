<?php
/**
 * The Password Factory
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Password
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Password;

use CryptLib\Password\Implementation\Blowfish;

/**
 * The Password Factory
 *
 * @category   PHPCryptLib
 * @package    Password
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Factory extends \CryptLib\Core\AbstractFactory {

    /**
     * @var array An array of implementation classes
     */
    protected $implementations = array();

    /**
     * Build a new instance of the factory, loading core implementations
     *
     * @return void
     */
    public function __construct() {
        $this->loadImplementations();
    }

    /**
     * Create a new password hash from the supplied password
     *
     * This defaults to using Blowfish if $prefix is not supplied
     * 
     * @param string $password The password to hash
     * @param string $prefix   The prefix for the implementation
     * 
     * @return string The hashed password
     * @throws DomainException if the supplied prefix is not supported
     */
    public function createHash($password, $prefix = '$2a$') {
        if ($prefix === false) {
            throw new \DomainException('Unsupported Prefix Supplied');
        }
        foreach ($this->implementations as $impl) {
            if ($impl::getPrefix() == $prefix) {
                $instance = new $impl;
                return $instance->create($password);
            }
        }
        throw new \DomainException('Unsupported Prefix Supplied');
    }

    /**
     * Verify a hash with a supplied password
     *
     * @param string $hash     The hash to verify
     * @param string $password The password to check against
     * 
     * @return boolean True if valid, false if not
     * @throws DomainException if the supplied prefix is not supported
     */
    public function verifyHash($hash, $password) {
        foreach ($this->implementations as $impl) {
            if ($impl::detect($hash)) {
                $instance = $impl::loadFromHash($hash);
                return $instance->verify($hash, $password);
            }
        }
        throw new \DomainException('Unsupported Password Hash Supplied');
    }

    /**
     * Register a password implementation for this factory instance
     *
     * @param string $name  The name of the stategy
     * @param string $class The class name of the implementation
     *
     * @return Factory $this The current factory instance
     */
    public function registerImplementation($name, $class) {
        $this->registerType(
            'implementations',
            __NAMESPACE__ . '\\Implementation',
            $name,
            $class
        );
        return $this;
    }

    /**
     * Load all core password hashing implementations
     *
     * @return void
     */
    protected function loadImplementations() {
        $this->loadFiles(
            __DIR__ . '/Implementation',
            __NAMESPACE__ . '\\Implementation\\',
            array($this, 'registerImplementation')
        );
    }

}