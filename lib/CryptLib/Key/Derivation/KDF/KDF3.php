<?php
/**
 * An implementation of the RFC 18033-2 KDF3 function
 *
 */

namespace CryptLib\Key\Derivation\KDF;

/**
 * An implementation of the RFC 18033-2 KDF3 function
 *
 */
class KDF3
    extends \CryptLib\Key\Derivation\AbstractDerivation
    implements \CryptLib\Key\Derivation\KDF
{
    protected $options = array(
        'hash'        => 'sha512',
        'hashfactory' => null,
        'pAmt'        => 4,
    );

    public function derive($secret, $length, $other = '') {
        $size = $this->hash->getSize();
        $l    = ceil($length / $size);
        $t    = '';
        $stub = str_repeat(chr(0), max($this->options['pAmt'], 0));
        for ($i = 0; $i < $l; $i++) {
            $p  = $stub . pack('N', $i);
            $t .= $this->hash->evaluate($p . $secret . $other);
        }
        return substr($t, 0, $length);
    }

}

