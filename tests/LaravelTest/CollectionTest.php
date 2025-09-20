<?php

namespace Voyanara\MilvusSdk\Tests\LaravelTest;

use Voyanara\MilvusSdk\Facades\Milvus;
use Voyanara\MilvusSdk\Tests\OrchestraTestCase;

class CollectionTest extends OrchestraTestCase
{
    public function test_create_collection(): void
    {
        $schema = [
            'fields' => [
                [
                    'fieldName' => 'id',
                    'dataType' => 'Int64',
                    'isPrimary' => true
                ],
                [
                    'fieldName' => 'vector',
                    'dataType' => 'FloatVector',
                    'elementTypeParams' => [
                        'dim' => '128'
                    ]
                ]
            ]
        ];
        
        $response = Milvus::collection()->createCollection('MyFirstCollection', $schema);
        var_dump($response->body());
        $this->assertIsArray($response->json());
    }

    public function test_drop_collection(): void
    {
        $collectionName = 'test_drop_collection_' . time();
        
        $schema = [
            'fields' => [
                [
                    'fieldName' => 'id',
                    'dataType' => 'Int64',
                    'isPrimary' => true
                ],
                [
                    'fieldName' => 'vector',
                    'dataType' => 'FloatVector',
                    'elementTypeParams' => [
                        'dim' => '128'
                    ]
                ]
            ]
        ];
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        $response = Milvus::collection()->dropCollection($collectionName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_add_field(): void
    {
        $collectionName = 'test_add_field_' . time();
        
        $schema = [
            'fields' => [
                [
                    'fieldName' => 'id',
                    'dataType' => 'Int64',
                    'isPrimary' => true
                ],
                [
                    'fieldName' => 'vector',
                    'dataType' => 'FloatVector',
                    'elementTypeParams' => [
                        'dim' => '128'
                    ]
                ]
            ]
        ];
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        $fieldSchema = [
            'fieldName' => 'new_field',
            'dataType' => 'VarChar',
            'nullable' => true,
            'defaultValue' => 'default_value',
            'elementTypeParams' => [
                'max_length' => 255
            ]
        ];
        
        $response = Milvus::collection()->addField($collectionName, $fieldSchema);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::collection()->dropCollection($collectionName);
    }
}
