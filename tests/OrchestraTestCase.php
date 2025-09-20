<?php

namespace Voyanara\MilvusSdk\Tests;
use Orchestra\Testbench\TestCase as Orchestra;
use Voyanara\MilvusSdk\ServiceProvider;

class OrchestraTestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }


    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}