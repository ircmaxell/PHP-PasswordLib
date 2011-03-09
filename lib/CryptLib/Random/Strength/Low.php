<?php
/**
 * The Low Strength representitive class
 *
 * Low is used for instantiating a low strength random number generator.
 *
 * Low Strength should be used anywhere that random strings are needed in a
 * non-cryptographical setting.  They are not strong enough to be used as
 * keys or salts.  They are however useful for one-time use tokens.
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 */

namespace CryptLib\Random\Strength;


/**
 * The Low Strength representitive class
 *
 * Low is used for instantiating a low strength random number generator.
 *
 * Low Strength should be used anywhere that random strings are needed in a
 * non-cryptographical setting.  They are not strong enough to be used as
 * keys or salts.  They are however useful for one-time use tokens.
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Low extends \CryptLib\Random\Strength {

    /**
     * @var int The current value of the instance
     */
    protected $value = 1;

}
