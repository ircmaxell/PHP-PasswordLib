<?php

class Unit_Cipher_Block_Mode_CFBTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEncryptWithInvalidBlockSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $mode = new \CryptLib\Cipher\Block\Mode\CFB($aes, 'bazbsomemoreizbu');
        $expect = $mode->encrypt('baz');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDecryptWithInvalidBlockSize() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $mode = new \CryptLib\Cipher\Block\Mode\CFB($aes, 'bazbsomemoreizbu');
        $expect = $mode->decrypt('baz');
    }

    public function testEncrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $aes->setKey('foobarbafoobarba');
        $mode = new \CryptLib\Cipher\Block\Mode\CFB($aes, 'bazbsomemoreizbu');
        $expect = $mode->encrypt('bazbizbusomemore');
        $expect .= $mode->encrypt('somemorebazbizbu');
        $expect .= $mode->finish();
        $this->assertEquals('780be9c7da419db4b2c722f3e37d90b659ef86341afa301f0ff758352cb596fe', bin2hex($expect));
    }

    public function testDecrypt() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('aes-128');
        $aes->setKey('foobarbafoobarba');
        $mode = new \CryptLib\Cipher\Block\Mode\CFB($aes, 'bazbsomemoreizbu');
        $expect = $mode->decrypt(pack('H*', '780be9c7da419db4b2c722f3e37d90b6'));
        $expect .= $mode->decrypt(pack('H*', '59ef86341afa301f0ff758352cb596fe'));
        $expect .= $mode->finish();
        $this->assertEquals('bazbizbusomemoresomemorebazbizbu', $expect);
    }


    public function testGetMode() {
        $factory = new \CryptLib\Cipher\Factory();
        $aes = $factory->getBlockCipher('rijndael-128');
        $mode = new CryptLib\Cipher\Block\Mode\CFB($aes, 'foobarbazbizbuz');
        $this->assertEquals('cfb', $mode->getMode());
    }

}
