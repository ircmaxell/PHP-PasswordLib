<?php

use CryptLibTest\Mocks\Random\Mixer;
use CryptLibTest\Mocks\Random\Source;

use CryptLib\Random\Generator;

class Vectors_Random_GeneratorTest extends PHPUnit_Framework_TestCase {

    public static function provideGenerateInt() {
        return array(
            // First, lets test each offset based range
            array(0, 7),
            array(0, 15),
            array(0, 31),
            array(0, 63),
            array(0, 127),
            array(0, 255),
            array(0, 511),
            array(0, 1023),
            // Let's try a range not starting at 0
            array(8, 15),
            // Let's try a range with a negative number
            array(-18, -11),
            // Let's try a non-power-of-2 range
            array(10, 100),
            // Finally, let's try two large numbers
            array(100000, 100007),
            array(100000000, 100002047),
            // Now, let's force a few loops by setting a valid offset
            array(0, 5, 2),
            array(0, 9, 5),
            array(0, 27, 4),
        );
    }

    /**
     * This test asserts that the algorithm that generates the integers does not
     * actually introduce any bias into the generated numbers.  If this test
     * passes, the generated integers from the generator will be as unbiased as
     * the sources that provide the data.
     *
     * @dataProvider provideGenerateInt
     */
    public function testGenerateInt($min, $max, $offset = 0) {
        $generator = $this->getGenerator($max - $min + $offset);
        for ($i = $max; $i >= $min; $i--) {
            $this->assertEquals($i, $generator->generateInt($min, $max));
        }
    }

    public function getGenerator($random) {
        $source1  = new Source(array(
            'generate' => function ($size) use (&$random) {
                $ret = pack('N', $random);
                $random--;
                return substr($ret, -1 * $size);
            }
        ));
        $sources = array($source1);
        $mixer   = new Mixer(array(
            'mix'=> function(array $sources) {
                if (empty($sources)) return '';
                return array_pop($sources);
            }
        ));
        return new Generator($sources, $mixer);
    }

}
