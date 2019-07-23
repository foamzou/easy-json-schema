<?php

namespace Foamzou\EasyJsonSchema\Keyword;


class Kif extends Base
{
    protected $keyword = 'Kif';

    protected $then;
    protected $else;

    public function then($v)
    {
        $this->then = $v;
        return $this;
    }

    public function else($v)
    {
        $this->else = $v;
        return $this;
    }
}