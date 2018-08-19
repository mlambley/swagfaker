<?php

namespace SwaggerFaker\Test;

use SwaggerFaker\Faker;
use Swagception\Validator\Validator;

class FakerTest extends TestCase
{
    /**
     * @dataProvider getTypes
     */
    public function testFakeMustReturnValidValue($type)
    {
        $schema = $this->getFixture($type);
        $validator = new Validator();

        $actual = (new Faker())->generate($schema);
        
        try {
            $validator->validate($schema, $actual);
            $this->assertTrue(true);
        } catch (\Swagception\Exception\ValidationException $e) {
            $this->assertTrue(false, $e->getMessage() . PHP_EOL . json_encode($actual, JSON_PRETTY_PRINT));
        }
    }

    public function getTypes()
    {
        return [
            ['boolean'],
            ['null'],
            ['integer'],
            ['number'],
            ['string'],
            ['array'],
            ['object'],
            ['combining']
        ];
    }

    /**
     * @expectedException Exception
     */
    public function testFakeMustThrowExceptionIfInvalidType()
    {
        (new Faker())->generate((object)['type' => 'xxxxx']);
    }
}
