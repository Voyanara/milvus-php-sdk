<?php

namespace Voyanara\MilvusSdk\Requests\Vector;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class QueryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $collectionName,
        protected string $filter,
        protected ?string $dbName = null,
        protected ?array $outputFields = null,
        protected ?array $partitionNames = null,
        protected ?int $limit = null,
        protected ?int $offset = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/entities/query';
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
            'filter' => $this->filter,
            'dbName' => $this->dbName,
            'outputFields' => $this->outputFields,
            'partitionNames' => $this->partitionNames,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ], function ($value) {
            return $value !== null;
        });
    }
}
