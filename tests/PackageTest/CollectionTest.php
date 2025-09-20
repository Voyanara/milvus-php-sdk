<?php

namespace Voyanara\MilvusSdk\Tests\PackageTest;

use Voyanara\MilvusSdk\Milvus;
use Voyanara\MilvusSdk\Tests\TestCase;

class CollectionTest extends TestCase
{
    private Milvus $milvus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->milvus = new Milvus('root:Milvus', 'http://localhost', '19530');
    }

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
        
        $response = $this->milvus->collection()->createCollection('MyFirstCollection', $schema);
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $response = $this->milvus->collection()->dropCollection($collectionName);
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $fieldSchema = [
            'fieldName' => 'new_field',
            'dataType' => 'VarChar',
            'nullable' => true,
            'defaultValue' => 'default_value',
            'elementTypeParams' => [
                'max_length' => 255
            ]
        ];
        
        $response = $this->milvus->collection()->addField($collectionName, $fieldSchema);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->collection()->dropCollection($collectionName);
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $fieldParams = [
            'max_length' => 100
        ];
        
        $response = $this->milvus->collection()->alterFieldProperties($collectionName, 'my_varchar', $fieldParams);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->collection()->dropCollection($collectionName);
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $response = $this->milvus->collection()->describeCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertEquals($collectionName, $response->json('data.collectionName'));
        $this->assertIsArray($response->json('data.fields'));

        $this->milvus->collection()->dropCollection($collectionName);
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $properties = [
            'mmmap.enabled' => true
        ];
        
        $response = $this->milvus->collection()->alterCollectionProperties($collectionName, $properties);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->collection()->dropCollection($collectionName);
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $response = $this->milvus->collection()->compactCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->collection()->dropCollection($collectionName);
    }

    public function test_drop_collection_properties(): void
    {
        $collectionName = 'test_drop_properties_' . time();
        
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        // First set a property
        $properties = ['mmmap.enabled' => true];
        $this->milvus->collection()->alterCollectionProperties($collectionName, $properties);
        
        // Then drop it
        $propertyKeys = ['mmmap.enabled'];
        $response = $this->milvus->collection()->dropCollectionProperties($collectionName, $propertyKeys);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->collection()->dropCollection($collectionName);
    }

    public function test_flush_collection(): void
    {
        $collectionName = 'test_flush_collection_' . time();
        
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $response = $this->milvus->collection()->flushCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->collection()->dropCollection($collectionName);
    }

    public function test_get_collection_load_state(): void
    {
        $collectionName = 'test_load_state_' . time();
        
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $response = $this->milvus->collection()->getCollectionLoadState($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertArrayHasKey('loadState', $response->json('data'));
        $this->assertArrayHasKey('loadProgress', $response->json('data'));
        
        $this->milvus->collection()->dropCollection($collectionName);
    }

    public function test_get_collection_stats(): void
    {
        $collectionName = 'test_stats_' . time();
        
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
        
        $this->milvus->collection()->createCollection($collectionName, $schema);
        
        $response = $this->milvus->collection()->getCollectionStats($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertArrayHasKey('rowCount', $response->json('data'));
        $this->assertIsInt($response->json('data.rowCount'));
        
        $this->milvus->collection()->dropCollection($collectionName);
    }
}
