<?php

//Test the PHP implementation, since that's the only one we'll use
class_exists('Crypt_DES') && define('CRYPT_DES_MODE', CRYPT_DES_MODE_INTERNAL);

class Unit_Cipher_Block_Implementation_DESTest extends PHPUnit_Framework_TestCase {

    /**
     * @return array The test vectors
     */
    public static function provideTestEncryptVectors() {
        $ret = array(
            array('0101010101010101', '95F8A5E5DD31D900', '8000000000000000'),
            array('0101010101010101', 'DD7F121CA5015619', '4000000000000000'),
            array('0101010101010101', '2E8653104F3834EA', '2000000000000000'),
            array('0101010101010101', '8000000000000000', '95F8A5E5DD31D900'),
            array('0101010101010101', '4000000000000000', 'DD7F121CA5015619'),
            array('0101010101010101', '2000000000000000', '2E8653104F3834EA'),
            array('8001010101010101', '0000000000000000', '95A8D72813DAA94D'),
            array('4001010101010101', '0000000000000000', '0EEC1487DD8C26D5'),
            array('2001010101010101', '0000000000000000', '7AD16FFB79C45926'),
            array('1046913489980131', '0000000000000000', '88D55E54F54C97B4'),
            array('1007103489988020', '0000000000000000', '0C0CC00C83EA48FD'), 
            array('10071034C8980120', '0000000000000000', '83BC8EF3A6570183'),
            array('7CA110454A1A6E57', '01A1D6D039776742', '690F5B0D9A26939B'),
            array('0131D9619DC1376E', '5CD54CA83DEF57DA', '7A389D10354BD271'), 
            array('07A1133E4A0B2686', '0248D43806F67172', '868EBB51CAB4599A'),
        );
        foreach ($ret as &$case) {
            foreach ($case as &$row) {
                if (empty($row) || is_int($row)) continue;
                $temp = preg_replace('/\s/', '', $row);
                $temp = str_split($temp, 2);
                $row = '';
                foreach ($temp as $val) {
                    $chr = chr(hexdec($val));
                    $row .= $chr;
                }
                $row = base64_encode($row);
            }
        }
        return $ret;
    }
    
    /**
     * @return array The test vectors
     */
    public static function provideTestDecryptVectors() {
        $ret = array(
            array('8001010101010101', '95A8D72813DAA94D', '0000000000000000'),
            array('4001010101010101', '0EEC1487DD8C26D5', '0000000000000000'),
            array('2001010101010101', '7AD16FFB79C45926', '0000000000000000'),
        );
        foreach ($ret as &$case) {
            foreach ($case as &$row) {
                if (empty($row) || is_int($row)) continue;
                $temp = preg_replace('/\s/', '', $row);
                $temp = str_split($temp, 2);
                $row = '';
                foreach ($temp as $val) {
                    $chr = chr(hexdec($val));
                    $row .= $chr;
                }
                $row = base64_encode($row);
            }
        }
        return $ret;
    }
    
    /**
     * @dataProvider provideTestEncryptVectors
     */
    public function testEncrypt($key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\DES('des');
        $enc = $cipher->encryptBlock(base64_decode($data), base64_decode($key));
        $this->assertEquals($expected, base64_encode($enc));
    }
    
    /**
     * @dataProvider provideTestDecryptVectors
     */
    public function testDecrypt($key, $data, $expected) {
        $cipher = new \CryptLib\Cipher\Block\Implementation\DES('des');
        $enc = $cipher->decryptBlock(base64_decode($data), base64_decode($key));
        $this->assertEquals($expected, base64_encode($enc));
    }
    
    public function testBlockSize() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\DES('des');
        $this->assertEquals(8, $cipher->getBlockSize('foo'));
    }
    
    public function testGetCipher() {
        $cipher = new \CryptLib\Cipher\Block\Implementation\DES('des');
        $this->assertEquals('des', $cipher->getCipher());
    }

}
