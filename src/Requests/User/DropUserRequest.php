<?php

namespace Voyanara\MilvusSdk\Requests\User;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Request for deleting an existing user
 */
class DropUserRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $userName The name of the target user
     */
    public function __construct(private readonly string $userName)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/users/drop';
    }

    protected function defaultBody(): array
    {
        return [
            'userName' => $this->userName,
        ];
    }
}
