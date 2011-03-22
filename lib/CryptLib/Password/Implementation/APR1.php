<?php
/**
 * The APR1 password hashing implementation
 *
 * Use this class to generate and validate APR1 password hashes.  APR1 hashes
 * are used primarrily by Apache for .htaccess password storage.
 *
 * PHP version 5.3
 *
 * @see        http://httpd.apache.org/docs/2.2/misc/password_encryptions.html
 * @category   PHPCryptLib
 * @package    Password
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 * @copyright  2011 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @license    http://www.gnu.org/licenses/lgpl-2.1.html LGPL v 2.1
 * @version    Build @@version@@
 */

namespace CryptLib\Password\Implmentation;

use CryptLib\Random\Factory as RandomFactory;
use CryptLib\Hash\Factory   as HashFactory;

/**
 * The APR1 password hashing implementation
 *
 * Use this class to generate and validate APR1 password hashes.  APR1 hashes
 * are used primarrily by Apache for .htaccess password storage.
 *
 * @see        http://httpd.apache.org/docs/2.2/misc/password_encryptions.html
 * @category   PHPCryptLib
 * @package    Password
 * @subpackage Implementation
 * @author     Anthony Ferrara <ircmaxell@ircmaxell.com>
 */
class APR1 implements \CryptLib\Password\Password {

    /**
     * @var Generator The random generator to use for seeds
     */
    protected $generator = null;

    /**
     * @var Hash The hash function to use (MD5)
     */
    protected $hash = null;

    /**
     * @var int The number of iterations to perform (1000 for APR1)
     */
    protected $iterations = 1000;

    /**
     * Determine if the hash was made with this method
     *
     * @param string $hash The hashed data to check
     *
     * @return boolean Was the hash created by this method
     */
    public static function detect($hash) {
        return strncmp($hash, '$apr1$', 6) === 0;
    }

    /**
     * Load an instance of the class based upon the supplied hash
     *
     * @param string $hash The hash to load from
     *
     * @return Password the created instance
     * @throws InvalidArgumentException if the hash wasn't created here
     */
    public static function loadFromHash($hash) {
        if (!static::detect($hash)) {
            throw new \InvalidArgumentException('Hash Not Created Here');
        }
        return new static;
    }

    /**
     * Build a new instance
     *
     * @param Generator $generator The random generator to use for seeds
     * @param Factory   $factory   The hash factory to use for this instance
     *
     * @return void
     */
    public function __construct(
        \CryptLib\Random\Generator $generator = null,
        \CryptLib\Hash\Factory $factory = null
    ) {
        if (is_null($factory)) {
            $factory = new HashFactory();
        }
        $this->hash = $factory->getHash('md5');
        if (is_null($generator)) {
            $random    = new RandomFactory();
            $generator = $random->getMediumStrengthGenerator();
        }
        $this->generator = $generator;
    }

    /**
     * Create a password hash for a given plain text password
     *
     * @param string $password The password to hash
     *
     * @return string The formatted password hash
     */
    public function create($password) {
        $salt = $this->to64($this->generator->generate(8), 8);
        return $this->hash($password, $salt, $this->iterations);
    }

    /**
     * Verify a password hash against a given plain text password
     *
     * @param string $hash     The supplied ahsh to validate
     * @param string $password The password to hash
     *
     * @return boolean Does the password validate against the hash
     */
    public function verify($hash, $password) {
        $bits = explode('$', $hash);
        if (!isset($bits[3]) || $bits[1] != 'apr1') {
            return false;
        }
        $test = $this->hash($password, $bits[2], $this->iterations);
        return $test == $hash;
    }

    /**
     * Perform the hashing of the password
     *
     * @param string $password   The plain text password to hash
     * @param string $salt       The 8 byte salt to use
     * @param int    $iterations The number of iterations to use
     *
     * @return string The hashed password
     */
    protected function hash($password, $salt, $iterations) {
        $len  = strlen($password);
        $text = $password . '$apr1$' . $salt;
        $bin  = $this->hash->evaluate($password.$salt.$password);
        for ($i = $len; $i > 0; $i -= 16) {
            $text .= substr($bin, 0, min(16, $i));
        }
        for ($i = $len; $i > 0; $i >>= 1) {
            $text .= ($i & 1) ? chr(0) : $password[0];
        }
        $bin = $this->iterate($text, $iterations, $salt, $password);
        return $this->convertToHash($bin, $salt);
    }

    protected function iterate($text, $iterations, $salt, $password) {
        $bin = $this->hash->evaluate($text);
        for ($i = 0; $i < $iterations; $i++) {
            $new = ($i & 1) ? $password : $bin;
            if ($i % 3) {
                $new .= $salt;
            }
            if ($i % 7) {
                $new .= $password;
            }
            $new .= ($i & 1) ? $bin : $password;
            $bin  = $this->hash->evaluate($new);
        }
        return $bin;
    }

    /**
     * Base64 encode the input string, and truncate to the specified size
     *
     * This implmentation uses a different mapping than the core base64_encode.
     *
     * @param string $str  The source string to encode
     * @param int    $size The size of the result string to return
     *
     * @return string The encoded string
     */
    protected function base64($str, $size) {
        $str    = str_split($str, 3);
        $result = '';
        foreach ($str as $chr) {
            $c0      = ord($chr[0]);
            $c1      = isset($chr[1]) ? ord($chr[1]) : 0;
            $c2      = isset($chr[2]) ? ord($chr[2]) : 0;
            $result .= $this->to64(($c2 << 16) | ($c1<<8) | $c0, 4);
        }
        return substr($result, 0, $size);
    }

    protected function convertToHash($bin, $salt) {
        $tmp  = '$apr1$'.$salt.'$';
        $tmp .= $this->to64(
            (ord($bin[0])<<16) | (ord($bin[6])<<8) | ord($bin[12]),
            4
        );
        $tmp .= $this->to64(
            (ord($bin[1])<<16) | (ord($bin[7])<<8) | ord($bin[13]),
            4
        );
        $tmp .= $this->to64(
            (ord($bin[2])<<16) | (ord($bin[8])<<8) | ord($bin[14]),
            4
        );
        $tmp .= $this->to64(
            (ord($bin[3])<<16) | (ord($bin[9])<<8) | ord($bin[15]),
            4
        );
        $tmp .= $this->to64(
            (ord($bin[4])<<16) | (ord($bin[10])<<8) | ord($bin[5]),
            4
        );
        $tmp .= $this->to64(
            ord($bin[11]),
            2
        );
        return $tmp;
    }

    /**
     * Convert the input number to a base64 number of the specified size
     *
     * @param int $num  The number to convert
     * @param int $size The size of the result string
     *
     * @return string The converted representation
     */
    protected function to64($num, $size) {
        static $seed = '';
        if (empty($seed)) {
            $seed = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'.
                    'abcdefghijklmnopqrstuvwxyz';
        }
        $result = '';
        while (--$size >= 0) {
            $result .= $seed[$num & 0x3f];
            $num   >>= 6;
        }
        return $result;
    }

}
