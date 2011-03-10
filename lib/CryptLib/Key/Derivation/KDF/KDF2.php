<?php
/**
 * An implementation of the RFC 18033-2 KDF2 function
 *
 */

namespace CryptLib\Key\Derivation\KDF;

/**
 * An implementation of the RFC 18033-2 KDF2 function
 *
 */
class KDF2
    extends \CryptLib\Key\Derivation\AbstractDerivation
    implements \CryptLib\Key\Derivation\KDF
{

    public function derive($secret, $length, $other = '') {
        $size = $this->hash->getSize();
        $l = ceil($length / $size);
        $t = '';
        for ($i = 1; $i <= $l; $i++) {
            $p = pack('N', $i);
            $t .= $this->hash->evaluate($secret . $p . $other);
        }
        return substr($t, 0, $length);
    }

}

