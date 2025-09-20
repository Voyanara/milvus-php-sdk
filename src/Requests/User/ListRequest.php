<?php

namespace Voyanara\MilvusSdk\Requests\User;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ListRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/users/list';
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
            '' => ''
        ];
    }
}