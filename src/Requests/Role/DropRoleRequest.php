<?php

namespace Voyanara\MilvusSdk\Requests\Role;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Request for dropping an existing role
 */
class DropRoleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string $roleName The name of the role
     */
    public function __construct(private readonly string $roleName)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/roles/drop';
    }

    protected function defaultBody(): array
    {
        return [
            'roleName' => $this->roleName,
        ];
    }
}
