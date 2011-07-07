<?php
/**
 * The Very Low Strength representitive class
 *
 * This class is used to describe non-cryptographic strengths.
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Core
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace CryptLib\Core\Strength;


/**
 * The Very Low Strength representitive class
 *
 * This class is used to describe non-cryptographic strengths.
 *
 * @category   PHPCryptLib
 * @package    Core
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class VeryLow extends \CryptLib\Core\Strength {

    /**
     * @internal
     * @var int The current value of the instance
     */
    protected $value = 1;

}
