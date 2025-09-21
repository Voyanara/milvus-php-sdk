<?php

namespace Voyanara\MilvusSdk\Endpoints;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Voyanara\MilvusSdk\Requests\Vector\DeleteRequest;
use Voyanara\MilvusSdk\Requests\Vector\GetRequest;
use Voyanara\MilvusSdk\Requests\Vector\HybridSearchRequest;
use Voyanara\MilvusSdk\Requests\Vector\InsertRequest;
use Voyanara\MilvusSdk\Requests\Vector\QueryRequest;
use Voyanara\MilvusSdk\Requests\Vector\SearchRequest;
use Voyanara\MilvusSdk\Requests\Vector\UpsertRequest;

class VectorEndpoint extends BaseResource
{
    /**
     * Insert - This operation inserts data into a specific collection.
     *
     * @param string $collectionName The name of an existing collection
     * @param array $data An array of entity objects. Note that the keys in an entity object should match the collection schema. Always pass an array, even for a single entity
     * @param string|null $partitionName The name of a partition in the current collection. If specified, the data is to be inserted into the specified partition (optional, defaults to null)
     * @param string|null $dbName The name of the target database (optional, defaults to null)
     * @return Response A success response with insertCount and insertIds
     * 
     * Data structure examples:
     * Single entity (wrapped in array):
     * [
     *     {
     *         "id": 0,
     *         "vector": [0.3580376395471989, -0.6023495712049978, 0.18414012509913835, -0.26286205330961354, 0.9029438446296592],
     *         "color": "pink_8682"
     *     }
     * ]
     * 
     * Multiple entities:
     * [
     *     {
     *         "id": 0,
     *         "vector": [0.3580376395471989, -0.6023495712049978, 0.18414012509913835, -0.26286205330961354, 0.9029438446296592],
     *         "color": "pink_8682"
     *     },
     *     {
     *         "id": 1,
     *         "vector": [0.19886812562848388, 0.06023560599112088, 0.6976963061752597, 0.2614474506242501, 0.838729485096104],
     *         "color": "red_7025"
     *     }
     * ]
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {
     *         "insertCount": 10,
     *         "insertIds": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
     *     }
     * }
     */
    public function insert(
        string $collectionName,
        array $data,
        ?string $partitionName = null,
        ?string $dbName = null
    ): Response
    {
        return $this->connector->send(new InsertRequest(
            collectionName: $collectionName,
            data: $data,
            partitionName: $partitionName,
            dbName: $dbName
        ));
    }

    /**
     * Get - This operation gets specific entities by their IDs.
     * Note: The collection must be loaded and have an index on the vector field for this operation to work.
     *
     * @param string $collectionName The name of the collection to which this operation applies
     * @param array|int|string $id A specific entity ID or a list of entity IDs
     * @param array|null $outputFields An array of fields to return along with the query results (optional, defaults to null)
     * @param array|null $partitionNames The name of the partitions to which this operation applies (optional, defaults to null)
     * @param string|null $dbName The name of the database (optional, defaults to null)
     * @return Response A success response with query results
     * 
     * ID examples:
     * Single ID: 123
     * Multiple IDs: [1, 3, 5]
     * 
     * Output fields examples:
     * ["color", "vector"]
     * 
     * Partition names examples:
     * ["partition1", "partition2"]
     * 
     * Prerequisites:
     * - Collection must be created with an index on the vector field
     * - Collection must be loaded into memory
     * 
     * Example index creation:
     * $indexParams = [
     *     [
     *         'fieldName' => 'vector',
     *         'indexName' => 'vector_index',
     *         'metricType' => 'L2'
     *     ]
     * ];
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": [
     *         {
     *             "color": "red_7025",
     *             "id": 1
     *         },
     *         {
     *             "color": "pink_9298",
     *             "id": 3
     *         }
     *     ]
     * }
     */
    public function get(
        string $collectionName,
        array|int|string $id,
        ?array $outputFields = null,
        ?array $partitionNames = null,
        ?string $dbName = null
    ): Response
    {
        return $this->connector->send(new GetRequest(
            collectionName: $collectionName,
            id: $id,
            outputFields: $outputFields,
            partitionNames: $partitionNames,
            dbName: $dbName
        ));
    }

    /**
     * Delete - This operation deletes entities by their IDs or with a boolean expression.
     * Note: The collection must be loaded and have an index on the vector field for this operation to work.
     *
     * @param string $collectionName The name of an existing collection
     * @param string $filter A scalar filtering condition to filter matching entities. You can set this parameter to an empty string to skip scalar filtering. To build a scalar filtering condition, refer to Boolean Expression Rules
     * @param string|null $partitionName The name of a partition in the current collection. If specified, the data is to be deleted from the specified partition (optional, defaults to null)
     * @param string|null $dbName The name of the target database (optional, defaults to null)
     * @return Response A success response with cost information
     * 
     * Filter examples:
     * - "id == 4321034832910" - delete specific entity by ID
     * - "color == 'red'" - delete entities by field value
     * - "id in [1, 2, 3]" - delete multiple entities by IDs
     * - "" - empty string to delete all entities (use with caution)
     * 
     * Prerequisites:
     * - Collection must be created with an index on the vector field
     * - Collection must be loaded into memory
     * 
     * Example index creation:
     * $indexParams = [
     *     [
     *         'fieldName' => 'vector',
     *         'indexName' => 'vector_index',
     *         'metricType' => 'L2'
     *     ]
     * ];
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "cost": 0,
     *     "data": {}
     * }
     */
    public function delete(
        string $collectionName,
        string $filter,
        ?string $partitionName = null,
        ?string $dbName = null
    ): Response
    {
        return $this->connector->send(new DeleteRequest(
            collectionName: $collectionName,
            filter: $filter,
            partitionName: $partitionName,
            dbName: $dbName
        ));
    }

    /**
     * Hybrid Search - This operation searches for entities based on vector similarity and scalar filtering and reranks the results using a specified strategy.
     * Note: The collection must be loaded and have an index on all vector fields for this operation to work.
     *
     * @param string $collectionName The name of the collection to which this operation applies
     * @param array $search The search parameters - array of search objects with vector fields and their search criteria
     * @param array $rerank The reranking strategy configuration
     * @param int $limit The total number of entities to return
     * @param string|null $dbName The name of the database (optional, defaults to null)
     * @param array|null $partitionNames The name of the partitions to which this operation applies (optional, defaults to null)
     * @param array|null $outputFields An array of fields to return along with the search results (optional, defaults to null)
     * @param string|null $consistencyLevel The consistency level of the search operation (optional, defaults to null)
     * @return Response A success response with hybrid search results
     * 
     * Search parameter structure:
     * Each search object in $search array should contain:
     * [
     *     "data" => [[0.673, 0.739]], // Vector embeddings to search for
     *     "annsField" => "float_vector_1", // Name of the vector field
     *     "limit" => 10, // Number of entities to return for this search
     *     "outputFields" => ["*"], // Fields to return (optional)
     *     "filter" => "user_id > 0", // Boolean expression filter (optional)
     *     "groupingField" => "category", // Field to group by (optional)
     *     "metricType" => "L2", // Metric type (optional)
     *     "offset" => 0, // Number of entities to skip (optional)
     *     "ignoreGrowing" => false, // Whether to ignore growing segments (optional)
     *     "params" => [ // Extra search parameters (optional)
     *         "radius" => 0.1,
     *         "range_filter" => 0.9
     *     ]
     * ]
     * 
     * Rerank parameter structure:
     * [
     *     "strategy" => "rrf", // The reranking strategy name (currently only "rrf" supported)
     *     "params" => [
     *         "k" => 10 // Tunable constant for RRF algorithm
     *     ]
     * ]
     * 
     * Example usage:
     * $search = [
     *     [
     *         "data" => [[0.673437956701697, 0.739243747672878]],
     *         "annsField" => "float_vector_1",
     *         "limit" => 10,
     *         "outputFields" => ["*"]
     *     ],
     *     [
     *         "data" => [[0.075384179256879, 0.9971545645073111]],
     *         "annsField" => "float_vector_2",
     *         "limit" => 10,
     *         "outputFields" => ["*"]
     *     ]
     * ];
     * 
     * $rerank = [
     *     "strategy" => "rrf",
     *     "params" => ["k" => 10]
     * ];
     * 
     * $outputFields = ["user_id", "word_count", "book_describe"];
     * 
     * $response = $milvus->vector()->hybridSearch(
     *     "test_collection",
     *     $search,
     *     $rerank,
     *     3,
     *     null,
     *     null,
     *     $outputFields
     * );
     * 
     * Prerequisites:
     * - Collection must be created with indexes on all vector fields used in search
     * - Collection must be loaded into memory
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "cost": 0,
     *     "data": [
     *         {
     *             "book_describe": "book_105",
     *             "distance": 0.09090909,
     *             "id": 450519760774180800,
     *             "user_id": 5,
     *             "word_count": 105
     *         }
     *     ]
     * }
     */
    public function hybridSearch(
        string $collectionName,
        array $search,
        array $rerank,
        int $limit,
        ?string $dbName = null,
        ?array $partitionNames = null,
        ?array $outputFields = null,
        ?string $consistencyLevel = null
    ): Response
    {
        return $this->connector->send(new HybridSearchRequest(
            collectionName: $collectionName,
            search: $search,
            rerank: $rerank,
            limit: $limit,
            dbName: $dbName,
            partitionNames: $partitionNames,
            outputFields: $outputFields,
            consistencyLevel: $consistencyLevel
        ));
    }

    /**
     * Query - This operation conducts a filtering on the scalar field with a specified boolean expression.
     * Note: The collection must be loaded for this operation to work.
     *
     * @param string $collectionName The name of the collection to which this operation applies
     * @param string $filter The filter used to find matches for the search (boolean expression)
     * @param string|null $dbName The name of the database (optional, defaults to null)
     * @param array|null $outputFields An array of fields to return along with the query results (optional, defaults to null)
     * @param array|null $partitionNames The name of the partitions to which this operation applies (optional, defaults to null)
     * @param int|null $limit The total number of entities to return (optional, defaults to null)
     * @param int|null $offset The number of records to skip in the search result (optional, defaults to null)
     * @return Response A success response with query results
     * 
     * Filter expression examples:
     * - "color like 'red_%'" - find entities with color field starting with "red_"
     * - "id > 100" - find entities with id greater than 100
     * - "user_id in [1, 2, 3]" - find entities with user_id in the specified list
     * - "word_count >= 200 and word_count <= 500" - range filter
     * - "book_describe != ''" - find entities with non-empty book_describe field
     * 
     * Output fields examples:
     * - ["color"] - return only color field
     * - ["id", "color", "word_count"] - return specific fields
     * - null or omit - return all fields
     * 
     * Partition names examples:
     * - ["partition1", "partition2"] - search only in specified partitions
     * - null or omit - search in all partitions
     * 
     * Pagination examples:
     * - limit: 10 - return up to 10 results
     * - limit: 10, offset: 20 - return results 21-30 (skip first 20)
     * Note: The sum of limit and offset should be less than 16,384
     * 
     * Example usage:
     * $response = $milvus->vector()->query(
     *     "test_collection",
     *     "color like 'red_%'",
     *     null,
     *     ["color", "id"],
     *     null,
     *     3
     * );
     * 
     * Prerequisites:
     * - Collection must be loaded into memory
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "cost": 0,
     *     "data": [
     *         {
     *             "color": "red_7025",
     *             "id": 1
     *         },
     *         {
     *             "color": "red_4794",
     *             "id": 4
     *         }
     *     ]
     * }
     */
    public function query(
        string $collectionName,
        string $filter,
        ?string $dbName = null,
        ?array $outputFields = null,
        ?array $partitionNames = null,
        ?int $limit = null,
        ?int $offset = null
    ): Response
    {
        return $this->connector->send(new QueryRequest(
            collectionName: $collectionName,
            filter: $filter,
            dbName: $dbName,
            outputFields: $outputFields,
            partitionNames: $partitionNames,
            limit: $limit,
            offset: $offset
        ));
    }

    /**
     * Search - This operation conducts a vector similarity search with an optional scalar filtering expression.
     * Note: The collection must be loaded and have an index on the vector field for this operation to work.
     *
     * @param string $collectionName The name of the collection to which this operation applies
     * @param array $data A list of vector embeddings. Milvus searches for the most similar vector embeddings to the specified ones
     * @param string $annsField The name of the vector field
     * @param string|null $dbName The name of the database (optional, defaults to null)
     * @param string|null $filter The filter used to find matches for the search (optional, defaults to null)
     * @param int|null $limit The total number of entities to return (optional, defaults to null)
     * @param int|null $offset The number of records to skip in the search result (optional, defaults to null)
     * @param string|null $groupingField The name of the field that serves as the aggregation criteria (optional, defaults to null)
     * @param array|null $outputFields An array of fields to return along with the search results (optional, defaults to null)
     * @param array|null $searchParams The parameter settings specific to this operation (optional, defaults to null)
     * @param array|null $partitionNames The name of the partitions to search in (optional, defaults to null)
     * @param string|null $consistencyLevel The consistency level of the search operation (optional, defaults to null)
     * @return Response A success response with search results
     * 
     * Data parameter examples:
     * Single vector: [[0.3580376395471989, -0.6023495712049978, 0.18414012509913835]]
     * Multiple vectors: [
     *     [0.3580376395471989, -0.6023495712049978, 0.18414012509913835],
     *     [0.19886812562848388, 0.06023560599112088, 0.6976963061752597]
     * ]
     * 
     * Filter examples:
     * - "color like 'red_%'" - find entities with color field starting with "red_"
     * - "id > 100" - find entities with id greater than 100
     * - "word_count >= 200 and word_count <= 500" - range filter
     * 
     * SearchParams structure (optional):
     * [
     *     "metricType" => "L2", // The metric type (L2, IP, COSINE)
     *     "params" => [
     *         "radius" => 0.1, // Threshold of least similarity
     *         "range_filter" => 0.9 // Similarity range filter
     *     ]
     * ]
     * 
     * Output fields examples:
     * - ["color"] - return only color field
     * - ["id", "color", "distance"] - return specific fields
     * - null or omit - return all fields
     * 
     * Example usage:
     * $data = [[0.3580376395471989, -0.6023495712049978, 0.18414012509913835]];
     * $response = $milvus->vector()->search(
     *     "test_collection",
     *     $data,
     *     "vector",
     *     null,
     *     null,
     *     3,
     *     null,
     *     null,
     *     ["color"]
     * );
     * 
     * Prerequisites:
     * - Collection must be created with an index on the vector field
     * - Collection must be loaded into memory
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": [
     *         {
     *             "color": "orange_6781",
     *             "distance": 1.0,
     *             "id": 448300048035776800
     *         },
     *         {
     *             "color": "red_4794",
     *             "distance": 0.9353201,
     *             "id": 448300048035776801
     *         }
     *     ]
     * }
     */
    public function search(
        string $collectionName,
        array $data,
        string $annsField,
        ?string $dbName = null,
        ?string $filter = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $groupingField = null,
        ?array $outputFields = null,
        ?array $searchParams = null,
        ?array $partitionNames = null,
        ?string $consistencyLevel = null
    ): Response
    {
        return $this->connector->send(new SearchRequest(
            collectionName: $collectionName,
            data: $data,
            annsField: $annsField,
            dbName: $dbName,
            filter: $filter,
            limit: $limit,
            offset: $offset,
            groupingField: $groupingField,
            outputFields: $outputFields,
            searchParams: $searchParams,
            partitionNames: $partitionNames,
            consistencyLevel: $consistencyLevel
        ));
    }

    /**
     * Upsert - This operation inserts new records into the database or updates existing ones.
     * Note: The collection must be loaded for this operation to work.
     *
     * @param string $collectionName The name of the collection in which to upsert data
     * @param array $data An entity object or an array of entity objects. Keys should match the collection schema
     * @param string|null $dbName The name of the database (optional, defaults to null)
     * @param string|null $partitionName The name of a partition in the current collection (optional, defaults to null)
     * @return Response A success response with upsert results
     * 
     * Data structure examples:
     * Single entity (wrapped in array):
     * [
     *     {
     *         "id": 0,
     *         "vector": [0.3580376395471989, -0.6023495712049978, 0.18414012509913835, -0.26286205330961354, 0.9029438446296592],
     *         "color": "pink_8682"
     *     }
     * ]
     * 
     * Multiple entities:
     * [
     *     {
     *         "id": 0,
     *         "vector": [0.3580376395471989, -0.6023495712049978, 0.18414012509913835, -0.26286205330961354, 0.9029438446296592],
     *         "color": "pink_8682"
     *     },
     *     {
     *         "id": 1,
     *         "vector": [0.19886812562848388, 0.06023560599112088, 0.6976963061752597, 0.2614474506242501, 0.838729485096104],
     *         "color": "red_7025"
     *     }
     * ]
     * 
     * Example usage:
     * $data = [
     *     [
     *         "id" => 1,
     *         "vector" => [0.3580376395471989, -0.6023495712049978, 0.18414012509913835],
     *         "color" => "pink_8682"
     *     ]
     * ];
     * 
     * $response = $milvus->vector()->upsert("test_collection", $data);
     * 
     * Prerequisites:
     * - Collection must be loaded into memory
     * - Entity keys must match the collection schema
     * 
     * How it works:
     * - If entity with the same primary key exists, it will be updated
     * - If entity with the primary key doesn't exist, it will be inserted
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {
     *         "upsertCount": 10,
     *         "upsertIds": ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"]
     *     }
     * }
     */
    public function upsert(
        string $collectionName,
        array $data,
        ?string $dbName = null,
        ?string $partitionName = null
    ): Response
    {
        return $this->connector->send(new UpsertRequest(
            collectionName: $collectionName,
            data: $data,
            dbName: $dbName,
            partitionName: $partitionName
        ));
    }
}
