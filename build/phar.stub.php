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

\Phar::mapPhar('CryptLib.phar');
\Phar::interceptFileFuncs();

require_once 'phar://CryptLib.phar/CryptLib/Core/AutoLoader.php';

$autoloader = new \CryptLib\Core\AutoLoader(__NAMESPACE__, 'phar://CryptLib.phar');

$autoloader->register();

__HALT_COMPILER();
