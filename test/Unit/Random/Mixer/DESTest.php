<?php

use CryptLib\Random\Mixer\DES;
use CryptLib\Core\Strength\High as HighStrength;
use CryptLibTest\Mocks\Cipher\Block\BlockCipher;
use CryptLibTest\Mocks\Cipher\Factory as CipherFactory;

class Unit_Random_Mixer_DESTest extends PHPUnit_Framework_TestCase {

    public static function provideMix() {
        $data = array(
            array(array(), ''),
            array(array('', ''), ''),
            array(array('a'), 'a'),
            // This expects 'b' because of how the mock hmac function works
            array(array('a', 'b'), 'b'),
            array(array('aa', 'b'), 'b'),
            array(array('a', 'bb'), 'b'),
            array(array('aa', 'bb'), 'bb'),
            array(array('aa', 'bb', 'cc'), 'cc'),
            array(array('aabbcc', 'bbccdd', 'ccddee'), 'ccddee'),
            array(array('aabbccd', 'bbccdde', 'ccddeef'), 'ccddeef'),
            array(array('aabbccdd', 'bbccddee', 'ccddeeff'), 'ccddeefd'),
            array(array('aabbccddeeffgghh', 'bbccddeeffgghhii', 'ccddeeffgghhiijj'), 'ccddeefdeeffggii'),
        );
        return $data;
    }

    protected function getMockFactory() {
        $cipher = new BlockCipher(array(
            'getBlockSize' => function() { return 8; },
            'encryptBlock' => function($data, $key) {
                return $data ^ $key;
            }
        ));
        $factory = new CipherFactory(array(
            'getBlockCipher' => function ($algo) use ($cipher) { return $cipher; },
        ));
        return $factory;
    }

    /**
     * @covers CryptLib\Random\Mixer\DES::__construct
     */
    public function testConstructWithoutArgument() {
        $hash = new DES;
        $this->assertTrue($hash instanceof \CryptLib\Random\Mixer);
    }

    /**
     * @covers CryptLib\Random\Mixer\DES::getStrength
     */
    public function testGetStrength() {
        $strength = new HighStrength;
        $actual = DES::getStrength();
        $this->assertEquals($actual, $strength);
    }

    /**
     * @covers CryptLib\Random\Mixer\DES::test
     */
    public function testTest() {
        $actual = DES::test();
        $this->assertTrue($actual);
    }

    /**
     * @covers CryptLib\Random\Mixer\DES::mix
     * @dataProvider provideMix
     */
    public function testMix($parts, $result) {
        $mock = $this->getMockFactory();
        $mixer = new DES($mock);
        $actual = $mixer->mix($parts);
        $this->assertEquals($result, $actual);
    }


}
