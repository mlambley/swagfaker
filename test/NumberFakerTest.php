<?php

namespace SwaggerFaker\Test;

class NumberFakerTest extends TestCase
{
    public function testGetMaximumMustReturnMaximumMinusOneIfExclusiveMaximumTrue()
    {
        $maximum = 300;
        $schema = (object)['exclusiveMaximum' => true, 'maximum' => $maximum];

        $actual = $this->performMethod('getMaximum', [$schema]);

        // -1 mean exclusive
        $this->assertSame($actual, $maximum - 1);
    }

    public function testGetMaximumMustReturnMaximumIfExclusiveMaximumFalse()
    {
        $maximum = 300;
        $schema = (object)['exclusiveMaximum' => false, 'maximum' => $maximum];

        $actual = $this->performMethod('getMaximum', [$schema]);

        $this->assertSame($actual, $maximum);
    }

    public function testGetMaximumMustReturnMaximumIfExclusiveMaximumAbsent()
    {
        $maximum = 300;
        $schema = (object)['maximum' => $maximum];

        $actual = $this->performMethod('getMaximum', [$schema]);

        $this->assertSame($actual, $maximum);
    }

    public function testGetMinimumMustReturnMinimumMinusOneIfExclusiveMinimumTrue()
    {
        $minimum = 300;
        $schema = (object)['exclusiveMinimum' => true, 'minimum' => $minimum];

        $actual = $this->performMethod('getMinimum', [$schema]);

        // +1 mean exclusive
        $this->assertSame($actual, $minimum + 1);
    }

    public function testGetMinimumMustReturnMinimumIfExclusiveMinimumFalse()
    {
        $minimum = 300;
        $schema = (object)['exclusiveMinimum' => false, 'minimum' => $minimum];

        $actual = $this->performMethod('getMinimum', [$schema]);

        $this->assertSame($actual, $minimum);
    }

    public function testGetMinimumMustReturnMinimumIfExclusiveMinimumAbsent()
    {
        $minimum = 300;
        $schema = (object)['minimum' => $minimum];

        $actual = $this->performMethod('getMinimum', [$schema]);

        $this->assertSame($actual, $minimum);
    }

    public function testGetMultipleOfMustReturnValueIfPresent()
    {
        $expected = 7;
        $schema = (object)['multipleOf' => $expected];

        $actual = $this->performMethod('getMultipleOf', [$schema]);

        $this->assertSame($actual, $expected);
    }

    public function testGetMultipleOfMustReturnOneIfAbsent()
    {
        $expected = 1;
        $schema = (object)[];

        $actual = $this->performMethod('getMultipleOf', [$schema]);

        $this->assertSame($actual, $expected);
    }
    
    protected function performMethod($method, $args)
    {
        $object = new \SwaggerFaker\Faker\NumberFaker();
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}
