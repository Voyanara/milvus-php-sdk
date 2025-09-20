<?php

namespace Voyanara\MilvusSdk\Requests\Collection;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetCollectionLoadStateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $collectionName,
        protected ?string $dbName = null,
        protected ?string $partitionNames = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/collections/get_load_state';
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
            'dbName' => $this->dbName,
            'collectionName' => $this->collectionName,
            'partitionNames' => $this->partitionNames,
        ]);
    }
}
