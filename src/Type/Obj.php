<?php

namespace Foamzou\EasyJsonSchema\Type;

class Obj extends Base
{
    protected $type = 'object';

    protected $properties = self::UNDEFINED;
    protected $required = self::UNDEFINED;
    protected $dependencies = self::UNDEFINED;
    protected $minProperties = self::UNDEFINED;
    protected $maxProperties = self::UNDEFINED;
    protected $propertyNames = self::UNDEFINED;
    protected $patternProperties = self::UNDEFINED;
    protected $additionalProperties = self::UNDEFINED;

    public function __construct(array $propList = [])
    {
        $this->properties = empty($propList) ? self::UNDEFINED : $propList;
        return $this;
    }

    public function required(array $requiredList)
    {
        $this->required = $requiredList;
        return $this;
    }

    public function requiredAll()
    {
        $this->required = array_keys($this->properties);
        return $this;
    }


    public function additionalProp(bool $v)
    {
        $this->additionalProperties = $v;
        return $this;
    }

    public function dependencies($v)
    {
        $this->dependencies = $v;
        return $this;
    }

    public function minProp($v)
    {
        $this->minProperties = $v;
        return $this;
    }

    public function maxProp($v)
    {
        $this->maxProperties = $v;
        return $this;
    }

    public function propNames($v)
    {
        $this->propertyNames = $v;
        return $this;
    }

    public function patternProp($v)
    {
        $this->patternProperties = $v;
        return $this;
    }

}