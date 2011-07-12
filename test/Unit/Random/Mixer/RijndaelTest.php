<?php

use CryptLib\Random\Mixer\Rijndael;
use CryptLib\Core\Strength;
use CryptLibTest\Mocks\Cipher\Block\Cipher;
use CryptLibTest\Mocks\Cipher\Factory as CipherFactory;

class Unit_Random_Mixer_RijndaelTest extends PHPUnit_Framework_TestCase {

    public static function provideMix() {
        $data = array(
            array(array(), ''),
            array(array('', ''), ''),
            array(array('a'), 'a'),
            // This expects 'b' because of how the mock hmac function works
            array(array('a', 'b'), 'b'),
            array(array('aa', 'b'), 'b'.chr(0)),
            array(array('a', 'bb'), 'b'),
            array(array('aa', 'bb'), 'bb'),
            array(array('aa', 'bb', 'cc'), 'cc'),
            array(array('aabbcc', 'bbccdd', 'ccddee'), 'ccbbdd'),
            array(array('aabbccd', 'bbccdde', 'ccddeef'), 'ccbbddf'),
            array(array('aabbccdd', 'bbccddee', 'ccddeeff'), 'ccbbddff'),
            array(array('aabbccddeeffgghh', 'bbccddeeffgghhii', 'ccddeeffgghhiijj'), 'ccbbddffeeggiihh'),
        );
        return $data;
    }

    protected function getMockFactory() {
        $cipherkey = str_repeat(chr(0), 2);
        $cipher = new Cipher(array(
            'getBlockSize' => function() use (&$cipherkey) {
                return strlen($cipherkey);
            },
            'setKey' => function($key) use (&$cipherkey) {
                $cipherkey = $key;
            },
            'encryptBlock' => function($data) use (&$cipherkey) {
                return $data ^ $cipherkey;
            },
            'decryptBlock' => function($data) use (&$cipherkey) {
                return $data ^ $cipherkey;
            },
        ));
        $factory = new CipherFactory(array(
            'getBlockCipher' => function ($algo) use ($cipher) { return $cipher; },
        ));
        return $factory;
    }

    /**
     * @covers CryptLib\Random\Mixer\Rijndael
     * @covers CryptLib\Random\AbstractMixer
     */
    public function testConstructWithoutArgument() {
        $hash = new Rijndael;
        $this->assertTrue($hash instanceof \CryptLib\Random\Mixer);
    }

    /**
     * @covers CryptLib\Random\Mixer\Rijndael
     * @covers CryptLib\Random\AbstractMixer
     */
    public function testGetStrength() {
        $strength = new Strength(Strength::HIGH);
        $actual = Rijndael::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Mixer\Rijndael
     * @covers CryptLib\Random\AbstractMixer
     */
    public function testTest() {
        $actual = Rijndael::test();
        $this->assertTrue($actual);
    }

    /**
     * @covers CryptLib\Random\Mixer\Rijndael
     * @covers CryptLib\Random\AbstractMixer
     * @dataProvider provideMix
     */
    public function testMix($parts, $result) {
        $mock = $this->getMockFactory();
        $mixer = new Rijndael($mock);
        $actual = $mixer->mix($parts);
        $this->assertEquals($result, $actual);
    }


}
