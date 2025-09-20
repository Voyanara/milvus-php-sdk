<?php

namespace Voyanara\MilvusSdk\Tests\LaravelTest;

use Voyanara\MilvusSdk\Tests\OrchestraTestCase;

class ConfigTest extends OrchestraTestCase
{
    public function test_config_file_is_loaded(): void
    {
        $this->assertTrue(config()->has('milvus-php-sdk.token'));
        $this->assertTrue(config()->has('milvus-php-sdk.host'));
        $this->assertTrue(config()->has('milvus-php-sdk.port'));
    }

    public function test_env_values_are_loaded(): void
    {
        $this->assertSame('root:Milvus', config('milvus-php-sdk.token'));
        $this->assertSame('127.0.0.1', config('milvus-php-sdk.host'));
        $this->assertSame('19530', config('milvus-php-sdk.port'));
    }
}