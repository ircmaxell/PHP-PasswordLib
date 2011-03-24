<?php

use CryptLibTest\Mocks\Random\Mixer;
use CryptLibTest\Mocks\Random\Source;

use CryptLib\Core\Strength\Low;
use CryptLib\Core\Strength\Medium;
use CryptLib\Core\Strength\High;
use CryptLibTest\Mocks\Core\Strength as MockStrength;

use CryptLib\Random\Factory;

class Unit_Random_FactoryTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers CryptLib\Random\Factory::__construct
     * @covers CryptLib\Random\Factory::loadMixers
     * @covers CryptLib\Random\Factory::loadSources
     * @covers CryptLib\Random\Factory::registerMixer
     * @covers CryptLib\Random\Factory::registerSource
     */
    public function testConstruct() {
        $factory = new Factory;
        $this->assertTrue($factory instanceof CryptLib\Random\Factory);
    }

    /**
     * @covers CryptLib\Random\Factory::registerSource
     * @covers CryptLib\Random\Factory::getSources
     */
    public function testRegisterSource() {
        $factory = new Factory;
        $factory->registerSource('mock', 'CryptLibTest\Mocks\Random\Source');
        $test = $factory->getSources();
        $this->assertTrue(in_array('CryptLibTest\Mocks\Random\Source', $test));
    }

    /**
     * @covers CryptLib\Random\Factory::registerSource
     * @covers CryptLib\Random\Factory::getSources
     * @expectedException InvalidArgumentException
     */
    public function testRegisterSourceFail() {
        $factory = new Factory;
        $factory->registerSource('mock', 'stdclass');
    }


    /**
     * @covers CryptLib\Random\Factory::registerMixer
     * @covers CryptLib\Random\Factory::getMixers
     */
    public function testRegisterMixer() {
        $factory = new Factory;
        $factory->registerMixer('mock', 'CryptLibTest\Mocks\Random\Mixer');
        $test = $factory->getMixers();
        $this->assertTrue(in_array('CryptLibTest\Mocks\Random\Mixer', $test));
    }

    /**
     * @covers CryptLib\Random\Factory::registerMixer
     * @covers CryptLib\Random\Factory::getMixers
     * @expectedException InvalidArgumentException
     */
    public function testRegisterMixerFail() {
        $factory = new Factory;
        $factory->registerMixer('mock', 'stdclass');
    }

    /**
     * @covers CryptLib\Random\Factory::getLowStrengthGenerator
     * @covers CryptLib\Random\Factory::getGenerator
     * @covers CryptLib\Random\Factory::findMixer
     */
    public function testGetLowStrengthGenerator() {
        $factory = new Factory;
        $generator = $factory->getLowStrengthGenerator();
        $this->assertTrue($generator instanceof CryptLib\Random\Generator);
        $mixer = call_user_func(array(
            get_class($generator->getMixer()),
            'getStrength'
        ));
        $this->assertTrue($mixer->compare(new Low) <= 0);
        foreach ($generator->getSources() as $source) {
            $strength = call_user_func(array(get_class($source), 'getStrength'));
            $this->assertTrue($strength->compare(new Low) >= 0);
        }
    }

    /**
     * @covers CryptLib\Random\Factory::getMediumStrengthGenerator
     * @covers CryptLib\Random\Factory::getGenerator
     * @covers CryptLib\Random\Factory::findMixer
     */
    public function testGetMediumStrengthGenerator() {
        $factory = new Factory;
        $generator = $factory->getMediumStrengthGenerator();
        $this->assertTrue($generator instanceof CryptLib\Random\Generator);
        $mixer = call_user_func(array(
            get_class($generator->getMixer()),
            'getStrength'
        ));
        $this->assertTrue($mixer->compare(new Medium) <= 0);
        foreach ($generator->getSources() as $source) {
            $strength = call_user_func(array(get_class($source), 'getStrength'));
            $this->assertTrue($strength->compare(new Medium) >= 0);
        }
    }

    /**
     * @covers CryptLib\Random\Factory::getHighStrengthGenerator
     * @covers CryptLib\Random\Factory::getGenerator
     * @covers CryptLib\Random\Factory::findMixer
     */
    public function testGetHighStrengthGenerator() {
        $factory = new Factory;
        $generator = $factory->getHighStrengthGenerator();
        $this->assertTrue($generator instanceof CryptLib\Random\Generator);
        $mixer = call_user_func(array(
            get_class($generator->getMixer()),
            'getStrength'
        ));
        $this->assertTrue($mixer->compare(new High) <= 0);
        foreach ($generator->getSources() as $source) {
            $strength = call_user_func(array(get_class($source), 'getStrength'));
            $this->assertTrue($strength->compare(new High) >= 0);
        }
    }

    /**
     * @covers CryptLib\Random\Factory::getGenerator
     * @covers CryptLib\Random\Factory::findMixer
     * @expectedException RuntimeException
     */
    public function testGetGeneratorFail() {
        $factory = new Factory;
        $mock = new MockStrength(99);
        $generator = $factory->getGenerator($mock);
    }

}
