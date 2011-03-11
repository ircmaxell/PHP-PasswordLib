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
class Strength extends \CryptLib\Core\Strength {

    public function __construct($value = 1) {
        $this->value = $value;
    }
}
