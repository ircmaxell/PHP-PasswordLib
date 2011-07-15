<?php

class Unit_Cipher_Block_Mode_CBCTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEncryptWithInvalidBlockSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $mode = new \CryptLib\Cipher\Block\Mode\CBC($aes, 'bazbsomemoreizbu');
        $expect = $mode->encrypt('baz');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDecryptWithInvalidBlockSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $mode = new \CryptLib\Cipher\Block\Mode\CBC($aes, 'bazbsomemoreizbu');
        $expect = $mode->decrypt('baz');
    }

    public function testEncrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $aes->setKey('foobarbafoobarba');
        $mode = new \CryptLib\Cipher\Block\Mode\CBC($aes, 'bazbsomemoreizbu');
        $expect = $mode->encrypt('bazbizbusomemore');
        $expect .= $mode->encrypt('somemorebazbizbu');
        $expect .= $mode->finish();
        $this->assertEquals('0c71ec5adc5b68885b5f0f2c9f0d07a61e22a36b7354745c8746a845fbaae0e9', bin2hex($expect));
    }

    public function testDecrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $aes->setKey('foobarbafoobarba');
        $mode = new \CryptLib\Cipher\Block\Mode\CBC($aes, 'bazbsomemoreizbu');
        $expect = $mode->decrypt(pack('H*', '0c71ec5adc5b68885b5f0f2c9f0d07a6'));
        $expect .= $mode->decrypt(pack('H*', '1e22a36b7354745c8746a845fbaae0e9'));
        $expect .= $mode->finish();
        $this->assertEquals('bazbizbusomemoresomemorebazbizbu', $expect);
    }


    public function testGetMode() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $mode = new CryptLib\Cipher\Block\Mode\CBC($aes, 'foobarbazbizbuz');
        $this->assertEquals('cbc', $mode->getMode());
    }

}
