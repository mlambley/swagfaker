<?php
namespace SwaggerFaker;

interface FakesSwaggerData
{
    /**
    * Create dummy data with JSON schema
    *
    * @param  \stdClass $schema Data structure writen in JSON Schema
    * @return mixed dummy data
    * @throws \Exception Throw when unsupported type specified
    */
    public function generate(\stdClass $schema);
}
