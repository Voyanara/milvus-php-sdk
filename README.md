# 🚀 Milvus PHP SDK - Modern Vector Database Client

[![PHP Version](https://img.shields.io/badge/PHP-%5E8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/voyanara/milvus-php-sdk.svg?style=flat-square)](https://packagist.org/packages/voyanara/milvus-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/voyanara/milvus-php-sdk.svg?style=flat-square)](https://packagist.org/packages/voyanara/milvus-php-sdk)

A modern, type-safe PHP SDK for [Milvus](https://milvus.io/) vector database API v2. This library provides a clean, intuitive interface for managing collections, users, roles, and privileges in Milvus through its REST API.

Built with [Saloon HTTP](https://docs.saloon.dev/) for robust API communication, this SDK focuses on developer experience with proper type hints, comprehensive error handling, and a fluent API design.

Perfect for applications requiring vector similarity search, AI/ML workflows, and large-scale data processing with semantic search capabilities.

## Requirements

- PHP 8.1 or higher
- Milvus 2.6.x (or compatible versions)
- Laravel 10.x, 11.x, 12.x (for Laravel integration)

## Versions

| Milvus Version | SDK Version |
|----------------|-------------|
| v2.6.x         | v1.0.x      |

## Documentation

- [Milvus REST API Reference](https://milvus.io/api-reference/restful/v2.6.x/About.md) - Official Milvus RESTful API documentation

## Installation

You can install the package via Composer:

```bash
composer require voyanara/milvus-php-sdk
```

### Docker Development Environment

For development and testing, you can quickly spin up a Milvus instance using the included Docker Compose configuration:

```bash
docker-compose up -d
```

This will start Milvus with all necessary dependencies (etcd, MinIO) and expose it on the default port 19530.

### Laravel Integration

This package includes Laravel service provider for seamless integration:

```bash
# After installation, publish the configuration file
php artisan milvus-php-sdk:install
```

This command will publish the configuration file to `config/milvus-php-sdk.php` where you can set your Milvus connection parameters.

#### Configuration

Add the following environment variables to your `.env` file:

```env
# For local development with Docker
MILVUS_TOKEN=root:Milvus
MILVUS_HOST=http://localhost
MILVUS_PORT=19530

# For Zilliz Cloud (hosted Milvus)
MILVUS_TOKEN=db_randomstring:your_password
MILVUS_HOST=https://in03.serverless.gcp-us-west1.cloud.zilliz.com
MILVUS_PORT=443
```


## Roadmap

### ✅ Implemented Features

- **User Management** - Complete user operations (create, describe, drop, list, update password)
- **Role Management** - Full role-based access control (create, drop, describe, list, grant/revoke privileges)
- **Collection Management** - Complete collection operations and schema management (create, drop, describe, list, load, release, rename, etc.)
- **Vector Operations** - Complete vector data operations (insert, search, query, upsert, get, delete, hybrid search)

### 🚧 In Development

- **Index Management** - Vector index creation and optimization

### 📋 Planned Features

- **Alias Management** - Collection alias operations  
- **Database Management** - Multi-database support
- **Import Operations** - Bulk data import functionality
- **Partition Management** - Data partitioning for better performance
- **Resource Group** - Resource allocation and management

## Quick Start

### Basic Usage

```php
<?php

use Voyanara\MilvusSdk\Milvus;

// Initialize the client
$milvus = new Milvus(
    token: 'your_token_here',
    host: 'http://localhost',
    port: '19530'
);

// Example: List all users
$users = $milvus->user()->list();
```

### Using with Zilliz Cloud

For Zilliz Cloud (hosted Milvus), configure the client with your cloud credentials:

```php
<?php

use Voyanara\MilvusSdk\Milvus;

$milvus = new Milvus(
    token: "db_randomstring:your_password",
    host: 'https://in03.serverless.gcp-us-west1.cloud.zilliz.com',
    port: '443'
);

// Now you can use all SDK features with Zilliz Cloud
$collections = $milvus->collection()->list();
```

### Using with Laravel

Once configured, you can use the Milvus facade throughout your Laravel application:

```php
<?php

use Voyanara\MilvusSdk\Facades\Milvus;

// User management
$users = Milvus::user()->list();
$user = Milvus::user()->describe('username');

// Role management  
$roles = Milvus::role()->list();
$role = Milvus::role()->describe('role_name');

// Collection management
$collections = Milvus::collection()->list();
$collection = Milvus::collection()->describe('collection_name');

// Create a new collection with schema and index
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
            'elementTypeParams' => ['dim' => '128']
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

Milvus::collection()->createCollection('my_collection', $schema, $indexParams);

// Vector operations
// Insert vector data
$vectorData = [
    [
        'id' => 1,
        'vector' => [0.1, 0.2, 0.3, 0.4, 0.5],
        'metadata' => 'document1'
    ],
    [
        'id' => 2, 
        'vector' => [0.6, 0.7, 0.8, 0.9, 1.0],
        'metadata' => 'document2'
    ]
];

Milvus::vector()->insert('my_collection', $vectorData);

// Search for similar vectors
$queryVector = [[0.1, 0.2, 0.3, 0.4, 0.5]];
$searchResults = Milvus::vector()->search(
    collectionName: 'my_collection',
    data: $queryVector,
    annsField: 'vector',
    limit: 10,
    outputFields: ['id', 'metadata']
);

// Upsert (insert or update) vector data
$upsertData = [
    [
        'id' => 1,
        'vector' => [0.2, 0.3, 0.4, 0.5, 0.6], // Updated vector
        'metadata' => 'document1_updated'
    ]
];

Milvus::vector()->upsert('my_collection', $upsertData);
```

## Vector Operations Examples

### Vector Search

Perform semantic search using vector embeddings:

```php
<?php

use Voyanara\MilvusSdk\Milvus;

// Initialize Milvus client
$milvus = new Milvus(
    token: 'root:Milvus',
    host: 'http://localhost',
    port: '19530'
);

// Prepare query vectors (can be multiple vectors)
$queryVectors = [
    [0.3580376395471989, -0.6023495712049978, 0.18414012509913835],
    [0.19886812562848388, 0.06023560599112088, 0.6976963061752597]
];

// Basic vector search
$response = $milvus->vector()->search(
    collectionName: 'documents_collection',
    data: $queryVectors,
    annsField: 'content_vector',
    limit: 5,
    outputFields: ['id', 'title', 'category']
);

// Search with filtering
$response = $milvus->vector()->search(
    collectionName: 'documents_collection', 
    data: $queryVectors,
    annsField: 'content_vector',
    filter: "category == 'technology' and publish_date >= '2024-01-01'",
    limit: 10,
    outputFields: ['id', 'title', 'content']
);

// Advanced search with custom parameters
$searchParams = [
    'metricType' => 'L2',
    'params' => [
        'radius' => 0.1,
        'range_filter' => 0.9
    ]
];

$response = $milvus->vector()->search(
    collectionName: 'documents_collection',
    data: $queryVectors,
    annsField: 'content_vector', 
    searchParams: $searchParams,
    limit: 20,
    offset: 10  // Pagination support
);

// Process results
foreach ($response->json('data') as $result) {
    echo "Document ID: {$result['id']}, Distance: {$result['distance']}\n";
    echo "Title: {$result['title']}\n";
}
```

### Vector Upsert

Insert new vectors or update existing ones based on primary key:

```php
<?php

use Voyanara\MilvusSdk\Milvus;

// Initialize Milvus client
$milvus = new Milvus(
    token: 'root:Milvus',
    host: 'http://localhost', 
    port: '19530'
);

// Prepare document vectors for upsert
$documents = [
    [
        'id' => 1,
        'content_vector' => [0.1, 0.2, 0.3, 0.4, 0.5],
        'title' => 'Introduction to AI',
        'category' => 'technology',
        'publish_date' => '2024-01-15'
    ],
    [
        'id' => 2,
        'content_vector' => [0.6, 0.7, 0.8, 0.9, 1.0], 
        'title' => 'Machine Learning Basics',
        'category' => 'technology',
        'publish_date' => '2024-02-01'
    ],
    [
        'id' => 3,
        'content_vector' => [0.2, 0.4, 0.6, 0.8, 0.1],
        'title' => 'Deep Learning Guide', 
        'category' => 'technology',
        'publish_date' => '2024-03-10'
    ]
];

// Upsert documents (will insert new or update existing based on ID)
$response = $milvus->vector()->upsert(
    collectionName: 'documents_collection',
    data: $documents
);

echo "Upserted {$response->json('data.upsertCount')} documents\n";
print_r($response->json('data.upsertIds'));

// Upsert to specific partition
$response = $milvus->vector()->upsert(
    collectionName: 'documents_collection',
    data: $documents,
    partitionName: 'tech_partition'
);

// Single document upsert
$singleDocument = [
    [
        'id' => 100,
        'content_vector' => [0.3, 0.1, 0.4, 0.7, 0.2],
        'title' => 'Updated Document Title',
        'category' => 'science'
    ]
];

$milvus->vector()->upsert('documents_collection', $singleDocument);
```

## Creating a Milvus Collection

Quick example of creating a collection with vector field:

```php
<?php

use Voyanara\MilvusSdk\Milvus;

// Initialize client
$milvus = new Milvus(
    token: 'root:Milvus',
    host: 'http://localhost',
    port: '19530'
);

// Define collection schema
$schema = [
    'fields' => [
        [
            'fieldName' => 'id',
            'dataType' => 'Int64',
            'isPrimary' => true
        ],
        [
            'fieldName' => 'title',
            'dataType' => 'VarChar',
            'elementTypeParams' => ['max_length' => 200]
        ],
        [
            'fieldName' => 'content_vector',
            'dataType' => 'FloatVector',
            'elementTypeParams' => ['dim' => 768] // 768-dimensional vectors
        ]
    ]
];

// Define vector index
$indexParams = [
    [
        'fieldName' => 'content_vector',
        'indexName' => 'content_vector_index',
        'metricType' => 'L2'
    ]
];

// Create collection
$response = $milvus->collection()->createCollection(
    collectionName: 'my_documents',
    schema: $schema,
    indexParams: $indexParams
);

// Load collection into memory for operations
$milvus->collection()->loadCollection('my_documents');

echo "Collection 'my_documents' created successfully!\n";
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.