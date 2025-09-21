<?php

namespace Voyanara\MilvusSdk\Requests\Vector;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class HybridSearchRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $collectionName,
        protected array $search,
        protected array $rerank,
        protected int $limit,
        protected ?string $dbName = null,
        protected ?array $partitionNames = null,
        protected ?array $outputFields = null,
        protected ?string $consistencyLevel = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/entities/hybrid_search';
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
            'search' => $this->search,
            'rerank' => $this->rerank,
            'limit' => $this->limit,
            'dbName' => $this->dbName,
            'partitionNames' => $this->partitionNames,
            'outputFields' => $this->outputFields,
            'consistencyLevel' => $this->consistencyLevel,
        ], function ($value) {
            return $value !== null;
        });
    }
}
