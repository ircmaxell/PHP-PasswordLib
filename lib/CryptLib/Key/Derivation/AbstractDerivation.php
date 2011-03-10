<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CryptLib\Key\Derivation;

use CryptLib\Hash\Factory as HashFactory;

/**
 * Description of abstractsymetric
 *
 * @author ircmaxell
 */
abstract class AbstractDerivation {

    protected $hash = null;
    
    protected $options = array(
        'hash' => 'sha512',
        'hashfactory' => null,
    );

    public function __construct(array $options = array()) {
        $this->options = $options + $this->options;
        if (!is_object($this->options['hashfactory'])) {
            $this->options['hashfactory'] = new HashFactory();
        }
        $this->hash = $this->options['hashfactory']->getHash($this->options['hash']);
    }

}
