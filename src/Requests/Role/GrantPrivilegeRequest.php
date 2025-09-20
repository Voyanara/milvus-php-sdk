<?php

namespace Voyanara\MilvusSdk\Requests\Role;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GrantPrivilegeRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $roleName,
        protected readonly string $objectType,
        protected readonly string $objectName,
        protected readonly string $privilege,
    ){}

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/roles/grant_privilege';
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
            'objectType' => $this->objectType,
            'objectName' => $this->objectName,
            'privilege' => $this->privilege,
        ];
    }
}
