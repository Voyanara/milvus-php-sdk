<?php

namespace Voyanara\MilvusSdk\Requests\Collection;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DropCollectionPropertiesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $collectionName,
        protected array $propertyKeys,
        protected ?string $dbName = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/collections/drop_properties';
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
            'propertyKeys' => $this->propertyKeys,
        ]);
    }
}
