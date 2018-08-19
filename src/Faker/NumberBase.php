<?php
namespace SwaggerFaker\Faker;

abstract class NumberBase implements \SwaggerFaker\FakesSwaggerData
{
    protected function getMinimum($schema)
    {
        if (isset($schema->minimum)) {
            //if "exclusiveMinimum" is not present, or has boolean value false, then the instance is valid if it is greater than, or equal to, the value of "minimum";
            //if "exclusiveMinimum" is present and has boolean value true, the instance is valid if it is strictly greater than the value of "minimum".
            if (isset($schema->exclusiveMinimum) && $schema->exclusiveMinimum === true) {
                return $schema->minimum + 1;
            } else {
                return $schema->minimum;
            }
        } elseif (isset($schema->format) && $schema->format === 'int32') {
            return -2147483648;
        } else {
            return -mt_getrandmax();
        }
    }
    
    protected function getMaximum($schema)
    {
        if (isset($schema->maximum)) {
            //if "exclusiveMaximum" is not present, or has boolean value false, then the instance is valid if it is lower than, or equal to, the value of "maximum";
            //if "exclusiveMaximum" has boolean value true, the instance is valid if it is strictly lower than the value of "maximum".
            if (isset($schema->exclusiveMaximum) && $schema->exclusiveMaximum === true) {
                return $schema->maximum - 1;
            } else {
                return $schema->maximum;
            }
        } elseif (isset($schema->format) && $schema->format === 'int32') {
            return 2147483647;
        } else {
            return mt_getrandmax();
        }
    }
    
    protected function getMultipleOf($schema)
    {
        return (isset($schema->multipleOf)) ? $schema->multipleOf : 1;
    }
}
