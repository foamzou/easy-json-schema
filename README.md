English | [简体中文](./README.zh-CN.md)

<h1 align="center">Easy Json Schema</h1>

<div align="center">

* Use php object to define Json-Schema easily (support draft-07 and draft-06)
* Use [opis/json-schema](https://github.com/opis/json-schema) as validator

</div>


## Advantage

* Easy to define，avoid complex json-schema
* More readability
* Easier to maintain

## Install
`composer require "foamzou/easy-json-schema"`

## Usage

```
use Foamzou\EasyJsonSchema\Manager\Validator;
use Foamzou\EasyJsonSchema\Manager\Parser;
use Foamzou\EasyJsonSchema\Type\{
    Str, Integer
};

// define a schema
$schema = new Obj([
              'name' => (new Integer)->min(2)->max(5),
              'age' => (new Str)->max(120),
          ]);

$data = [
    'name' => 'foam',
    'age' => 18,
];

// generate json-schema
$jsonSchema = Parser::run($schema);

// check is valid with data，errorMessage will return while valid failed
$bool = Validator::getInstance()->isValid($jsonSchema, $data, $errorMessage);
```


## Define Schema
Here's a comparison with json-schema. You can see that the definition of using php objects is simpler, more readable, and easier to maintain.

Easy-Json-Schema
```
new Obj([
    'name'  => (new Integer)->min(2)->max(5),
    'age'   => (new Str)->min(16)->max(120),
    'skill' => (new Arr)->items((new Obj([
        'name'  => (new Str())->min(1)->max(64),
        'value' => (new Num())->min(0)->max(100)->multipleOf(0.25),
    ]))->requiredAll()->additionalProp(false)),
]);

```

Json-Schema
```
{
    "type": "object",
    "properties": {
        "name": {
            "type": "integer",
            "minimum": 2,
            "maximum": 5
        },
        "age": {
            "type": "string",
            "minLength": 16,
            "maxLength": 120
        },
        "skill": {
            "type": "array",
            "items": {
                "type": "object",
                "properties": {
                    "name": {
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 64
                    },
                    "value": {
                        "type": "number",
                        "minimum": 0,
                        "maximum": 100,
                        "multipleOf": 0.25
                    }
                },
                "required": [
                    "name",
                    "value"
                ],
                "additionalProperties": false
            }
        }
    }
}
```

### Data type
```
use Foamzou\EasyJsonSchema\Type\{
    Str, Integer, Num, Obj, Boolean, Arr, Nul
};
```
#### String
`(new Str)->min(1)->max(23)->pattern('/regex/')->contentEncoding()->contentMediaType()`

#### Number
```
(new Num)->min(1)->max(20); // >=1 and <=20
(new Num)->exMin(1)->exMax(20)->multipleOf(5);// >1 and <20 and divisible by 5
```

#### Integer
```
(new Integer)->min(1)->max(20); // >=1 and <=20
(new Integer)->exMin(1)->exMax(20)->multipleOf(5);// >1 and <20 and divisible by 5
```

#### Boolean
`new Boolean`

#### Null
`new Nul`

#### Array
```
(new Arr)->items(new obj([
    'name'  => new Integer,
    'age'   => new Str,
]))->min('Array length minimum')
    ->max('Array length minimum')
    ->uniq('bool: Whether the element is required to be unique')
    ->additionalItems('extra element')
    ->contains('contains elements');
```

#### Object
```
(new Obj([
    'name' => new Str,
    'age' => new Integer,
    'childObj' => new Obj([ // can be nested
        'newName' => new Str,
        'newAge' => new Integer,
    ])
]))->required(['name', 'age']);
```

### keyword
#### Enum
```
new Enum([1, 2, 3]);
```

#### Constant
```
new Constant('mustBeMe');
```


#### AnyOf, OneOf, AllOf
```
// new objects
new OneOf([
    new Integer,
    new Str,
]);

// call method
(new Arr())->items(new Integer)->oneOf([
    [
        "items"=> [
            "exclusiveMinimum"=> 0
        ]
    ],
    [
        "items"=> [
            "exclusiveMaximum"=> 0
        ]
    ],
    [
        "items"=> [
            "const"=> 0
        ]
    ]
]);


```

#### not
```
new Not(new Str)
```

#### if.then.else
```
// new objects
(new Kif(new Integer()))
    ->then(["minLength" => 3])
    ->else(["const" => 0]);

// call method
(new Obj)->if([
    'properties' => [
        'gender' => new Constant('female')
    ]
])->then([
    'properties' => [
        'gender'    => new Constant('female'),
        'age'       => (new Integer)->min(16),
    ]
])->else([
    'properties' => [
        'gender'    => new Constant('male'),
        'age'       => (new Integer)->min(18),
    ]
]);
```

#### Reuse Schema
When a schema need to reuse the other schema, you can use `require`
```
new Obj([
       'name' => (new Str()),
       'location' => require __DIR__ . '/Location.php',
 ]);
```



#### Common Method
All objects have a `desc` method to describes the object
```
(new Obj([
    'name' => (new Str)->desc('username'),
    'age' => (new Integer)->desc('user age'),
]))->desc('I am a request object');

```
The object which base `Type` has `default` method to set default value
```
(new Str)->default('jay');
```

## Compiling EasyJsonSchema to Json Schema
```
use Foamzou\EasyJsonSchema\Manager\Parser;

$schema = (new Obj([
              'name' => (new Str)->desc('username'),
              'age' => (new Integer)->desc('user age'),
          ]))->desc('I am a request object');

$jsonSchema = Parser::run($schema);
```

## Check data and get error information
If data does not match the schema, `isValid` will return false, otherwise it will be true
```
use Foamzou\EasyJsonSchema\Manager\Validator;

$validator = Validator::getInstance();
$isValid = $validator->isValid($jsonSchema, json_encode($data), $errMessage, $errList);
```

$errMessage and $errList has value while `isValid` return false

$errMessage is a semantically string, $errList is an array of items with Opis\JsonSchema\ValidationError

You can use $errList to build the error message you want when $errMessage does not you hope