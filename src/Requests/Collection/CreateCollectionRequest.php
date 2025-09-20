<?php

namespace Voyanara\MilvusSdk\Requests\Collection;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateCollectionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string  $collectionName,
        protected ?string $dbName = null,
        protected ?array  $schema = null,
        protected ?array  $indexParams = null,
        protected ?array  $params = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/collections/create';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function defaultBody(): array
    {
        return array_filter([
            'collectionName' => $this->collectionName,
            'dbName' => $this->dbName,
            'schema' => $this->schema,
            'indexParams' => $this->indexParams,
            'params' => $this->params,
        ]);
    }
}