<?php
namespace SwaggerFaker\Faker;

use Faker\Provider\Base;

class IntegerFaker extends NumberBase
{
    public function generate(\stdClass $schema)
    {
        $minimum = $this->getMinimum($schema);
        $maximum = $this->getMaximum($schema);
        $multipleOf = $this->getMultipleOf($schema);
        
        //To get a number which is both between the minimum/maximum range, and a multiple of the multipleOf number,
        //first divide minimum and maximum by the multipleOf number, then get a random number, then multiply by the multipleOf again.
        
        //Take a ceiling of the minimum number, and a floor of the maximum number.
        //Reason being, for example, if we want a random number between 5 and 11 which is multiple of 2,
        //then we want:
        //   numberBetween(ceil(2.5), floor(5.5)) * 2
        // = numberBetween(3, 5) * 2
        // = either 6, 8 or 10.
        $adjustedMinimum = ceil($minimum / $multipleOf);
        $adjustedMaximum = floor($maximum / $multipleOf);
        $adjustedRandomNumber = Base::numberBetween($adjustedMinimum, $adjustedMaximum);

        return $adjustedRandomNumber * $multipleOf;
    }
}
