<?php

namespace Foamzou\EasyJsonSchema\Type;

class Num extends Base
{
    protected $type = 'number';

    protected $minimum  = self::UNDEFINED;
    protected $exclusiveMinimum = self::UNDEFINED;
    protected $maximum  = self::UNDEFINED;
    protected $exclusiveMaximum = self::UNDEFINED;
    protected $multipleOf   = self::UNDEFINED;

    public function min($v)
    {
        $this->minimum = $v;
        return $this;
    }

    public function max($v)
    {
        $this->maximum = $v;
        return $this;
    }

    public function exMin($v)
    {
        $this->exclusiveMinimum = $v;
        return $this;
    }

    public function exMax($v)
    {
        $this->exclusiveMaximum = $v;
        return $this;
    }

    public function multipleOf($v)
    {
        $this->multipleOf = $v;
        return $this;
    }
}