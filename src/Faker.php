<?php
namespace SwaggerFaker;

use Faker\Provider\Base;

class Faker implements FakesSwaggerData
{
    /**
     * Create dummy data with Swagger schema v2
     *
     * @param  \stdClass $schema Data structure writen in Swagger Schema v2
     * @return mixed dummy data
     * @throws \Exception Throw when unsupported type specified
     */
    public function generate(\stdClass $schema)
    {
        //Handles allOf, anyOf, oneOf
        $resolvedSchema = $this->resolveOf($schema);
        
        if (isset($resolvedSchema->enum)) {
            return $this->enum($resolvedSchema);
        } else {
            return $this->getFaker($resolvedSchema->type)->generate($resolvedSchema);
        }
    }
    
    protected function enum($schema)
    {
        return Base::randomElement($schema->enum);
    }
    
    protected function getFaker($type)
    {
        $instances = static::getFakers();
        if (!isset($instances[$type])) {
            throw new \Exception("Unsupported type: {$type}");
        }
        return $instances[$type];
    }
    
    public static function getFakers()
    {
        return [
            'array' => (new Faker\ArrayFaker()),
            'boolean' => (new Faker\BooleanFaker()),
            'integer' => (new Faker\IntegerFaker()),
            'number' => (new Faker\NumberFaker()),
            'null' => (new Faker\NullFaker()),
            'object' => (new Faker\ObjectFaker()),
            'string' => (new Faker\StringFaker())
        ];
    }
    
    protected function resolveOf(\stdClass $schema)
    {
        if (isset($schema->allOf)) {
            //The data must validate against all items in the schema, but this is not feasible. Instead, pick one to validate against.
            return Base::randomElement($schema->allOf);
        } elseif (isset($schema->anyOf)) {
            //The data must validate against any of the items in the schema, so pick one.
            return Base::randomElement($schema->anyOf);
        } elseif (isset($schema->oneOf)) {
            //The data must validate against precisely one of the items in the schema, but this is not feasible. Instead, pick one to validate against.
            return Base::randomElement($schema->oneOf);
        } else {
            return $schema;
        }
    }
}
