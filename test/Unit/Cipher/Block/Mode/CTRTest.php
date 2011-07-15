<?php

class Unit_Cipher_Block_Mode_CTRTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEncryptWithInvalidBlockSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $mode = new \CryptLib\Cipher\Block\Mode\CTR($aes, 'bazbsomemoreizbu');
        $expect = $mode->encrypt('baz');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDecryptWithInvalidBlockSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $mode = new \CryptLib\Cipher\Block\Mode\CTR($aes, 'bazbsomemoreizbu');
        $expect = $mode->decrypt('baz');
    }

    public function testEncrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $aes->setKey('foobarbafoobarba');
        $mode = new \CryptLib\Cipher\Block\Mode\CTR($aes, 'bazbsomemoreizbu');
        $expect = $mode->encrypt('bazbizbusomemore');
        $expect .= $mode->encrypt('somemorebazbizbu');
        $expect .= $mode->finish();
        $this->assertEquals('780be9c7da419db4b2c722f3e37d90b6a1c284aceff53e2abd271fdfbf0b7f28', bin2hex($expect));
    }

    public function testDecrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $aes->setKey('foobarbafoobarba');
        $mode = new \CryptLib\Cipher\Block\Mode\CTR($aes, 'bazbsomemoreizbu');
        $expect = $mode->decrypt(pack('H*', '780be9c7da419db4b2c722f3e37d90b6'));
        $expect .= $mode->decrypt(pack('H*', 'a1c284aceff53e2abd271fdfbf0b7f28'));
        $expect .= $mode->finish();
        $this->assertEquals('bazbizbusomemoresomemorebazbizbu', $expect);
    }


    public function testGetMode() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $mode = new CryptLib\Cipher\Block\Mode\CTR($aes, 'foobarbazbizbuz');
        $this->assertEquals('ctr', $mode->getMode());
    }

}
