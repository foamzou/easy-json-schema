<?php

use Foamzou\EasyJsonSchema\Type\Str;


return [
    'country' => ["CN", "JP", "US"],
    'address' => (new Str())->max(100),
];