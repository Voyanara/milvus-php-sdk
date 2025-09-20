<?php

namespace Voyanara\MilvusSdk\Endpoints;

use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use Voyanara\MilvusSdk\Requests\User\CreateUserRequest;
use Voyanara\MilvusSdk\Requests\User\DescribeUserRequest;
use Voyanara\MilvusSdk\Requests\User\DropUserRequest;
use Voyanara\MilvusSdk\Requests\User\GrantRoleRequest;
use Voyanara\MilvusSdk\Requests\User\ListRequest;
use Voyanara\MilvusSdk\Requests\User\RevokeRoleRequest;
use Voyanara\MilvusSdk\Requests\User\UpdatePasswordRequest;

class UserEndpoint  extends BaseResource
{

    /**
     * Create User - This operation creates a new user with a corresponding password.
     *
     * @param string $userName The name of the target user. The value should start with a letter and can only contain underline, letters and numbers
     * @param string $password The corresponding password to the user. The password must include at least three of the following character types: uppercase letters, lowercase letters, numbers, and special characters
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function createUser(string $userName, string $password): Response
    {
        return $this->connector->send(new CreateUserRequest($userName, $password));
    }

    /**
     * Describe User - This operation describes the detailed information of a specific user.
     *
     * @param string $userName The name of the target user. The value should start with a letter and can only contain underline, letters and numbers
     * @return Response A success response with response code and array of roles assigned to the user
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
    public function describeUser(string $userName): Response
    {
        return $this->connector->send(new DescribeUserRequest($userName));
    }

    /**
     * List Users - This operation lists the information about all existing users.
     *
     * @return Response A success response with response code and array of user data
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": [
     *         "root",
     *         "user1"
     *     ]
     * }
     */
    public function list(): Response
    {
        return $this->connector->send(new ListRequest);
    }

    /**
     * Grant Role To User - This operation grants a specified role to the current user. Once granted the role, the user gets permissions allowed for the current role and can perform certain operations.
     *
     * @param string $userName The name of the target user. The value should start with a letter and can only contain underline, letters and numbers
     * @param string $roleName The name of the target role
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function grantRole(string $userName, string $roleName): Response
    {
        return $this->connector->send(new GrantRoleRequest($userName, $roleName));
    }

    /**
     * Revoke Role From User - This operation revokes a privilege granted to the current role.
     *
     * @param string $userName The name of the target user. The value should start with a letter and can only contain underline, letters and numbers
     * @param string $roleName The name of the target role
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function revokeRole(string $userName, string $roleName): Response
    {
        return $this->connector->send(new RevokeRoleRequest($userName, $roleName));
    }

    /**
     * Update User Password - This operation updates the password for a specific user.
     *
     * @param string $userName The name of the target user. The value should start with a letter and can only contain underline, letters and numbers
     * @param string $password The corresponding password to the user. The password must be a string of 8 to 64 characters and must include at least three of the following character types: uppercase letters, lowercase letters, numbers, and special characters
     * @param string $newPassword The new password for the user. The password must be a string of 8 to 64 characters and must include at least three of the following character types: uppercase letters, lowercase letters, numbers, and special characters
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function updatePassword(string $userName, string $password, string $newPassword): Response
    {
        return $this->connector->send(new UpdatePasswordRequest($userName, $password, $newPassword));
    }

    /**
     * Drop User - This operation deletes an existing user.
     *
     * @param string $userName The name of the target user. The value should start with a letter and can only contain underline, letters and numbers
     * @return Response A success response with response code and empty data object
     * 
     * Example response:
     * {
     *     "code": 0,
     *     "data": {}
     * }
     */
    public function dropUser(string $userName): Response
    {
        return $this->connector->send(new DropUserRequest($userName));
    }
}