<?php
namespace SwaggerFaker\Generator;

interface GeneratesBase64
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
     *   )?
     * $/
     *
     * @return string
     */
    public function generate();
}
