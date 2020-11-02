# Swagfaker
Generates fake data which will validate against your Swagger 2.0 schema.

## How to install
```
composer require mlambley/swagfaker:^1.0
```

## What is Swagger?
Swagger 2.0 (aka Open API 2.0) defines the structure of your API, including end points and the structure of input and output data. See [their website](https://swagger.io/) for more information.

## What is Swagfaker?
Swagfaker allows you to generate fake data based upon your existing Swagger 2.0 specification. You can use it to generate data to be sent to your API during acceptance testing.

## Example
Let's say your Swagger schema looks like this:
```json
{
  "type": "object",
  "required": [
    "Name",
    "DateOfBirth",
    "Identifier",
    "VisitCount"
  ],
  "properties": {
    "Name": {
      "type": "string"
    },
    "DateOfBirth": {
      "type": "string",
      "format": "date"
    },
    "Identifier": {
      "type": "string",
      "pattern": "^[0-9]{3}-[0-9]{4}$"
    },
    "VisitCount": {
      "type": "integer",
      "minimum": 0
    }
  }
}
```

Then generate fake data using:
```php
$values = (new \SwaggerFaker\Faker())->generate($schema);
```

Your values now might look like this:
```
object(stdClass) {
  "Name" => "Quod."
  "DateOfBirth" => "1971-08-20"
  "Identifier" => "037-1259"
  "VisitCount" => 1463093889
}
```

## Issues?
Log a [github issue](https://github.com/mlambley/swagfaker/issues). Your assistance is appreciated.

## Credit
Give some love to [Leko](https://github.com/Leko) for their [JSON Schema Faker](https://github.com/Leko/php-json-schema-faker) which this library was originally copied from.
