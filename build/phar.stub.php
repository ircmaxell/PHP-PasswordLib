<?php
/**
 * Bootstrap the library.  This registers a simple autoloader for autoloading
 * classes
 *
 * If you are using this library inside of another that uses a similar
 * autoloading system, you can use that autoloader instead of this file.
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Core
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 */

namespace CryptLib;

if (defined('\\CryptLib\\BOOTSTRAPPED')) {
    return;
}

define('BOOTSTRAPPED', true);

\Phar::mapPhar('CryptLib.phar');
\Phar::interceptFileFuncs();

/**
 * The simple autoloader for the CryptLib library.
 *
 * @param string $class The class name to load
 *
 * @return void
 */
spl_autoload_register(function ($class) {
    if (substr($class, 0, strlen(__NAMESPACE__)) == __NAMESPACE__) {
        $path = str_replace('\\', '/', $class);
        $path = 'phar://CryptLib.phar/' . $path . '.php';
        if (file_exists($path)) {
            require $path;
        }
    }
});

__HALT_COMPILER();
