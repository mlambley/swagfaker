<?php
namespace SwaggerFaker\Generator;

class Base64 implements GeneratesBase64
{
    /**
     * Returns random data in base 64 format.
     * Needs to fit the regex
     * /^
     *   (
     *     [A-Za-z0-9+\x2F]{4}
     *   )*
     *   (
     *       [A-Za-z0-9+\x2F]{4}
     *     | [A-Za-z0-9+\x2F]{3}=
     *     | [A-Za-z0-9+\x2F]{2}==
     *   )
     * $/
     *
     * @return string
     */
    public function generate()
    {
        //Work out how many of the first part we want, then decide which of the optional last parts we want.
        $numberOfFirstPart = mt_rand(0, 1000);
        $whichLastPart = mt_rand(0, 3);

        $base64String = '';
        for ($i=0; $i<$numberOfFirstPart; $i++) {
            //Each block contains 4 base64 chars.
            $base64String .= $this->getChar() . $this->getChar() . $this->getChar() . $this->getChar();
        }

        if ($whichLastPart === 0) {
            //It's optional, so do nothing.
        } elseif ($whichLastPart === 1) {
            //Another 4 base64 chars.
            $base64String .= $this->getChar() . $this->getChar() . $this->getChar() . $this->getChar();
        } elseif ($whichLastPart === 2) {
            //3 base64 chars and an equals.
            $base64String .= $this->getChar() . $this->getChar() . $this->getChar() . '=';
        } else {
            //2 base64 chars and 2 equals.
            $base64String .= $this->getChar() . $this->getChar() . '==';
        }

        return $base64String;
    }

    protected function getChar()
    {
        //26 lowercase + 26 uppercase + 10 digits + "+" + "/" = 64 possibilities.
        $digit = mt_rand(0, 63);

        if ($digit < 26) {
            //0-25: lowercase
            return chr(97 + $digit);
        } elseif ($digit < 52) {
            //26-51: uppercase
            return chr(39 + $digit);
        } elseif ($digit < 62) {
            //52-61: digit
            return $digit - 52;
        } elseif ($digit === 62) {
            //62: +
            return "+";
        } elseif ($digit === 63) {
            //63: /
            return "/";
        }
    }
}
