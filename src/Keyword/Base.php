<?php

namespace Foamzou\EasyJsonSchema\Keyword;

use Foamzou\EasyJsonSchema\Manager\Parser;

class Base
{
    private $description = '';
    private $value;
    protected $keyword;
    protected $else;
    protected $then;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function desc($v)
    {
        $this->description = $v;
        return $this;
    }

    public function toSchema()
    {
        if ($this instanceof Kif) {
            return $this->handleIf();
        }

        $schema = [];
        if (!empty($this->description)) {
            $schema['description'] = $this->description;
        }
        if (!($this instanceof Enum) && !($this instanceof Constant)) {
            if (is_array($this->value)) {
                $schema[$this->keyword] = array_map(function($obj){return Parser::parse($obj);}, $this->value);
            } else {
                $schema[$this->keyword] = Parser::parse($this->value);
            }
        } else {
            $schema[$this->keyword] = $this->value;
        }

        return $schema;
    }

    public function handleIf()
    {
        if (empty($this->else) || empty($this->then)) {
            throw new \Exception('missing else or then');
        }
        $schema = [];
        $schema['if']   = Parser::parse($this->value);
        $schema['then'] = Parser::parse($this->then);
        $schema['else'] = Parser::parse($this->else);

        return $schema;
    }
}