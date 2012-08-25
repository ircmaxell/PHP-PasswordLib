<?php

class Unit_Password_Implementation_Password_TestCase extends PHPUnit_Framework_TestCase {

    protected $class = '';

    public static function provideCreateTypes() {
        return array(
            array(1, true),
            array(1.0, true),
            array("foo", true),
            array(new CastTestCase, true),
            array(array(1), false),
            array(true, false),
            array(new StdClass, false),
        );
    }
    
    /**
     * @dataProvider provideCreateTypes
     */
    public function testCreateTypes($password, $valid) {
        if (!$valid) {
            $this->setExpectedException('DomainException');
        }
        $this->getPassword()->create($password);
    }

    /**
     * @dataProvider provideCreateTypes
     */
    public function testVerifyTypes($password, $valid) {
        $hash = $this->getPassword()->create('test');
        if (!$valid) {
            $this->setExpectedException('DomainException');
        }
        $this->getPassword()->verify($password, $hash);
    }

    
    protected function getPassword() {
        $class = $this->class;
        if ($class) {
            return new $class;
        }
        throw new Exception('Class not set!!!');
    }
    
}

class CastTestCase {
    public function __toString() {
        return 'foo';
    }
}