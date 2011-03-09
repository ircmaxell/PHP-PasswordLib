<?php
/**
 * The Medium Strength representitive class
 *
 * Medium Strength is useful for the vast majority of cryptographic needs.  It
 * is plenty strong enough for generating salts and one-time use tokens.
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
 * The Medium Strength representitive class
 *
 * Medium Strength is useful for the vast majority of cryptographic needs.  It
 * is plenty strong enough for generating salts and one-time use tokens.
 *
 * @category   PHPCryptLib
 * @package    Strength
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class Medium extends \CryptLib\Strength {

    /**
     * @var int The current value of the instance
     */
    protected $value = 5;

}