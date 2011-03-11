<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CryptLib\Key\Symmetric\Generator;

use CryptLib\Random\Factory    as RandomFactory;
use CryptLib\Key\Factory       as KeyFactory;
use CryptLib\Key\Symmetric\Raw as Raw;

/**
 * Description of mtrand
 *
 * @author ircmaxell
 */
class Internal implements \CryptLib\Key\Generator {

    protected $kdf = null;

    protected $random = null;

    public function __construct(
        \CryptLib\Key\Derivation\KDF $kdf = null,
        \CryptLib\Random\Factory $random = null
    ) {
        if (is_null($kdf)) {
            $factory = new KeyFactory();
            $kdf     = $factory->getKdf('kdf3');
        }
        $this->kdf = $kdf;
        if (is_null($random)) {
            $random = new RandomFactory();
        }
        $this->random = $random;
    }

    public function generate($strength, $size, $passphrase = '') {
        $generator = $this->random->getGenerator($strength);
        $seed      = $generator->generate($size);
        $key       = $this->kdf->derive($seed, $size, $passphrase);
        return new Raw(substr($key, 0, $size));
    }

}
