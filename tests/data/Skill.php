<?php

use Foamzou\EasyJsonSchema\Type\{
    Str, Num, Obj
};

return (new Obj([
    'name' => (new Str())->min(1)->max(64),
    'value' => (new Num())->min(0)->max(100)->multipleOf(0.25),
]))->requiredAll()->additionalProp(false);