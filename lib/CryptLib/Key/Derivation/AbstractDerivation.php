<?php
/**
 * An abstract implementation of some standard key derivation needs
 *
 * PHP version 5.3
 *
 * @category   PHPCryptLib
 * @package    Key
 * @subpackage Derivation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 * @version    Build @@version@@
 */

namespace CryptLib\Key\Derivation;

use CryptLib\Hash\Factory as HashFactory;

/**
 * An abstract implementation of some standard key derivation needs
 *
 * @category   PHPCryptLib
 * @package    Key
 * @subpackage Derivation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
abstract class AbstractDerivation {

    /**
     * @var Hash A hashing algorithm to use for the derivation
     */
    protected $hash = null;

    /**
     * @var array An array of options for the key derivation function
     */
    protected $options = array(
        'hash'        => 'sha512',
        'hashfactory' => null,
    );

    /**
     * Construct the derivation instance
     *
     * @param array $options An array of options to set for this instance
     *
     * @return void
     */
    public function __construct(array $options = array()) {
        $this->options = $options + $this->options;
        if (!is_object($this->options['hashfactory'])) {
            $this->options['hashfactory'] = new HashFactory();
        }
        $factory    = $this->options['hashfactory'];
        $this->hash = $$factory->getHash($this->options['hash']);
    }

}
