<?php
namespace SwaggerFaker\Faker;

use Faker\Provider\Base;
use Faker\Provider\Lorem;

class ObjectFaker implements \SwaggerFaker\FakesSwaggerData
{
    public function generate(\stdClass $schema)
    {
        $properties = isset($schema->properties) ? $schema->properties : new \stdClass();
        $propertyNames = $this->getProperties($schema);

        $dummy = new \stdClass();
        foreach ($propertyNames as $key) {
            if (isset($properties->{$key})) {
                $subschema = $properties->{$key};
            } else {
                $subschema = $this->getAdditionalPropertySchema($schema, $key) ?: $this->getRandomSchema();
            }

            $dummy->{$key} = (new \SwaggerFaker\Faker())->generate($subschema);
        }

        return $dummy;
    }
    
    /**
    * @return string[] Property names
    */
    protected function getProperties(\stdClass $schema)
    {
        $requiredKeys = isset($schema->required) ? $schema->required : [];
        $optionalKeys = isset($schema->properties) ? array_keys(get_object_vars($schema->properties)) : [];
        $maxProperties = isset($schema->maxProperties) ? $schema->maxProperties : count($optionalKeys) - count($requiredKeys);
        
        $pickSize = Base::numberBetween(0, min(count($optionalKeys), $maxProperties));
        $additionalKeys = $this->resolveDependencies($schema, Base::randomElements($optionalKeys, $pickSize));
        $propertyNames = array_unique(array_merge($requiredKeys, $additionalKeys));
        
        $additionalProperties = isset($schema->additionalProperties) ? $schema->additionalProperties : true;
        $patternProperties = isset($schema->patternProperties) ? $schema->patternProperties : new \stdClass();
        $minProperties = isset($schema->minProperties) ? $schema->minProperties : 0;
        $patterns = array_keys((array)$patternProperties);
        
        while (count($propertyNames) < $minProperties) {
            $name = $additionalProperties ? Lorem::word() : Lorem::regexify(Base::randomElement($patterns));
            if (!in_array($name, $propertyNames)) {
                $propertyNames[] = $name;
            }
        }

        return $propertyNames;
    }

    protected function getAdditionalPropertySchema(\stdClass $schema, $property)
    {
        $patternProperties = isset($schema->patternProperties) ? $schema->patternProperties : new \stdClass();
        $additionalProperties = isset($schema->additionalProperties) ? $schema->additionalProperties : true;
        
        foreach ($patternProperties as $pattern => $sub) {
            if (preg_match("/{$pattern}/", $property)) {
                return $sub;
            }
        }

        if (is_object($additionalProperties)) {
            return $additionalProperties;
        }
    }
    
    protected function getRandomSchema()
    {
        $fakerNames = array_keys(\SwaggerFaker\Faker::getFakers());
        
        $schema = new \stdClass();
        $schema->type = Base::randomElement($fakerNames);
        if ($schema->type === 'array') {
            $schema->items = $this->getRandomSchema();
        }
        return $schema;
    }
    
    protected function resolveDependencies(\stdClass $schema, array $keys)
    {
        $resolved = [];
        $dependencies = isset($schema->dependencies) ? $schema->dependencies : new \stdClass();

        foreach ($keys as $key) {
            $dependency = isset($dependencies->$key) ? $dependencies->$key : [];
            $resolved = array_merge($resolved, [$key], $dependency);
        }

        return $resolved;
    }
}
