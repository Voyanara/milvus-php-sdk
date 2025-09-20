<?php

namespace Voyanara\MilvusSdk\Tests\LaravelTest;

use Voyanara\MilvusSdk\Facades\Milvus;
use Voyanara\MilvusSdk\Tests\OrchestraTestCase;

class UserTest extends OrchestraTestCase
{
    public function test_list_users(): void
    {
        $response = Milvus::user()->list();
//        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_create_user(): void
    {
        $userName = 'test_user_' . time();
        
        $response = Milvus::user()->createUser($userName, '123123');

        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));

        $describeResponse = Milvus::user()->describeUser($userName);
        $this->assertIsArray($describeResponse->json());
        $this->assertEquals(0, $describeResponse->json('code'));

        $dropResponse = Milvus::user()->dropUser($userName);
        $this->assertIsArray($dropResponse->json());
        $this->assertEquals(0, $dropResponse->json('code'));
    }

    public function test_describe_user(): void
    {
        $userName = 'test_describe_user_' . time();
        
        Milvus::user()->createUser($userName, 'P@ssw0rd123');
        
        $response = Milvus::user()->describeUser($userName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::user()->dropUser($userName);
    }

    public function test_drop_user(): void
    {
        $userName = 'test_drop_user_' . time();
        
        Milvus::user()->createUser($userName, 'P@ssw0rd123');
        
        $response = Milvus::user()->dropUser($userName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_grant_role(): void
    {
        $userName = 'test_grant_user_' . time();
        $roleName = 'test_grant_role_' . time();
        
        Milvus::user()->createUser($userName, 'P@ssw0rd123');
        Milvus::role()->createRole($roleName);
        
        $response = Milvus::user()->grantRole($userName, $roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::user()->dropUser($userName);
        Milvus::role()->dropRole($roleName);
    }

    public function test_revoke_role(): void
    {
        $userName = 'test_revoke_user_' . time();
        $roleName = 'test_revoke_role_' . time();
        
        Milvus::user()->createUser($userName, 'P@ssw0rd123');
        Milvus::role()->createRole($roleName);
        Milvus::user()->grantRole($userName, $roleName);
        
        $response = Milvus::user()->revokeRole($userName, $roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::user()->dropUser($userName);
        Milvus::role()->dropRole($roleName);
    }

    public function test_update_password(): void
    {
        $userName = 'test_user_' . time();
        $oldPassword = 'P@ssw0rd123';
        $newPassword = 'N3wP@ssw0rd!';
        
        Milvus::user()->createUser($userName, $oldPassword);

        $response = Milvus::user()->updatePassword($userName, $oldPassword, $newPassword);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));

        Milvus::user()->dropUser($userName);
    }
}