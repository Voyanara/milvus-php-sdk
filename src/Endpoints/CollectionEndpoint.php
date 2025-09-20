<?php

namespace Voyanara\MilvusSdk\Endpoints;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Voyanara\MilvusSdk\Requests\Collection\AddFieldRequest;
use Voyanara\MilvusSdk\Requests\Collection\CreateCollectionRequest;
use Voyanara\MilvusSdk\Requests\Collection\DropCollectionRequest;
use Voyanara\MilvusSdk\Requests\Role\ListRequest;

class CollectionEndpoint extends BaseResource
{

    /**
     * Create Collection - This operation creates a collection in a specified cluster.
     *
     * @param string $collectionName The name of the collection to create
     * @param array|null $schema The schema definition for the collection. Responsible for organizing data in the target collection. A valid schema should have multiple fields, which must include a primary key, a vector field, and several scalar fields (optional, defaults to null)
     * @param array|null $indexParams The parameters that apply to the index-building process. Array of index parameter objects with metricType, fieldName, indexName, and params (optional, defaults to null)
     * @param array|null $params Extra parameters for the collection such as max_length, enableDynamicField, shardsNum, consistencyLevel, partitionsNum, ttlSeconds (optional, defaults to null)
     * @param string|null $dbName The name of the database (optional, defaults to null)
     * @return Response A success response with response code and empty data object
     * 
     * Schema example:
     * [
     *     'autoId' => false,
     *     'enabledDynamicField' => false,
     *     'fields' => [
     *         [
     *             'fieldName' => 'my_id',
     *             'dataType' => 'Int64',
     *             'isPrimary' => true
     *         ],
     *         [
     *             'fieldName' => 'my_vector',
     *             'dataType' => 'FloatVector',
     *             'elementTypeParams' => [
     *                 'dim' => '5'
     *             ]
     *         ]
     *     ]
     * ]
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function createCollection(
        string  $collectionName,
        ?array  $schema = null,
        ?array  $indexParams = null,
        ?array  $params = null,
        ?string $dbName = null,
    ): Response
    {


        return $this->connector->send(new CreateCollectionRequest(
            collectionName: $collectionName,
            dbName: $dbName,
            schema: $schema,
            indexParams: $indexParams,
            params: $params
        ));
    }

    /**
     * Drop Collection - This operation drops the current collection and all data within the collection.
     *
     * @param string $collectionName The name of the target collection. Setting this to a non-existing collection results in an error
     * @param string|null $dbName The name of the database that to which the collection belongs. Setting this to a non-existing database results in an error (optional, defaults to null)
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function dropCollection(string $collectionName, ?string $dbName = null): Response
    {
        return $this->connector->send(new DropCollectionRequest($collectionName, $dbName));
    }

    /**
     * Add Collection Field - This operation adds a field to a collection without recreating the collection.
     *
     * @param string $collectionName The name of the target collection. Setting this to a non-existing collection results in an error
     * @param array $schema The schema of the field to add
     * @param string|null $dbName The name of the database which the collection belongs to. Setting this to a non-existing database results in an error (optional, defaults to null)
     * @return Response A success response with response code and empty data object
     * 
     * Schema structure:
     * [
     *     'fieldName' => 'new_field',               // string - The name of the field to add
     *     'dataType' => 'DataType.VARCHAR',         // string - The data type of the field (Int64, Float, Double, VarChar, Array, Vector)
     *     'elementDataType' => 'DataType.BOOL',     // string - Data type of elements in array field (required for array type)
     *     'nullable' => true,                       // boolean - Whether the field can be null (should be true for compatibility)
     *     'defaultValue' => 'default_value',        // object - Default value (required for VarChar type)
     *     'elementTypeParams' => [                  // object - Extra field parameters
     *         'max_length' => 255,                  // integer - Max length for VarChar values
     *         'max_capacity' => 100                 // integer - Max number of elements in array field
     *     ]
     * ]
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function addField(
        string $collectionName,
        array $schema,
        ?string $dbName = null
    ): Response
    {
        return $this->connector->send(new AddFieldRequest(
            collectionName: $collectionName,
            schema: $schema,
            dbName: $dbName
        ));
    }
}