<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * @version    Build @@version@@
 */

namespace CryptLib\Key;

/**
 *
 * @author ircmaxell
 */
interface Symmetric extends Key {

    public function getKey();

    public function saveKey($filename);

}
