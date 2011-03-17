<?php

use CryptLibTest\Mocks\Random\Mixer;
use CryptLibTest\Mocks\Random\Source;

use CryptLib\Random\Generator;

class Unit_Random_GeneratorTest extends PHPUnit_Framework_TestCase {

    protected $generator = null;

    public static function provideGenerate() {
        return array(
            array(0, ''),
            array(1, chr(0)),
            array(2, chr(1) . chr(1)),
            array(3, chr(2) . chr(0) . chr(2)),
            array(4, chr(3) . chr(3) . chr(3) . chr(3)),
        );
    }

    public static function provideGenerateInt() {
        return array(
            array(1, 1, 1),
            array(0, 1, 0),
            array(0, 255, 0),
            array(400, 655, 400),
            array(0, 65535, 257),
            array(65535, 131070, 65792),
            array(0, 16777215, (2<<16) + 2),
            array(-10, 0, -10),
            array(-655, -400, -655),
            array(-131070, -65535, -130813),
        ); 
    }

    public static function provideGenerateIntRangeTest() {
        return array(
            array(0, 0),
            array(0, 1),
            array(1, 10000),
            array(100000, \PHP_INT_MAX),
        );
    }

    public static function provideGenerateStringTest() {
        return array(
            array(0, 'ab', ''),
            array(1, 'ab', 'a'),
            array(1, 'a', ''),
            array(2, 'ab', 'aa'),
            array(3, 'abc', 'aaa'),
            array(8, '0123456789abcdef', '03030303'),
            array(16, '0123456789abcdef', '0707070707070707'),
            array(16, '', '2MIb2Mc30Mcb2MIb'),
        );
    }

    public function setUp() {
        $source1  = new Source(array(
            'generate' => function ($size) {
                $r = '';
                for ($i = 0; $i < $size; $i++) {
                    $r .= chr($i);
                }
                return $r;
            }
        ));
        $source2  = new Source(array(
            'generate' => function ($size) {
                $r = '';
                for ($i = $size - 1; $i >= 0; $i--) {
                    $r .= chr($i);
                }
                return $r;
            }
        ));
        $sources = array($source1, $source2);
        $mixer   = new Mixer(array(
            'mix'=> function(array $sources) {
                if (empty($sources)) return '';
                $start = array_pop($sources);
                return array_reduce(
                    $sources,
                    function($el1, $el2) {
                        return $el1 ^ $el2;
                    },
                    $start
                );
            }
        ));
        $this->generator = new Generator($sources, $mixer);
    }

    /**
     */
    public function testConstruct() {
        $obj = new Generator(array(new Source), new Mixer);
        $this->assertTrue($obj instanceof \CryptLib\Random\Generator);
    }

    /**
     * @covers CryptLib\Random\Generator::addSource
     */
    public function testAddSource() {
        $obj = new Generator(array(), new Mixer);
        $r = new ReflectionObject($obj);
        $property = $r->getProperty('sources');
        $property->setAccessible(true);
        $this->assertEquals(array(), $property->getValue($obj));
        $source = new Source;
        $this->assertSame($obj, $obj->addSource($source));
        $this->assertEquals(array($source), $property->getValue($obj));
    }

    /**
     * @dataProvider provideGenerate
     */
    public function testGenerate($size, $expect) {
        $this->assertEquals($expect, $this->generator->generate($size));
    }

    /**
     * @dataProvider provideGenerateInt
     */
    public function testGenerateInt($min, $max, $expect) {
        $this->assertEquals($expect, $this->generator->generateInt($min, $max));
    }

    /**
     * @dataProvider provideGenerateIntRangeTest
     */
    public function testGenerateIntRange($min, $max) {
        $n = $this->generator->generateInt($min, $max);
        $this->assertTrue($min <= $n);
        $this->assertTrue($max >= $n);
    }

    /**
     * @dataProvider provideGenerateStringTest
     */
    public function testGenerateString($length, $chars, $expected) {
        $n = $this->generator->generateString($length, $chars);
        $this->assertEquals($expected, $n);
    }
}
