<?php

namespace Voyanara\MilvusSdk\Tests\LaravelTest;

use Voyanara\MilvusSdk\Facades\Milvus;
use Voyanara\MilvusSdk\Tests\OrchestraTestCase;

class VectorTest extends OrchestraTestCase
{
    public function test_vector_insert(): void
    {
        $collectionName = 'test_vector_insert_' . time();
        
        // Create collection with schema first
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
                        'dim' => '5'
                    ]
                ],
                [
                    'fieldName' => 'color',
                    'dataType' => 'VarChar',
                    'elementTypeParams' => [
                        'max_length' => 255
                    ]
                ]
            ]
        ];
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        // Test data from API documentation
        $data = [
            [
                'id' => 0,
                'vector' => [
                    0.3580376395471989,
                    -0.6023495712049978,
                    0.18414012509913835,
                    -0.26286205330961354,
                    0.9029438446296592
                ],
                'color' => 'pink_8682'
            ],
            [
                'id' => 1,
                'vector' => [
                    0.19886812562848388,
                    0.06023560599112088,
                    0.6976963061752597,
                    0.2614474506242501,
                    0.838729485096104
                ],
                'color' => 'red_7025'
            ],
            [
                'id' => 2,
                'vector' => [
                    0.43742130801983836,
                    -0.5597502546264526,
                    0.6457887650909682,
                    0.7894058910881185,
                    0.20785793220625592
                ],
                'color' => 'orange_6781'
            ]
        ];
        
        $response = Milvus::vector()->insert($collectionName, $data);
        var_dump($response->body());
        
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertArrayHasKey('insertCount', $response->json('data'));
        $this->assertArrayHasKey('insertIds', $response->json('data'));
        $this->assertEquals(3, $response->json('data.insertCount'));
        $this->assertCount(3, $response->json('data.insertIds'));
        
        // Flush data to disk
        Milvus::collection()->flushCollection($collectionName);
        
        // Load collection to memory for queries
        Milvus::collection()->loadCollection($collectionName);
        
        // Clean up
        Milvus::collection()->dropCollection($collectionName);
    }


    public function test_vector_get(): void
    {
        $collectionName = 'test_vector_get_' . time();
        
        // Create collection with schema first
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
                        'dim' => '5'
                    ]
                ],
                [
                    'fieldName' => 'color',
                    'dataType' => 'VarChar',
                    'elementTypeParams' => [
                        'max_length' => 255
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
        
        // Insert test data
        $data = [
            [
                'id' => 1,
                'vector' => [0.1, 0.2, 0.3, 0.4, 0.5],
                'color' => 'red_7025'
            ],
            [
                'id' => 3,
                'vector' => [0.6, 0.7, 0.8, 0.9, 1.0],
                'color' => 'pink_9298'
            ],
            [
                'id' => 5,
                'vector' => [1.1, 1.2, 1.3, 1.4, 1.5],
                'color' => 'yellow_4222'
            ]
        ];
        
        Milvus::vector()->insert($collectionName, $data);
        
        // Flush data to disk and load collection
        Milvus::collection()->flushCollection($collectionName);
        Milvus::collection()->loadCollection($collectionName);
        
        // Wait for collection to be loaded
        sleep(3);
        
        // Test get by multiple IDs
        $response = Milvus::vector()->get($collectionName, [1, 3, 5], ['color']);
        var_dump($response->body());
        
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertIsArray($response->json('data'));
        $this->assertCount(3, $response->json('data'));
        
        // Verify returned data structure
        $returnedData = $response->json('data');
        foreach ($returnedData as $entity) {
            $this->assertArrayHasKey('id', $entity);
            $this->assertArrayHasKey('color', $entity);
            $this->assertContains($entity['id'], [1, 3, 5]);
        }
        
        // Clean up
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_vector_delete(): void
    {
        $collectionName = 'test_vector_delete_' . time();
        
        // Create collection with schema first
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
                        'dim' => '5'
                    ]
                ],
                [
                    'fieldName' => 'color',
                    'dataType' => 'VarChar',
                    'elementTypeParams' => [
                        'max_length' => 255
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
        
        // Insert test data
        $data = [
            [
                'id' => 1,
                'vector' => [0.1, 0.2, 0.3, 0.4, 0.5],
                'color' => 'red'
            ],
            [
                'id' => 2,
                'vector' => [0.6, 0.7, 0.8, 0.9, 1.0],
                'color' => 'blue'
            ]
        ];
        
        Milvus::vector()->insert($collectionName, $data);
        
        // Flush data to disk and load collection
        Milvus::collection()->flushCollection($collectionName);
        Milvus::collection()->loadCollection($collectionName);
        
        // Wait for collection to be loaded
        sleep(3);
        
        // Test delete by filter
        $response = Milvus::vector()->delete($collectionName, 'id == 1');
        var_dump($response->body());
        
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertArrayHasKey('data', $response->json());
        
        // Clean up
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_vector_hybrid_search(): void
    {
        $collectionName = 'test_vector_hybrid_search_' . time();
        
        // Create collection with multiple vector fields for hybrid search
        $schema = [
            'fields' => [
                [
                    'fieldName' => 'id',
                    'dataType' => 'Int64',
                    'isPrimary' => true
                ],
                [
                    'fieldName' => 'float_vector_1',
                    'dataType' => 'FloatVector',
                    'elementTypeParams' => [
                        'dim' => '2'
                    ]
                ],
                [
                    'fieldName' => 'float_vector_2',
                    'dataType' => 'FloatVector',
                    'elementTypeParams' => [
                        'dim' => '2'
                    ]
                ],
                [
                    'fieldName' => 'user_id',
                    'dataType' => 'Int64'
                ],
                [
                    'fieldName' => 'word_count',
                    'dataType' => 'Int64'
                ],
                [
                    'fieldName' => 'book_describe',
                    'dataType' => 'VarChar',
                    'elementTypeParams' => [
                        'max_length' => 255
                    ]
                ]
            ]
        ];
        
        $indexParams = [
            [
                'fieldName' => 'float_vector_1',
                'indexName' => 'vector_index_1',
                'metricType' => 'L2'
            ],
            [
                'fieldName' => 'float_vector_2',
                'indexName' => 'vector_index_2',
                'metricType' => 'L2'
            ]
        ];
        
        Milvus::collection()->createCollection($collectionName, $schema, $indexParams);
        
        // Insert test data with multiple vector fields
        $data = [
            [
                'id' => 1,
                'float_vector_1' => [0.673, 0.739],
                'float_vector_2' => [0.075, 0.997],
                'user_id' => 5,
                'word_count' => 105,
                'book_describe' => 'book_10511'
            ],
            [
                'id' => 2,
                'float_vector_1' => [0.421, 0.832],
                'float_vector_2' => [0.123, 0.456],
                'user_id' => 46,
                'word_count' => 246,
                'book_describe' => 'book_246'
            ],
            [
                'id' => 3,
                'float_vector_1' => [0.321, 0.654],
                'float_vector_2' => [0.789, 0.321],
                'user_id' => 67,
                'word_count' => 367,
                'book_describe' => 'book_367'
            ]
        ];
        
        Milvus::vector()->insert($collectionName, $data);
        
        // Flush data to disk and load collection
        Milvus::collection()->flushCollection($collectionName);
        Milvus::collection()->loadCollection($collectionName);
        
        // Wait for collection to be loaded
        sleep(3);
        
        // Test hybrid search from API documentation
        $search = [
            [
                'data' => [
                    [0.673437956701697, 0.739243747672878]
                ],
                'annsField' => 'float_vector_1',
                'limit' => 10,
                'outputFields' => ['*']
            ],
            [
                'data' => [
                    [0.075384179256879, 0.9971545645073111]
                ],
                'annsField' => 'float_vector_2',
                'limit' => 10,
                'outputFields' => ['*']
            ]
        ];
        
        $rerank = [
            'strategy' => 'rrf',
            'params' => [
                'k' => 10
            ]
        ];
        
        $outputFields = ['user_id', 'word_count', 'book_describe'];
        
        $response = Milvus::vector()->hybridSearch(
            $collectionName,
            $search,
            $rerank,
            3,
            null,
            null,
            $outputFields
        );
        
        var_dump($response->body());
        
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertIsArray($response->json('data'));
        $this->assertLessThanOrEqual(3, count($response->json('data')));
        
        // Verify returned data structure
        $returnedData = $response->json('data');
        foreach ($returnedData as $entity) {
            $this->assertArrayHasKey('user_id', $entity);
            $this->assertArrayHasKey('word_count', $entity);
            $this->assertArrayHasKey('book_describe', $entity);
            $this->assertArrayHasKey('distance', $entity);
        }
        
        // Clean up
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_vector_query(): void
    {
        $collectionName = 'test_vector_query_' . time();
        
        // Create collection with schema
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
                        'dim' => '5'
                    ]
                ],
                [
                    'fieldName' => 'color',
                    'dataType' => 'VarChar',
                    'elementTypeParams' => [
                        'max_length' => 255
                    ]
                ],
                [
                    'fieldName' => 'word_count',
                    'dataType' => 'Int64'
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
        
        // Insert test data
        $data = [
            [
                'id' => 1,
                'vector' => [0.1, 0.2, 0.3, 0.4, 0.5],
                'color' => 'red_7025',
                'word_count' => 150
            ],
            [
                'id' => 4,
                'vector' => [0.6, 0.7, 0.8, 0.9, 1.0],
                'color' => 'red_4794',
                'word_count' => 200
            ],
            [
                'id' => 6,
                'vector' => [1.1, 1.2, 1.3, 1.4, 1.5],
                'color' => 'red_9392',
                'word_count' => 100
            ],
            [
                'id' => 10,
                'vector' => [1.6, 1.7, 1.8, 1.9, 2.0],
                'color' => 'blue_1234',
                'word_count' => 300
            ]
        ];
        
        Milvus::vector()->insert($collectionName, $data);
        
        // Flush data to disk and load collection
        Milvus::collection()->flushCollection($collectionName);
        Milvus::collection()->loadCollection($collectionName);
        
        // Wait for collection to be loaded
        sleep(3);
        
        // Test query with LIKE filter from API documentation
        $response = Milvus::vector()->query(
            $collectionName,
            "color like 'red_%'",
            null,
            ['color', 'id'],
            null,
            3
        );
        
        var_dump($response->body());
        
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertIsArray($response->json('data'));
        $this->assertLessThanOrEqual(3, count($response->json('data')));
        
        // Verify returned data structure and filter results
        $returnedData = $response->json('data');
        foreach ($returnedData as $entity) {
            $this->assertArrayHasKey('id', $entity);
            $this->assertArrayHasKey('color', $entity);
            $this->assertStringStartsWith('red_', $entity['color']);
        }
        
        // Test query with range filter
        $response2 = Milvus::vector()->query(
            $collectionName,
            "word_count >= 150 and word_count <= 250",
            null,
            ['id', 'word_count'],
            null,
            10
        );
        
        var_dump($response2->body());
        
        $this->assertIsArray($response2->json());
        $this->assertEquals(0, $response2->json('code'));
        $this->assertIsArray($response2->json('data'));
        
        // Verify range filter results
        $rangeData = $response2->json('data');
        foreach ($rangeData as $entity) {
            $this->assertArrayHasKey('id', $entity);
            $this->assertArrayHasKey('word_count', $entity);
            $this->assertGreaterThanOrEqual(150, $entity['word_count']);
            $this->assertLessThanOrEqual(250, $entity['word_count']);
        }
        
        // Test query with IN filter
        $response3 = Milvus::vector()->query(
            $collectionName,
            "id in [1, 4, 10]",
            null,
            ['id', 'color'],
            null,
            5
        );
        
        var_dump($response3->body());
        
        $this->assertIsArray($response3->json());
        $this->assertEquals(0, $response3->json('code'));
        $this->assertIsArray($response3->json('data'));
        
        // Verify IN filter results
        $inData = $response3->json('data');
        foreach ($inData as $entity) {
            $this->assertArrayHasKey('id', $entity);
            $this->assertArrayHasKey('color', $entity);
            $this->assertContains($entity['id'], [1, 4, 10]);
        }
        
        // Clean up
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_vector_search(): void
    {
        $collectionName = 'test_vector_search_' . time();
        
        // Create collection with schema
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
                        'dim' => '5'
                    ]
                ],
                [
                    'fieldName' => 'color',
                    'dataType' => 'VarChar',
                    'elementTypeParams' => [
                        'max_length' => 255
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
        
        // Insert test data
        $data = [
            [
                'id' => 1,
                'vector' => [
                    0.3580376395471989,
                    -0.6023495712049978,
                    0.18414012509913835,
                    -0.26286205330961354,
                    0.9029438446296592
                ],
                'color' => 'orange_6781'
            ],
            [
                'id' => 2,
                'vector' => [
                    0.19886812562848388,
                    0.06023560599112088,
                    0.6976963061752597,
                    0.2614474506242501,
                    0.838729485096104
                ],
                'color' => 'red_4794'
            ],
            [
                'id' => 3,
                'vector' => [
                    0.43742130801983836,
                    -0.5597502546264526,
                    0.6457887650909682,
                    0.7894058910881185,
                    0.20785793220625592
                ],
                'color' => 'grey_8510'
            ]
        ];
        
        Milvus::vector()->insert($collectionName, $data);
        
        // Flush data to disk and load collection
        Milvus::collection()->flushCollection($collectionName);
        Milvus::collection()->loadCollection($collectionName);
        
        // Wait for collection to be loaded
        sleep(3);
        
        // Test vector search from API documentation
        $searchData = [
            [
                0.3580376395471989,
                -0.6023495712049978,
                0.18414012509913835,
                -0.26286205330961354,
                0.9029438446296592
            ]
        ];
        
        $response = Milvus::vector()->search(
            $collectionName,
            $searchData,
            'vector',
            null,
            null,
            3,
            null,
            null,
            ['color']
        );
        
        var_dump($response->body());
        
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertIsArray($response->json('data'));
        $this->assertLessThanOrEqual(3, count($response->json('data')));
        
        // Verify returned data structure
        $returnedData = $response->json('data');
        foreach ($returnedData as $entity) {
            $this->assertArrayHasKey('id', $entity);
            $this->assertArrayHasKey('color', $entity);
            $this->assertArrayHasKey('distance', $entity);
            $this->assertTrue(is_numeric($entity['distance']));
        }
        
        // Test search with filter
        $response2 = Milvus::vector()->search(
            $collectionName,
            $searchData,
            'vector',
            null,
            "color like 'red_%'",
            2,
            null,
            null,
            ['color', 'id']
        );
        
        var_dump($response2->body());
        
        $this->assertIsArray($response2->json());
        $this->assertEquals(0, $response2->json('code'));
        $this->assertIsArray($response2->json('data'));
        
        // Verify filter works
        $filteredData = $response2->json('data');
        foreach ($filteredData as $entity) {
            $this->assertStringStartsWith('red_', $entity['color']);
        }
        
        // Clean up
        Milvus::collection()->dropCollection($collectionName);
    }

    public function test_vector_upsert(): void
    {
        $collectionName = 'test_vector_upsert_' . time();
        
        // Create collection with schema
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
                        'dim' => '5'
                    ]
                ],
                [
                    'fieldName' => 'color',
                    'dataType' => 'VarChar',
                    'elementTypeParams' => [
                        'max_length' => 255
                    ]
                ]
            ]
        ];
        
        Milvus::collection()->createCollection($collectionName, $schema);
        
        // Test upsert with data from API documentation
        $data = [
            [
                'id' => 0,
                'vector' => [
                    0.3580376395471989,
                    -0.6023495712049978,
                    0.18414012509913835,
                    -0.26286205330961354,
                    0.9029438446296592
                ],
                'color' => 'pink_8682'
            ],
            [
                'id' => 1,
                'vector' => [
                    0.19886812562848388,
                    0.06023560599112088,
                    0.6976963061752597,
                    0.2614474506242501,
                    0.838729485096104
                ],
                'color' => 'red_7025'
            ],
            [
                'id' => 2,
                'vector' => [
                    0.43742130801983836,
                    -0.5597502546264526,
                    0.6457887650909682,
                    0.7894058910881185,
                    0.20785793220625592
                ],
                'color' => 'orange_6781'
            ]
        ];
        
        $response = Milvus::vector()->upsert($collectionName, $data);
        var_dump($response->body());
        
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        $this->assertArrayHasKey('upsertCount', $response->json('data'));
        $this->assertArrayHasKey('upsertIds', $response->json('data'));
        $this->assertEquals(3, $response->json('data.upsertCount'));
        $this->assertCount(3, $response->json('data.upsertIds'));
        
        // Test upsert with same IDs (update operation)
        $updateData = [
            [
                'id' => 0,
                'vector' => [
                    0.1, 0.2, 0.3, 0.4, 0.5
                ],
                'color' => 'blue_updated'
            ],
            [
                'id' => 3,
                'vector' => [
                    0.6, 0.7, 0.8, 0.9, 1.0
                ],
                'color' => 'green_new'
            ]
        ];
        
        $response2 = Milvus::vector()->upsert($collectionName, $updateData);
        var_dump($response2->body());
        
        $this->assertIsArray($response2->json());
        $this->assertEquals(0, $response2->json('code'));
        $this->assertArrayHasKey('upsertCount', $response2->json('data'));
        $this->assertEquals(2, $response2->json('data.upsertCount'));
        
        // Clean up
        Milvus::collection()->dropCollection($collectionName);
    }
}
