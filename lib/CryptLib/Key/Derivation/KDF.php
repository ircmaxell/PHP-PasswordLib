<?php
/** 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CryptLib\Key\Derivation;

/**
 *
 * @author ircmaxell
 */
interface KDF {

    /**
     *
     * @param <type> $passkey
     * @param <type> $salt
     *
     * @return string The Derived Key
     */
    public function derive($secret, $length, $other = '');

}
