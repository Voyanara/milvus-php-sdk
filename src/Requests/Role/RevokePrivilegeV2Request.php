<?php

namespace Voyanara\MilvusSdk\Requests\Role;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Voyanara\MilvusSdk\Enums\Privilege;

class RevokePrivilegeV2Request extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $roleName,
        protected readonly Privilege $privilege,
        protected readonly string $collectionName,
        protected readonly ?string $dbName = null,
    ){}

    public function resolveEndpoint(): string
    {
        return '/v2/vectordb/roles/revoke_privilege_v2';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function defaultBody(): array
    {
        $body = [
            'roleName' => $this->roleName,
            'privilege' => $this->privilege->value,
            'collectionName' => $this->collectionName,
        ];

        if ($this->dbName !== null) {
            $body['dbName'] = $this->dbName;
        }

        return $body;
    }
}
