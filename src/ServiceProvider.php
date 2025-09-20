<?php

namespace Voyanara\MilvusSdk;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
class ServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package->name('milvus-php-sdk')->hasConfigFile()
        ->hasInstallCommand(function (InstallCommand $command) {
            $command
                ->publishConfigFile()
                ->askToStarRepoOnGitHub('Voyanara/milvus-php-sdk');
        });

    }

    public function packageBooted(): void
    {

        $this->app->bind(Milvus::class, function () {
            return new Milvus(
                token: config('milvus-php-sdk.token'),
                host: config('milvus-php-sdk.host'),
                port: config('milvus-php-sdk.port'),
            );
        });
    }
}
//php artisan vendor:publish --tag=milvus-php-sdk-config
//vendor/bin/testbench vendor:publish --tag=milvus-php-sdk-config