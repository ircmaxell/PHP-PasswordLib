<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CryptLib\Key\Derivation\PBKDF;

/**
 * Description of pbkdf2
 *
 * @author ircmaxell
 */
class Schneier
    extends \CryptLib\Key\Derivation\AbstractDerivation
    implements \CryptLib\Key\Derivation\PBKDF
{

    public function derive($password, $salt, $iterations, $length) {
        $size = $this->hash->getSize();
        if ($length > $size) {
            throw new \InvalidArgumentException('Length is too long for hash');
        }
        $t = $this->hash->evaluate($password . $salt);
        for ($i = 2; $i <= $iterations; $i++) {
            $t = $this->hash->evaluate($t . $password . $salt);
        }
        return substr($t, 0, $length);
    }

    /**
     * Get the signature for this implementation
     *
     * This should include all information needed to build the same instance
     * later.
     *
     * @return string The signature for this instance
     */
    public function getSignature() {
        return 'schneier-'.$this->hash->getName();
    }

}
