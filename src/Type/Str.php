<?php

namespace Foamzou\EasyJsonSchema\Type;

class Str extends Base
{
    protected $type = 'string';

    protected $minLength = self::UNDEFINED;
    protected $maxLength = self::UNDEFINED;
    protected $pattern = self::UNDEFINED;
    protected $contentEncoding = self::UNDEFINED;
    protected $contentMediaType = self::UNDEFINED;

    public function min($v)
    {
        $this->minLength = $v;
        return $this;
    }

    public function max($v)
    {
        $this->maxLength = $v;
        return $this;
    }

    public function pattern($v)
    {
        $this->pattern = $v;
        return $this;
    }

    public function contentEncoding($v)
    {
        $this->contentEncoding = $v;
        return $this;
    }

    public function contentMediaType($v)
    {
        $this->contentMediaType = $v;
        return $this;
    }
}