<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CryptLib\Key;

/**
 * Description of factory
 *
 * @author ircmaxell
 */
class Factory extends \CryptLib\Core\AbstractFactory {

    protected $kdf = array();
    protected $pbkdf = array();
    protected $symmetricGenerators = array();

    public function __construct() {
        $this->loadPBKDF();
//        $this->loadKDF();
//        $this->loadSymmetricGenerators();
    }

    public function getKDF($name = 'kdf3', array $options = array()) {
        if (isset($this->kdf[$name])) {
            $class = $this->kdf[$name];
            return new $class($options);
        }
        throw new \InvalidArgumentException('Unsupported KDF');
    }

    public function getPBKDF($name = 'pbkdf2', array $options = array()) {
        if (isset($this->pbkdf[$name])) {
            $class = $this->pbkdf[$name];
            return new $class($options);
        }
        throw new \InvalidArgumentException('Unsupported PBKDF');
    }

    public function getSymmetricKeyGenerator() {
        
    }

    public function registerKDF($name, $class) {
        $this->registerType(
            'kdf',
            __NAMESPACE__ . '\\Derivation\\KDF',
            $name,
            $class
        );
    }

    public function registerPBKDF($name, $class) {
        $this->registerType(
            'pbkdf',
            __NAMESPACE__ . '\\Derivation\\PBKDF',
            $name,
            $class
        );
    }

    protected function loadKDF() {
        $this->loadFiles(
            __DIR__ . '/derivation/kdf',
            __NAMESPACE__ . '\\Derivation\\KDF\\',
            'registerKDF'
        );
    }
 
    protected function loadPBKDF() {
        $this->loadFiles(
            __DIR__ . '/derivation/pbkdf',
            __NAMESPACE__ . '\\Derivation\\PBKDF\\',
            'registerPBKDF'
        );
    }
            

}
