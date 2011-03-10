<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CryptLib\Key\Derivation\KDF;

/**
 * Description of pbkdf2
 *
 * @author ircmaxell
 */
class KDF3
    extends \CryptLib\Key\Derivation\AbstractDerivation
    implements \CryptLib\Key\Derivation\KDF
{
    protected $options = array(
        'hash' => 'sha512',
        'hashfactory' => null,
        'pAmt' => 4,
    );

    public function derive($secret, $length, $other = '') {
        $size = $this->hash->getSize(true);
        $l = ceil($length / $size);
        $t = '';
        $stub = str_repeat(chr(0), max($this->options['pAmt'], 0));
        for ($i = 0; $i < $l; $i++) {
            $p = $stub . pack('N', $i);
            $t .= $this->hash->evaluate($p . $secret . $other);
        }
        return substr($t, 0, $length);
    }

}

