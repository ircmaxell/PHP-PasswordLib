<?php

class Unit_Cipher_Block_Cipher_MCryptTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp() {
        if (!function_exists('mcrypt_encrypt')) {
            $this->markTestSkipped('MCrypt Is Not Available');
        }
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailure() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\MCrypt('foobarbaz');
    }
    
    public function testConstruct() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\MCrypt('rijndael-128');
    }
    
    public function testEncryptDecryptBlock() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\MCrypt('rijndael-128');
        $str = str_repeat(chr(0), 16);
        $key = str_repeat(chr(0), 32);
        $cipher->setKey($key);
        $enc = $cipher->encryptBlock($str);
        $dec = $cipher->decryptBlock($enc);
        $this->assertEquals(bin2hex($str), bin2hex($dec));
    }
    
    public function testGetBlockSize() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\MCrypt('rijndael-128');
        $this->assertEquals(16, $cipher->getBlockSize('foo'));
    }
    
    public function testGetCipher() {
        $cipher = new \CryptLib\Cipher\Block\Cipher\MCrypt('rijndael-128');
        $this->assertEquals('rijndael-128', $cipher->getCipher());
    }

}
