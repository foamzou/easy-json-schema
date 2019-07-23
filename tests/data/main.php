<?php

use Foamzou\EasyJsonSchema\Type\{
    Str, Integer, Num, Obj, Boolean, Arr
};

use Foamzou\EasyJsonSchema\Keyword\{
    Constant, Enum, AnyOf, AllOf, OneOf
};

return (new Obj([
    'code' => new Enum([0, 1, 2]),
    'message' => (new Str()),
    'data' => (new Obj([
        'name' => (new Str())->min(1)->max(64)->pattern('^[a-zA-Z0-9\-]+(\s[a-zA-Z0-9\-]+)*$'),
        'age' => (new Integer())->min(18)->max(100)->desc('年龄'),
        'who' => (new Constant('foam'))->desc('我是谁'),
        'price' => (new Num())->min(18)->max(100),
        'location' => require __DIR__ . '/Location.php',
        'email' => (new OneOf([(new Str()), (new Integer())])),
        'website' => (new Str())->default('https://github.com'),
        'available_for_hire' => (new Boolean()),
        'interests' => (new Arr())->max(100)->min(3)->uniq()->items((new Str())->max(120))->additionalItems(new Integer()),
        'skills' => (new Arr())->max(100)->uniq()->items(require __DIR__ . '/Skill.php'),
        'luckyNum' => (new Arr())->contains((new Integer())->exMin(0)->exMax(10)),
        'base64' => (new Str())->contentEncoding('base64'),
        'json' => (new Str())->contentMediaType('application/json'),
    ]))->requiredAll()->additionalProp(false),
]))->desc('获取用户信息返回体')->requiredAll()->additionalProp(false);