<?php

namespace Foamzou\EasyJsonSchema\Manager;

use Opis\JsonSchema\{
    Validator as OpisValidator, ValidationError, Schema
};


class Validator
{
    private static $instance = null;
    private static $opisValidatorInstance = null;

    private $errorMax = 5;

    public static function getInstance() : Validator
    {
        return self::$instance instanceof Validator ? self::$instance : (self::$instance = new self());
    }

    public function isValid(string $schema, string $json, &$errMsg = '', &$errorList = []) : bool
    {
        $data = json_decode($json);
        $schema = Schema::fromJsonString($schema);

        $validator = self::$opisValidatorInstance instanceof OpisValidator
            ? self::$opisValidatorInstance
            : (self::$opisValidatorInstance = new OpisValidator);

        $result = $validator->schemaValidation($data, $schema, $this->errorMax);

        if ($result->isValid()) {
            return true;
        } else {
            $messageList = [];
            $errorList = $result->getErrors();

            foreach ($errorList as $error) {
                $message = $this->getErrorMessage($error);
                $messageList[] = $message;
            }
            $errMsg = join("\n", $messageList);
            return false;
        }
    }

    public function setErrorMax(int $max)
    {
        $this->errorMax = $max;
    }

    private function getErrorMessage(ValidationError $error)
    {
        $dataPointer = $error->dataPointer();
        $errorType = $error->keyword();
        $detailList = $error->keywordArgs();
        $subErrorList = $error->subErrors();
        $detail = '';
        foreach ($detailList as $k => $v) {
            if (is_array($v)) {
                $v = json_encode($v);
            }
            $detail .= "$k: $v, ";
        }
        if (!empty($dataPointer)) {
            $output = "- [Name] %s, [ErrorType] %s, [Detail] %s";
            return sprintf($output, join('.', $dataPointer), $errorType, $detail);
        } else {
            if (!empty($detail)) {
                $output = "- [ErrorType] %s, [Detail] %s";
                return sprintf($output, $errorType, $detail);
            } else {
                foreach ($subErrorList as $subError) {
                    $detail .= "\n\t" . $this->getErrorMessage($subError) . ';';
                }
                $output = "- [ErrorType] %s, [SubErrorDetail] %s";
                return sprintf($output, $errorType, $detail);
            }
        }
    }
}