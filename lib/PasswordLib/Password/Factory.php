<?php
/**
 * The Password Factory
 *
 * PHP version 5.3
 *
 * @category   PHPPasswordLib
 * @package    Password
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace PasswordLib\Password;

use PasswordLib\Password\Implementation\Blowfish;

/**
 * The Password Factory
 *
 * @category   PHPPasswordLib
 * @package    Password
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Factory extends \PasswordLib\Core\AbstractFactory {

    /**
     * @var array An array of implementation classes
     */
    protected $implementations = array();

    /**
     * Current implementation
     * @var \PasswordLib\Password\Implementation
     */
    protected $implementation = null;

    /**
     * Current hash prefix
     * @var string
     */
    protected $prefix = null;

    /**
     * Build a new instance of the factory, loading core implementations
     *
     * @return void
     */
    public function __construct() {
        $this->loadImplementations();
    }

    /**
     * Get the current implementation
     * 
     * @return \PasswordLib\Password\Implementation
     */
    public function getImplementation() {
        return $this->implementation;
    }

    /**
     * Set the hash prefix
     * 
     * @param string $prefix Prefix value
     * 
     * @return Factory $this The current factory instance
     */
    public function setPrefix($prefix) {
        if ($prefix === false) {
            throw new \DomainException('Unsupported Prefix Supplied');
        }
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Get the current prefix value
     * 
     * @return integer Hash prefix value
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     * Set the implementation for the Factory instance
     * 
     * @param \PasswordLib\Password\Implementation $impl Implementation object [optional]
     * 
     * @return Factory $this The current factory instance
     */
    public function setImplementation($impl = null) {
        if ($impl !== null && is_object($impl)) {
            $this->implementation = $impl;
        } else {
            foreach ($this->implementations as $impl) {
                if ($impl::getPrefix() == $this->getPrefix()) {
                    $this->implementation = new $impl;
                }
            }
            if ($this->implementation == null) {
                throw new \DomainException('Cannot Set Implementation, Invalid Prefix');
            }
        }
        return $this;
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
        if ($prefix == false) {
            throw new \DomainException('Invalid Prefix Provided');
        }

        $impl = $this->getImplementation();
        if ($impl == null) {
            $this->setPrefix($prefix)->setImplementation();
        }
        return $this->getImplementation()->create($password);
    }

    /**
     * Verify a hash with a supplied password
     *
     * @param string $password The password to check against
     * @param string $hash     The hash to verify
     *
     * @return boolean True if valid, false if not
     * @throws DomainException if the supplied prefix is not supported
     */
    public function verifyHash($password, $hash) {
        foreach ($this->implementations as $impl) {
            if ($impl::detect($hash)) {
                $instance = $impl::loadFromHash($hash);
                return $instance->verify($password, $hash);
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
            __NAMESPACE__ . '\\Password',
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