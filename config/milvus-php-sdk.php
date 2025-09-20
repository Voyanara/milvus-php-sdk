<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Milvus PHP SDK Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file is for interfacing with the Milvus vector database
    | API v2 using the Milvus PHP SDK. Authentication is achieved through an API
    | token which can be either a simple token or username:password combination.
    |
    | For local development with Docker: use 'root:Milvus' as the token
    | For Zilliz Cloud: use 'db_randomstring:your_password' format
    |
    | The 'host' should include the protocol (http/https) and 'port' determines
    | the connection port, with defaults set for local development.
    |
    */

    'token' => env('MILVUS_TOKEN'),
    'host' => env('MILVUS_HOST', 'localhost'),
    'port' => env('MILVUS_PORT', '19530'),
];