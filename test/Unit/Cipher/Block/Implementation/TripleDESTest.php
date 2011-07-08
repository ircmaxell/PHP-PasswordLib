<?php

class Unit_Cipher_Block_Implementation_TripleDESTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $ret = array(
            array('800000000000000000000000000000000000000000000000', '0000000000000000', '95A8D72813DAA94D'),
            array('400000000000000000000000000000000000000000000000', '0000000000000000', '0EEC1487DD8C26D5'),
            array('200000000000000000000000000000000000000000000000', '0000000000000000', '7AD16FFB79C45926'),
            array('100000000000000000000000000000000000000000000000', '0000000000000000', 'D3746294CA6A6CF3'),
            array('080000000000000000000000000000000000000000000000', '0000000000000000', '809F5F873C1FD761'),
            array('040000000000000000000000000000000000000000000000', '0000000000000000', 'C02FAFFEC989D1FC'),
            array('020000000000000000000000000000000000000000000000', '0000000000000000', '4615AA1D33E72F10'),
            array('000000000000000000000000000000000000000000000000', '8000000000000000', '95F8A5E5DD31D900'),
            array('000000000000000000000000000000000000000000000000', '4000000000000000', 'DD7F121CA5015619'),
            array('000000000000000000000000000000000000000000000000', '2000000000000000', '2E8653104F3834EA'),
            array('000000000000000000000000000000000000000000000000', '1000000000000000', '4BD388FF6CD81D4F'),
            array('000000000000000000000000000000000000000000000000', '0800000000000000', '20B9E767B2FB1456'),
            array('000000000000000000000000000000000000000000000000', '0000000000000000', '8CA64DE9C1B123A7'),
        );
        return $ret;
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncrypt($key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\TripleDES('tripledes');
        $enc = $cipher->encryptBlock(pack('H*', $data), pack('H*', $key));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testDecrypt($key, $expected, $data) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\TripleDES('tripledes');
        $enc = $cipher->decryptBlock(pack('H*', $data), pack('H*', $key));
        $this->assertEquals($expected, strtoupper(bin2hex($enc)));
    }
    
    public function testBlockSize() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\TripleDES('tripledes');
        $this->assertEquals(8, $cipher->getBlockSize('foo'));
    }
    
    public function testGetCipher() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\TripleDES('tripledes');
        $this->assertEquals('tripledes', $cipher->getCipher());
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructFailure() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\TripleDES('des');
    }

}
