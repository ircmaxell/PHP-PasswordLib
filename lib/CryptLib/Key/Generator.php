<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * @version    Build @@version@@
 */

namespace CryptLib\Key;

/**
 * Description of generator
 *
 * @author ircmaxell
 */
interface Generator extends Key {

    public static function test();

    public function __construct(array $options = array());

    public function generate($strength, $size, $passPhrase = '');

}
