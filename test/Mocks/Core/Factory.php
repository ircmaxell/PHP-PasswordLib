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

namespace CryptLibTest\Mocks\Core;

/**
 * The interface that all hash implementations must implement
 *
 * @category   PHPCryptLib
 * @package    Hash
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Factory extends \CryptLib\Core\AbstractFactory {
    
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

    public function registerType($a1, $a2, $a3, $a4, $a5) {
        return parent::registerType($a1, $a2, $a3, $a4, $a5);
    }

    public function loadFiles($dir, $name, $method) {
        return parent::loadFiles($dir, $name, $method);
    }

}
