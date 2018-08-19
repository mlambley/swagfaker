<?php

namespace SwaggerFaker\Test;

use Swagception\Validator\Validator;

class StringFakerTest extends TestCase
{
    /**
     * @dataProvider getFormats
     */
    public function testGetFormattedValueMustReturnValidValue($format)
    {
        $schema = (object)['type' => 'string', 'format' => $format];
        $validator = new Validator();
        
        $actual = $this->performMethod('formattedValue', [$schema]);
        
        try {
            $validator->validate($schema, $actual);
            $this->assertTrue(true);
        } catch (\Swagception\Exception\ValidationException $e) {
            $this->assertTrue(false, $e->getMessage() . PHP_EOL . json_encode($actual, JSON_PRETTY_PRINT));
        }
    }

    /**
     * @see testGetFormattedValueMustReturnValidValue
     */
    public function getFormats()
    {
        return [
            ['byte'],
            ['binary'],
            ['date'],
            ['date-time'],
            ['password']
        ];
    }
    
    protected function performMethod($method, $args)
    {
        $object = new \SwaggerFaker\Faker\StringFaker();
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $args);
    }
}
