<?php

namespace Foamzou\EasyJsonSchema\Type;

use Foamzou\EasyJsonSchema\Manager\Parser;

class Base
{
    const UNDEFINED = '#value.undefined';

    protected $default = self::UNDEFINED;
    protected $description = self::UNDEFINED;

    protected $anyOf = self::UNDEFINED;
    protected $oneOf = self::UNDEFINED;
    protected $allOf = self::UNDEFINED;

    protected $if = self::UNDEFINED;
    protected $then = self::UNDEFINED;
    protected $else = self::UNDEFINED;
    protected $not = self::UNDEFINED;

    public function toSchema()
    {
        $obj = new \ReflectionClass(get_called_class());
        $properties = $obj->getProperties();

        $output = [];

        if ($this->description !== self::UNDEFINED) {
            $output['description'] = $this->description;
        }

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if ($this instanceof Obj && $propertyName == 'properties' && $this->$propertyName !== self::UNDEFINED) {
                $output['properties'] = [];
                foreach ($this->$propertyName as $propName => $prop) {
                    $output['properties'][$propName] = Parser::parse($prop, $propName, $this);
                }

            } elseif ($this->$propertyName !== self::UNDEFINED) {
                $output[$propertyName] = Parser::parse($this->$propertyName, $propertyName, $this);
            }
        }

        return $output;
    }

    public function default($v)
    {
        $this->default = $v;
        return $this;
    }

    public function desc($v)
    {
        $this->description = $v;
        return $this;
    }

    public function anyOf($v)
    {
        $this->anyOf = $v;
        return $this;
    }

    public function oneOf($v)
    {
        $this->oneOf = $v;
        return $this;
    }

    public function allOf($v)
    {
        $this->allOf = $v;
        return $this;
    }

    public function if($v)
    {
        $this->if = $v;
        return $this;
    }

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

    public function not($v)
    {
        $this->not = $v;
        return $this;
    }
}