[English](./README.md) | 简体中文

<h1 align="center">Easy Json Schema</h1>

* 使用php对象，描述Json-Schema变得更容易（支持draft-07 和 draft-06）
* 使用[opis/json-schema](https://github.com/opis/json-schema)作为校验器

## 好处
* 定义容易，不用写复杂的json
* 定义更有可读性

## 安装
`composer require "foamzou/easy-json-schema"`

## 基本使用

```
use Foamzou\EasyJsonSchema\Manager\Validator;
use Foamzou\EasyJsonSchema\Manager\Parser;
use Foamzou\EasyJsonSchema\Type\{
    Str, Integer
};

// 定义一个schema
$schema = new Obj([
              'name' => (new Integer)->min(2)->max(5),
              'age' => (new Str)->max(120),
          ]);

$data = [
    'name' => 'foam',
    'age' => 18,
];

// 生成json-schema
$jsonSchema = Parser::run($schema);

//校验数据合法性，如果校验失败，errorMessage将返回具体信息
$bool = Validator::getInstance()->isValid($jsonSchema, $data, $errorMessage);
```


## Schema的定义
下面是与json-schema的对比，可以看到使用php对象的定义会更简单、更有可读性且更容易维护

php版
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

### 数据类型
```
use Foamzou\EasyJsonSchema\Type\{
    Str, Integer, Num, Obj, Boolean, Arr, Nul
};
```
#### 字符串
`(new Str)->min(最短长度)->max(最大长度)->pattern('/正则/')->contentEncoding(编码类型)->contentMediaType(媒体类型)`

#### 数字
```
(new Num)->min(1)->max(20); // >=1 且 <=20
(new Num)->exMin(1)->exMax(20)->multipleOf(5);// >1 且 <20 且 被5整除
```

#### 整型
```
(new Integer)->min(1)->max(20); // >=1 且 <=20
(new Integer)->exMin(1)->exMax(20)->multipleOf(5);// >1 且 <20 且 被5整除
```

#### 布尔
`new Boolean`

#### 空值
`new Nul`

#### 数组
```
(new Arr)->items(new obj([
    'name'  => new Integer,
    'age'   => new Str,
]))->min('数组长度最小值')
    ->max('数组长度最大值')
    ->uniq('bool:是否要求元素唯一')
    ->additionalItems('额外元素')
    ->contains('包含元素');
```

#### 对象
```
(new Obj([
    'name' => new Str,
    'age' => new Integer,
    'childObj' => new Obj([ // 对象可嵌套
        'newName' => new Str,
        'newAge' => new Integer,
    ])
]))->required(['name', 'age']);
```

### 关键字
#### 枚举
```
new Enum([1, 2, 3]);
```

#### 常量
```
new Constant('mustBeMe');
```


#### AnyOf, OneOf, AllOf
```
// 如果差异在于不同的数据类型，可用new对象的方式
new OneOf([
    new Integer,
    new Str,
]);

// 如果在同一个数据类型的不同属性，则调用方法
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
// 方式1，适用于new if的场景
(new Kif(new Integer()))
    ->then(["minLength" => 3])
    ->else(["const" => 0]);

// 方式2，适用于基本类型内部的链式场景
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

#### 复用schema
在开发中，经常会定义公共的实体。当其他地方需要复用同一个schema时，可使用require引入
```
new Obj([
       'name' => (new Str()),
       'location' => require __DIR__ . '/Location.php',
 ]);
```



#### 公共方法
所有对象都有desc方法，用于描述该对象
```
(new Obj([
    'name' => (new Str)->desc('用户姓名'),
    'age' => (new Integer)->desc('用户年龄'),
]))->desc('我是一个请求体');

```
基于Type的对象，都有default方法，用于设置默认值
```
(new Str)->default('jay');
```

## 将EasyJsonSchema编译为Json Schema
```
use Foamzou\EasyJsonSchema\Manager\Parser;

$schema = (new Obj([
              'name' => (new Str)->desc('用户姓名'),
              'age' => (new Integer)->desc('用户年龄'),
          ]))->desc('我是一个请求体');

$jsonSchema = Parser::run($schema);
```

## 校验数据并获取错误信息
如果给定的数据与schema不匹配，isValid方法将返回false，反之则为true
```
use Foamzou\EasyJsonSchema\Manager\Validator;

$validator = Validator::getInstance();
$isValid = $validator->isValid($jsonSchema, json_encode($data), $errMessage, $errList);
```

当返回false时，$errMessage和$errList不为空。

$errMessage为语义化好的字符串，$errList是一个item为Opis\JsonSchema\ValidationError的数组

如果$errMessage展示的格式或内容不符合需求，你可以使用$errList来构建想要展示出来的错误信息