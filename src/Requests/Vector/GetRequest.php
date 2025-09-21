<?php

namespace Voyanara\MilvusSdk\Requests\Vector;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $collectionName,
        protected array|int|string $id,
        protected ?array $outputFields = null,
        protected ?array $partitionNames = null,
        protected ?string $dbName = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/entities/get';
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
            'id' => $this->id,
            'outputFields' => $this->outputFields,
            'partitionNames' => $this->partitionNames,
            'dbName' => $this->dbName,
        ]);
    }
}
