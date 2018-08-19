<?php
namespace SwaggerFaker\Faker;

use Faker\Provider\Base;
use Faker\Provider\DateTime;
use Faker\Provider\Lorem;

class StringFaker implements \SwaggerFaker\FakesSwaggerData
{
    public function generate(\stdClass $schema)
    {
        if (isset($schema->format)) {
            return $this->formattedValue($schema);
        } elseif (isset($schema->pattern)) {
            return Lorem::regexify($schema->pattern);
        } else {
            return $this->randomString($schema);
        }
    }
    
    protected function randomString($schema)
    {
        $min = isset($schema->minLength) ? $schema->minLength : 1;
        $max = isset($schema->maxLength) ? $schema->maxLength : max(5, $min + 1);
        $lorem = Lorem::text($max);

        if (mb_strlen($lorem) < $min) {
            $lorem = str_repeat($lorem, $min);
        }

        return mb_substr($lorem, 0, $max);
    }
    
    protected function formattedValue($schema)
    {
        switch ($schema->format) {
            case 'byte':
                return $this->byteValue($schema);
            case 'binary':
                return $this->binaryValue($schema);
            case 'date':
                return $this->dateValue($schema);
            case 'date-time':
                return $this->dateTimeValue($schema);
            case 'password':
                return $this->passwordValue($schema);
        }
        
        //The format property is an open string-valued property, and can have any value to support documentation needs.
        return $this->randomString($schema);
    }
    
    protected function byteValue($schema)
    {
        return $this->getBase64FakerInstance()->generate();
    }
    
    protected function binaryValue($schema)
    {
        //Arbitrary maximum of 2^20 bytes.
        return openssl_random_pseudo_bytes(Base::numberBetween(0, pow(2, 20)));
    }
    
    protected function dateValue($schema)
    {
        return $this->getDateTimeInstance()->format('Y-m-d');
    }
    
    protected function dateTimeValue($schema)
    {
        return $this->getDateTimeInstance()->format(DATE_RFC3339);
    }
    
    protected function passwordValue($schema)
    {
        return $this->randomString($schema);
    }
    
    /**
     * @return \SwaggerFaker\Generator\GeneratesBase64
     */
    protected function getBase64FakerInstance()
    {
        return new \SwaggerFaker\Generator\Base64();
    }
    
    /**
     * @return \DateTime
     */
    protected function getDateTimeInstance()
    {
        return DateTime::dateTime();
    }
}
