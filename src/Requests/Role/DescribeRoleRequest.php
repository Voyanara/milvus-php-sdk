<?php

namespace Voyanara\MilvusSdk\Requests\Role;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DescribeRoleRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;
    public function __construct(
        protected readonly string $roleName,
    ){}
    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/roles/describe';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

        public function defaultBody(): array
    {
        return [
            'roleName' => $this->roleName,
        ];
    }
}