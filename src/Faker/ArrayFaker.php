<?php
namespace SwaggerFaker\Faker;

use Faker\Provider\Base;

class ArrayFaker implements \SwaggerFaker\FakesSwaggerData
{
    public function generate(\stdClass $schema)
    {
        if (!isset($schema->items)) {
            throw new \Exception("items is required when type is array");
        } elseif (!is_object($schema->items)) {
            throw new \Exception("items for type:array must be an object");
        }

        $dummies = [];
        $minItems = isset($schema->minItems) ? $schema->minItems : 0;
        $maxItems = isset($schema->maxItems) ? $schema->maxItems : 30; //Arbitrary maximum
        
        $itemSize = Base::numberBetween($minItems, $maxItems);
        
        for ($i = 0; $i < $itemSize; $i++) {
            $dummies[] = (new \SwaggerFaker\Faker())->generate($schema->items);
        }
        
        if (isset($schema->uniqueItems) && $schema->uniqueItems === true) {
            return array_unique($dummies);
        } else {
            return $dummies;
        }
    }
}
