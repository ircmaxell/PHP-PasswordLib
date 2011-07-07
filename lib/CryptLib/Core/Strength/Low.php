<?php
/**
 * The Low Strength representitive class
 *
 * Low is used to describe boarderline cryptographic strengths.  It is useful
 * for generating salts.
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
 * The Low Strength representitive class
 *
 * Low is used to describe boarderline cryptographic strengths.  It is useful
 * for generating salts.
 *
 * @category   PHPCryptLib
 * @package    Core
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Low extends \CryptLib\Core\Strength {

    /**
     * @internal
     * @var int The current value of the instance
     */
    protected $value = 3;

}
