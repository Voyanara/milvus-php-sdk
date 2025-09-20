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

    public function test_alter_field_properties(): void
    {
        $collectionName = 'test_alter_field_' . time();
        
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
                ],
                [
                    'fieldName' => 'my_varchar',
                    'dataType' => 'VarChar',
                    'elementTypeParams' => [
                        'max_length' => 50
                    ]
                ]
            ]
        ];
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        $fieldParams = [
            'max_length' => 100
        ];
        
        $response = Milvus::collection()->alterFieldProperties($collectionName, 'my_varchar', $fieldParams);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_describe_collection(): void
    {
        $collectionName = 'test_describe_collection_' . time();
        
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
        
        $response = Milvus::collection()->describeCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertEquals($collectionName, $response->json('data.collectionName'));
        $this->assertIsArray($response->json('data.fields'));
        
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_alter_collection_properties(): void
    {
        $collectionName = 'test_alter_collection_' . time();
        
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
        
        $properties = [
            'mmmap.enabled' => true
        ];
        
        $response = Milvus::collection()->alterCollectionProperties($collectionName, $properties);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
//        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_compact_collection(): void
    {
        $collectionName = 'test_compact_collection_' . time();
        
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
        
        $response = Milvus::collection()->compactCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
//        Milvus::collection()->dropCollection($collectionName);
    }
}
