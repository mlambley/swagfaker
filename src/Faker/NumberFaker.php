<?php
namespace SwaggerFaker\Faker;

use Faker\Provider\Base;

class NumberFaker extends NumberBase
{
    public function generate(\stdClass $schema)
    {
        $minimum = $this->getMinimum($schema);
        $maximum = $this->getMaximum($schema);
        $multipleOf = $this->getMultipleOf($schema);
        
        //To get a number which is both between the minimum/maximum range, and a multiple of the multipleOf number,
        //first divide minimum and maximum by the multipleOf number, then get a random number, then multiply by the multipleOf again.
        $adjustedMinimum = $minimum / $multipleOf;
        $adjustedMaximum = $maximum / $multipleOf;
        $adjustedRandomNumber = Base::randomFloat(null, $adjustedMinimum, $adjustedMaximum);
        
        if ($multipleOf !== 1) {
            //Only necessary to round it when we're making it a multiple.
            $adjustedRandomNumber = floor($adjustedRandomNumber);
        }
        
        return $adjustedRandomNumber * $multipleOf;
    }
}
