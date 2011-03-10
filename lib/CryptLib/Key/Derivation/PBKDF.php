<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CryptLib\Key\Derivation;

/**
 *
 * @author ircmaxell
 */
interface PBKDF {

    /**
     *
     * @param <type> $passkey
     * @param <type> $salt
     *
     * @return string The Derived Key
     */
    public function derive($passkey, $salt, $iterations, $klen);

    /**
     * Get the signature for this implementation
     *
     * This should include all information needed to build the same isntance
     * later.
     *
     * @return string The signature for this instance
     */
    public function getSignature();

}
