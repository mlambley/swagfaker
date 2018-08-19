<?php
namespace SwaggerFaker\Faker;

class NullFaker implements \SwaggerFaker\FakesSwaggerData
{
    public function generate(\stdClass $schema)
    {
        return null;
    }
}
