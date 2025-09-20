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
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        // First set a property
        $properties = ['mmmap.enabled' => true];
        Milvus::collection()->alterCollectionProperties($collectionName, $properties);
        
        // Then drop it
        $propertyKeys = ['mmmap.enabled'];
        $response = Milvus::collection()->dropCollectionProperties($collectionName, $propertyKeys);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::collection()->dropCollection($collectionName);
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
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        $response = Milvus::collection()->flushCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::collection()->dropCollection($collectionName);
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
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        $response = Milvus::collection()->getCollectionLoadState($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertArrayHasKey('loadState', $response->json('data'));
        
        // loadProgress may not be present when collection is not loaded
        if (isset($response->json('data')['loadProgress'])) {
            $this->assertIsInt($response->json('data.loadProgress'));
        }
        
        Milvus::collection()->dropCollection($collectionName);
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
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        $response = Milvus::collection()->getCollectionStats($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertArrayHasKey('rowCount', $response->json('data'));
        $this->assertIsInt($response->json('data.rowCount'));
        
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_has_collection(): void
    {
        $collectionName = 'test_has_collection_' . time();
        
        // Check non-existing collection
        $response = Milvus::collection()->hasCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertFalse($response->json('data.has'));
        
        // Create collection and check again
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
        
        $response = Milvus::collection()->hasCollection($collectionName);
        $this->assertTrue($response->json('data.has'));
        
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_list_collections(): void
    {
        $response = Milvus::collection()->listCollections();
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertIsArray($response->json('data'));
    }

    public function test_load_release_collection(): void
    {
        $collectionName = 'test_load_release_' . time();
        
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
        
        $indexParams = [
            [
                'fieldName' => 'vector',
                'indexName' => 'vector_index',
                'metricType' => 'L2'
            ]
        ];
        
        Milvus::collection()->createCollection($collectionName, $schema, $indexParams);
        
        // Load collection
        $response = Milvus::collection()->loadCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        // Release collection
        $response = Milvus::collection()->releaseCollection($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_refresh_load(): void
    {
        $collectionName = 'test_refresh_load_' . time();
        
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
        
        $indexParams = [
            [
                'fieldName' => 'vector',
                'indexName' => 'vector_index',
                'metricType' => 'L2'
            ]
        ];
        
        Milvus::collection()->createCollection($collectionName, $schema, $indexParams);
        
        // Load collection first
        $loadResponse = Milvus::collection()->loadCollection($collectionName);
        $this->assertEquals(0, $loadResponse->json('code'));
        
        // Wait a bit for collection to be fully loaded
        sleep(2);
        
        $response = Milvus::collection()->refreshLoad($collectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        
        // Check if collection is loaded before refreshing, if not skip the test
        $loadStateResponse = Milvus::collection()->getCollectionLoadState($collectionName);
        if ($loadStateResponse->json('data.loadState') !== 'LoadStateLoaded') {
            $this->markTestSkipped('Collection not fully loaded, skipping refresh load test');
        }
        
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_rename_collection(): void
    {
        $collectionName = 'test_rename_' . time();
        $newCollectionName = 'test_renamed_' . time();
        
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
        
        $response = Milvus::collection()->renameCollection($collectionName, $newCollectionName);
        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::collection()->dropCollection($newCollectionName);
    }
}
