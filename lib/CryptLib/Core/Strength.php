<?php
/**
 * The strength FlyweightEnum class
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

namespace CryptLib\Core;


/**
 * The strength FlyweightEnum class
 *
 * All mixing strategies must implement this interface
 *
 * @category   PHPCryptLib
 * @package    Core
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
abstract class Strength {

    /**
     * @internal
     * @var int The current value of the instance
     */
    protected $value = 1;

    /**
     * Compare the passed in strength to the current one
     *
     * Returns 0 if they are equal in strength
     * Returns 1 if the passed in argument is stronger
     * Returns -1 if the passed in argument is weaker
     *
     * @param Strength $strength The strength object to compare to
     *
     * @return int The returned comparison
     */
    public function compare(Strength $strength) {
        if ($this->value == $strength->value) {
            return 0;
        } elseif ($this->value > $strength->value) {
            return -1;
        } else {
            return 1;
        }
    }

}
