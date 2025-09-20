<?php

namespace Voyanara\MilvusSdk\Requests\User;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GrantRoleRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $userName,
        protected readonly string $roleName,
    ){}
    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/users/grant_role';
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
            'userName' => $this->userName,
            'roleName' => $this->roleName,
        ];
    }
}