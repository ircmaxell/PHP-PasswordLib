<?php

class Unit_Cipher_Block_Mode_ECBTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEncryptWithInvalidBlockSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $mode = new \CryptLib\Cipher\Block\Mode\ECB($aes, 'bazbsomemoreizbu');
        $expect = $mode->encrypt('baz');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDecryptWithInvalidBlockSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $mode = new \CryptLib\Cipher\Block\Mode\ECB($aes, 'bazbsomemoreizbu');
        $expect = $mode->decrypt('baz');
    }

    public function testEncrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $aes->setKey('foobarbafoobarba');
        $mode = new \CryptLib\Cipher\Block\Mode\ECB($aes, 'bazbsomemoreizbu');
        $expect = $mode->encrypt('bazbizbusomemore');
        $expect .= $mode->encrypt('somemorebazbizbu');
        $expect .= $mode->finish();
        $this->assertEquals('2a80eb517795427a6d96225745cff48bc9d17d088dab18e7f6b170c6f02d3ce2', bin2hex($expect));
    }

    public function testDecrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $aes->setKey('foobarbafoobarba');
        $mode = new \CryptLib\Cipher\Block\Mode\ECB($aes, 'bazbsomemoreizbu');
        $expect = $mode->decrypt(pack('H*', '2a80eb517795427a6d96225745cff48b'));
        $expect .= $mode->decrypt(pack('H*', 'c9d17d088dab18e7f6b170c6f02d3ce2'));
        $expect .= $mode->finish();
        $this->assertEquals('bazbizbusomemoresomemorebazbizbu', $expect);
    }


    public function testGetMode() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $mode = new CryptLib\Cipher\Block\Mode\ECB($aes, 'foobarbazbizbuz');
        $this->assertEquals('ecb', $mode->getMode());
    }

}
