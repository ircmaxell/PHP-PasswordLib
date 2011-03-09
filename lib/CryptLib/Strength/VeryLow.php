<?php
/**
 * The Very Low Strength representitive class
 *
 * This class is used to describe non-cryptographic strengths.
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 */

namespace CryptLib\Strength;


/**
 * The Very Low Strength representitive class
 *
 * This class is used to describe non-cryptographic strengths.
 *
 * @category   PHPCryptLib
 * @package    Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class VeryLow extends \CryptLib\Strength {

    /**
     * @var int The current value of the instance
     */
    protected $value = 1;

}
