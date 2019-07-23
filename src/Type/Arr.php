<?php

namespace Foamzou\EasyJsonSchema\Type;

class Arr extends Base
{
    protected $type = 'array';

    protected $minItems = self::UNDEFINED;
    protected $maxItems = self::UNDEFINED;
    protected $uniqueItems = self::UNDEFINED;
    protected $contains = self::UNDEFINED;
    protected $items = self::UNDEFINED;
    protected $additionalItems = self::UNDEFINED;

    public function min(int $v)
    {
        $this->minItems = $v;
        return $this;
    }

    public function max(int $v)
    {
        $this->maxItems = $v;
        return $this;
    }

    public function uniq(bool $v = true)
    {
        $this->uniqueItems = $v;
        return $this;
    }

    public function contains(Base $v)
    {
        $this->contains = $v;
        return $this;
    }

    /**
     * @param Base|Base[] $v
     * @return $this
     */
    public function items($v)
    {
        $this->items = $v;
        return $this;
    }

    public function additionalItems(Base $v)
    {
        $this->additionalItems = $v;
        return $this;
    }
}