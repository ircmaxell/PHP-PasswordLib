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
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib;

/**
 * The simple autoloader for the CryptLib library.
 *
 * @param string $class The class name to load
 *
 * @return void
 */
spl_autoload_register(function ($class) {
    if (substr($class, 0, strlen(__NAMESPACE__)) == __NAMESPACE__) {
        $class = substr($class, strlen(__NAMESPACE__) + 1);
        $path  = str_replace('\\', '/', $class);
        $path  = __DIR__ . '/' . $path . '.php';
        if (file_exists($path)) {
            require $path;
        }
    }
});
