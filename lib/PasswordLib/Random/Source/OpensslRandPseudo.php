<?php
/**
 * The OpensslRandomPseudo Random Number Source
 *
 * This uses openssl_random_pseudo_bytes.  This is suggested for use only with
 * with php5-openssl compiled against LibreSSL:
 *
 *   OpenSSL copying RNG state on fork:
 *     https://github.com/ramsey/uuid/issues/80#issuecomment-188286637
 *   Fixed in LibreSSL:
 *     http://opensslrampage.org/post/91910269738/fix-for-the-libressl-prng-issue-under-linux
 *
 * Additionally, CVE-2015-8867 was fixed only in versions 5.6.12, 5.5.28,
 * 5.4.44 and above:
 *
 *   https://bugs.php.net/bug.php?id=70014
 *   http://www.php.net/ChangeLog-5.php
 *
 * CVE-2015-8867 does not affect versions compiled against LibreSSL.
 *
 * PHP version 5.3
 *
 * @category   PHPPasswordLib
 * @package    Random
 * @subpackage Source
 * @author     Derek Marcotte <554b8425@razorfever.net>
 * @copyright  2011 The Authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    Build @@version@@
 */

namespace PasswordLib\Random\Source;

use PasswordLib\Core\Strength;

/**
 * The OpensslRandomPseudo Random Number Source
 *
 * This uses openssl_random_pseudo_bytes.  This is suggested for use only with
 * with php5-openssl compiled against LibreSSL:
 *
 *   OpenSSL copying RNG state on fork:
 *     https://github.com/ramsey/uuid/issues/80#issuecomment-188286637
 *   Fixed in LibreSSL:
 *     http://opensslrampage.org/post/91910269738/fix-for-the-libressl-prng-issue-under-linux
 *
 * Additionally, CVE-2015-8867 was fixed only in versions 5.6.12, 5.5.28,
 * 5.4.44 and above:
 *
 *   https://bugs.php.net/bug.php?id=70014
 *   http://www.php.net/ChangeLog-5.php
 *
 * CVE-2015-8867 does not affect versions compiled against LibreSSL.
 *
 * @category   PHPPasswordLib
 * @package    Random
 * @subpackage Source
 * @author     Derek Marcotte <554b8425@razorfever.net>
 * @codeCoverageIgnore
 */
class OpensslRandomPseudo implements \PasswordLib\Random\Source {

    /**
     * Return an instance of Strength indicating the strength of the source
     *
     * @return Strength An instance of one of the strength classes
     */
    public static function getStrength() {
        if ( preg_match('/^LibreSSL/i', OPENSSL_VERSION_TEXT) !== 1 ) {
            return new Strength(Strength::LOW);
        }

        return new Strength(Strength::MEDIUM);
    }

    /**
     * Generate a random string of the specified size
     *
     * @param int $size The size of the requested random string
     *
     * @return string A string of the requested size
     */
    public function generate($size) {
        return openssl_random_pseudo_bytes($size);
    }

}
