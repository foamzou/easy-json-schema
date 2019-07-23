<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Foamzou\EasyJsonSchema\Manager\Validator;
use Foamzou\EasyJsonSchema\Manager\Parser;


class Test extends TestCase
{
    private $schema;
    private $data;

    public function testBase()
    {
        $validator = Validator::getInstance();
        $isValid = $validator->isValid($this->schema, json_encode($this->data), $errMessage);
        $this->assertTrue($isValid);

        $data = $this->data;
        $data['code'] = 'wrong code';
        $isValid = $validator->isValid($this->schema, json_encode($data), $errMessage);
        $this->assertFalse($isValid);
    }


    public function testErrorMessage()
    {
        $validator = Validator::getInstance();
        $errorMaxCount = 10;
        $validator->setErrorMax($errorMaxCount);

        $data = $this->data;
        foreach ($data['data'] as $k => &$v) {
            if ($k == 'location') {
                foreach ($v as &$v2) {
                    $v2 = '';
                }
            } else {
                $v = '';
            }
        }

        $isValid = $validator->isValid($this->schema, json_encode($data), $errMessage, $errorList);

        $this->assertFalse($isValid);
        $this->assertTrue(count($errorList) === $errorMaxCount, 'max count should be ' . $errorMaxCount);
    }


    protected function setUp(): void
    {
        $schemaDef = require __DIR__ . '/data/main.php';
        $this->schema = Parser::run($schemaDef);

        $this->data = $this->prepareData();
    }

    private function prepareData()
    {
        return [
            'code' => 0,
            'message' => '',
            'data' => [
                "name"=> "foam",
                "age"=> 19,
                "price" => 19.3,
                "who" => 'foam',
                "email"=> "john@example.com",
                "website"=> 'das',
                "location"=> [
                    "country"=> "CN",
                    "address"=> "Sesame Street, no. 5"
                ],
                "available_for_hire"=> true,
                "interests"=> ["php", "html", "css", "javascript", "programming", "web design"],
                "skills"=> [
                    [
                        "name"=> "HTML",
                        "value"=> 100
                    ],
                    [
                        "name"=> "PHP",
                        "value"=> 55
                    ],
                    [
                        "name"=> "CSS",
                        "value"=> 99.5
                    ],
                    [
                        "name"=> "JavaScript",
                        "value"=> 75
                    ]
                ],
                'luckyNum' => [1, 5, 4],
                'base64' => 'b3Bpcy9qc29uLXNjaGVtYQ==',
                'json' => '{}',
            ]
        ];
    }
}