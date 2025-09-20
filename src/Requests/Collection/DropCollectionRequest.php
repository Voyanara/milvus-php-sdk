<?php

namespace Voyanara\MilvusSdk\Requests\Collection;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DropCollectionRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $collectionName,
        protected readonly ?string $dbName = null,
    ){}
    
    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/collections/drop';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function defaultBody(): array
    {
        $body = [
            'collectionName' => $this->collectionName,
        ];

        if ($this->dbName !== null) {
            $body['dbName'] = $this->dbName;
        }

        return $body;
    }
}
