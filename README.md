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

### 🚧 In Development

- **Vector Operations** - Insert, search, query, update, and delete vector data

### 📋 Planned Features

- **Alias Management** - Collection alias operations  
- **Database Management** - Multi-database support
- **Import Operations** - Bulk data import functionality
- **Index Management** - Vector index creation and optimization
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
```