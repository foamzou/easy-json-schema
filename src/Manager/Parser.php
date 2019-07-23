<?php

namespace Foamzou\EasyJsonSchema\Manager;

use Foamzou\EasyJsonSchema\Keyword\Enum;
use Foamzou\EasyJsonSchema\Type\Base as TypeBase;
use Foamzou\EasyJsonSchema\Keyword\Base as KeywordBase;
use Foamzou\EasyJsonSchema\Type\Obj;

class Parser
{
    public static function run($entity)
    {
        return json_encode(self::parse($entity), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

    public static function parse($entity, $entityName = '', $parentEntity = null)
    {
        if (is_array($entity)) {
            if (Util::isAssocArray($entity)) {
                return self::handleAssocArray($entity, $entityName);
            }

            return $entity;
        } else {
            if (!($entity instanceof TypeBase) && !($entity instanceof KeywordBase)) {
                return $entity;
                //throw new \Exception('invalid Type or Keyword. Detail: ' . serialize($entity));
            }
            return $entity->toSchema();
        }
    }

    private static function handleAssocArray($entity, $entityName)
    {
        $data = [];
        foreach ($entity as $childEntityName => $childEntity) {
            $data[$childEntityName] = self::parse($childEntity, $entityName, $entity);
        }
        return $data;
    }
}