<?php
namespace SwaggerFaker\Faker;

use Faker\Provider\Base;

class BooleanFaker implements \SwaggerFaker\FakesSwaggerData
{
    public function generate(\stdClass $schema)
    {
        return Base::randomElement([true, false]);
    }
}
