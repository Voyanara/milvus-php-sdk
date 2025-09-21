<?php

namespace Voyanara\MilvusSdk\Requests\Vector;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SearchRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $collectionName,
        protected array $data,
        protected string $annsField,
        protected ?string $dbName = null,
        protected ?string $filter = null,
        protected ?int $limit = null,
        protected ?int $offset = null,
        protected ?string $groupingField = null,
        protected ?array $outputFields = null,
        protected ?array $searchParams = null,
        protected ?array $partitionNames = null,
        protected ?string $consistencyLevel = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/entities/search';
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
            'data' => $this->data,
            'annsField' => $this->annsField,
            'dbName' => $this->dbName,
            'filter' => $this->filter,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'groupingField' => $this->groupingField,
            'outputFields' => $this->outputFields,
            'searchParams' => $this->searchParams,
            'partitionNames' => $this->partitionNames,
            'consistencyLevel' => $this->consistencyLevel,
        ], function ($value) {
            return $value !== null;
        });
    }
}
