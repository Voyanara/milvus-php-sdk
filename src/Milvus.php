<?php

namespace Voyanara\MilvusSdk;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Voyanara\MilvusSdk\Endpoints\CollectionEndpoint;
use Voyanara\MilvusSdk\Endpoints\RoleEndpoint;
use Voyanara\MilvusSdk\Endpoints\UserEndpoint;

class Milvus extends Connector
{
    public function __construct(
        protected readonly ?string $token,
        protected readonly  string $host,
        protected readonly  string $port
    ) {
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token);
    }
    public function resolveBaseUrl(): string
    {
        return "{$this->host}:{$this->port}";
    }

    public function defaultConfig(): array
    {
        return [
            'verify' => false,
        ];
    }

    public function user(): UserEndpoint
    {
        return new UserEndpoint($this);
    }

    public function role(): RoleEndpoint
    {
        return new RoleEndpoint($this);
    }

    public function collection(): CollectionEndpoint
    {
        return new CollectionEndpoint($this);
    }
}