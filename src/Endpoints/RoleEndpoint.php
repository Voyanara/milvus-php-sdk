<?php

namespace Voyanara\MilvusSdk\Endpoints;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Voyanara\MilvusSdk\Enums\Privilege;
use Voyanara\MilvusSdk\Requests\Role\CreateRoleRequest;
use Voyanara\MilvusSdk\Requests\Role\DescribeRoleRequest;
use Voyanara\MilvusSdk\Requests\Role\DropRoleRequest;
use Voyanara\MilvusSdk\Requests\Role\GrantPrivilegeRequest;
use Voyanara\MilvusSdk\Requests\Role\GrantPrivilegeV2Request;
use Voyanara\MilvusSdk\Requests\Role\ListRequest;
use Voyanara\MilvusSdk\Requests\Role\RevokePrivilegeV2Request;

class RoleEndpoint  extends BaseResource
{

    /**
     * List Roles - This operation lists the information about all existing roles.
     *
     * @return Response A success response with response code and array of role data
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": [
     *         "admin",
     *         "public"
     *     ]
     * }
     */
    public function list(): Response
    {
        return $this->connector->send(new ListRequest);
    }

    /**
     * Describe Role - This operation describes the details of a specified role.
     *
     * @param string $roleName The name of the role
     * @return Response A success response with response code and array of privilege items
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": [
     *         {
     *             "dbName": "*",
     *             "grantor": "root",
     *             "objectName": "*",
     *             "objectType": "Collection",
     *             "privilege": "IndexDetail"
     *         }
     *     ]
     * }
     */
    public function describeRole(string $roleName): Response
    {
        return $this->connector->send(new DescribeRoleRequest($roleName));
    }

    /**
     * Create Role - This operation creates a role.
     *
     * @param string $roleName The name of the role
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function createRole(string $roleName): Response
    {
        return $this->connector->send(new CreateRoleRequest($roleName));
    }

    /**
     * Grant Privilege To Role - This operation grants a privilege to the current role.
     *
     * @param string $roleName The name of the role
     * @param string $objectType The type of the object to which the privilege belongs
     * @param string $objectName The name of the object to which the role is granted the specified privilege
     * @param string $privilege The privilege that is granted to the role
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function grantPrivilege(string $roleName, string $objectType, string $objectName, string $privilege): Response
    {
        return $this->connector->send(new GrantPrivilegeRequest($roleName, $objectType, $objectName, $privilege));
    }

    /**
     * Grant Privilege To Role V2 - This operation grants a privilege to the specified role using V2 API.
     *
     * @param string $roleName The name of the role
     * @param Privilege $privilege The privilege enum that is granted to the role
     * @param string $collectionName The name of the collection
     * @param string|null $dbName The name of the database (optional, defaults to null)
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function grantPrivilegeV2(string $roleName, Privilege $privilege, string $collectionName, ?string $dbName = null): Response
    {
        return $this->connector->send(new GrantPrivilegeV2Request($roleName, $privilege, $collectionName, $dbName));
    }

    /**
     * Revoke Privilege From Role V2 - This operation revokes a privilege from the specified role using V2 API.
     *
     * @param string $roleName The name of the role
     * @param Privilege $privilege The privilege enum that is revoked from the role
     * @param string $collectionName The name of the collection
     * @param string|null $dbName The name of the database (optional, defaults to null)
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function revokePrivilegeV2(string $roleName, Privilege $privilege, string $collectionName, ?string $dbName = null): Response
    {
        return $this->connector->send(new RevokePrivilegeV2Request($roleName, $privilege, $collectionName, $dbName));
    }

    /**
     * Drop Role - This operation drops an existing role. The operation will succeed if the specified role exists. Otherwise, this operation will fail.
     *
     * @param string $roleName The name of the role
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function dropRole(string $roleName): Response
    {
        return $this->connector->send(new DropRoleRequest($roleName));
    }
}