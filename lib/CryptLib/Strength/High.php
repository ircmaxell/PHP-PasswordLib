<?php
/**
 * The High Strength representitive class
 *
 * High is Used for instantiating a high strength random number generator.
 *
 * High Strength keys should ONLY be used for generating extremely strong
 * cryptographic keys.  Generating them is very resource intensive and may
 * take several minutes or more depending on the requested size.
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
 * The High Strength representitive class
 *
 * High is Used for instantiating a high strength random number generator.
 *
 * High Strength keys should ONLY be used for generating extremely strong
 * cryptographic keys.  Generating them is very resource intensive and may
 * take several minutes or more depending on the requested size.
 *
 * @category   PHPCryptLib
 * @package    Random
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class High extends \CryptLib\Random\Strength {

    /**
     * @var int The current value of the instance
     */
    protected $value = 3;

}