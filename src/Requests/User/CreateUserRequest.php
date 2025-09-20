<?php

namespace Voyanara\MilvusSdk\Requests\User;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateUserRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $userName,
        protected readonly string $password,
    ){}
    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/users/create';
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
            'password' => $this->password,
        ];
    }
}