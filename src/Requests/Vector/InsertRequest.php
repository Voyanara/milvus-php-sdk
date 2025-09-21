<?php

namespace Voyanara\MilvusSdk\Requests\Vector;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class InsertRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $collectionName,
        protected array $data,
        protected ?string $partitionName = null,
        protected ?string $dbName = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/entities/insert';
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
            'partitionName' => $this->partitionName,
            'dbName' => $this->dbName,
        ]);
    }
}
