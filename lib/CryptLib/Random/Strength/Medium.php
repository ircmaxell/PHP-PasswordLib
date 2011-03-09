<?php
/**
 * The Medium Strength representitive class
 *
 * Medium Used for instantiating a medium strength random number generator.
 *
 * Medium Strength should be used for most needs of a cryptographic nature.
 * They are strong enough to be used as keys and salts.  However, they do
 * take some time and resources to generate, so they should not be over-used
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
 * The Medium Strength representitive class
 *
 * Medium Used for instantiating a medium strength random number generator.
 *
 * Medium Strength should be used for most needs of a cryptographic nature.
 * They are strong enough to be used as keys and salts.  However, they do
 * take some time and resources to generate, so they should not be over-used
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Medium extends \CryptLib\Random\Strength {

    /**
     * @var int The current value of the instance
     */
    protected $value = 2;

}