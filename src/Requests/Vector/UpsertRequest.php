<?php

namespace Voyanara\MilvusSdk\Requests\Vector;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpsertRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $collectionName,
        protected array $data,
        protected ?string $dbName = null,
        protected ?string $partitionName = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/entities/upsert';
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
            'dbName' => $this->dbName,
            'partitionName' => $this->partitionName,
        ], function ($value) {
            return $value !== null;
        });
    }
}
