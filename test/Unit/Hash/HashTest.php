<?php

use CryptLib\Hash\Hash;

class Unit_Hash_HashTest extends PHPUnit_Framework_TestCase {
    protected $oldHashes = array();

    public static function provideTestOutputSize() {
        $ret = array();
        foreach (hash_algos() as $hash) { 
            $ret[] = array($hash, strlen(hash($hash, '', true)));
        }
        return $ret;
    }
    
    /**
     * @dataProvider provideTestOutputSize
     */
    public function testOutputSize($algo, $expected) {
        $this->assertEquals($expected, Hash::getHashSize($algo));
        $this->assertEquals($expected * 8, Hash::getHashSizeInBits($algo));
    }
    
    public function testGetBlockSize() {
        $this->assertEquals(2048 / 8, Hash::getBlockSize('test'));
    }
    
    public function testGetBlockSizeInBits() {
        $this->assertEquals(2048, Hash::getBlockSizeInBits('test'));
    }
    
    public function testGetHashSize() {
        $this->assertEquals(4096 / 8, Hash::getHashSize('test'));
    }
    
    public function testGetHashSizeInBits() {
        $this->assertEquals(4096, Hash::getHashSizeInBits('test'));
    }
    
    public function testIsAvailable() {
        $this->assertFalse(Hash::isAvailable('test'));
    }
    
    public function testIsSecure() {
        $this->assertEquals('yes', Hash::isSecure('test'));
    }
    
    public function setUp() {
        $r = new ReflectionProperty('CryptLib\\Hash\\Hash', 'hashInfo');
        $r->setAccessible(true);
        $prop = $r->getValue(null);
        $this->oldHashes = $prop;
        $prop['test'] = array(
            'HashSize' => 4096,
            'BlockSize' => 2048,
            'secure' => 'yes',
        );
        $r->setValue(null, $prop);
    }
    
    public function tearDown() {
        $r = new ReflectionProperty('CryptLib\\Hash\\Hash', 'hashInfo');
        $r->setAccessible(true);
        $r->setValue(null, $this->oldHashes);
    }
}
