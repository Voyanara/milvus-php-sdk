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

### Laravel Integration

This package includes Laravel service provider for seamless integration:

```bash
# After installation, publish the configuration file
php artisan milvus-php-sdk:install
```

This command will publish the configuration file to `config/milvus-php-sdk.php` where you can set your Milvus connection parameters.

## Roadmap

### ✅ Implemented Features

- **User Management** - Complete user operations (create, describe, drop, list, update password)
- **Role Management** - Full role-based access control (create, drop, describe, list, grant/revoke privileges)

### 🚧 In Development

- **Collection Management** - Collection operations and schema management
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