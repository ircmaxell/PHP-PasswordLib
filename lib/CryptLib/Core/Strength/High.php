<?php
/**
 * The High Strength representitive class
 *
 * High strength should only be used when absolutely necessary.  Classes that
 * use this strength level will be slow and make take a signficant amount of
 * time to work.  Medium should suffice for most needs.
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Core
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 * @version    Build @@version@@
 */

namespace CryptLib\Core\Strength;


/**
 * The High Strength representitive class
 *
 * High strength should only be used when absolutely necessary.  Classes that
 * use this strength level will be slow and make take a signficant amount of
 * time to work.  Medium should suffice for most needs.
 *
 * @category   PHPCryptLib
 * @package    Core
 * @subpackage Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class High extends \CryptLib\Core\Strength {

    /**
     * @internal
     * @var int The current value of the instance
     */
    protected $value = 7;

}
