<?php

namespace Voyanara\MilvusSdk\Tests\Unit;

use Voyanara\MilvusSdk\Milvus;
use Voyanara\MilvusSdk\Tests\TestCase;

class UserTest extends TestCase
{
    private Milvus $milvus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->milvus = new Milvus('root:Milvus', 'http://localhost', '19530');
    }

    public function test_list_users(): void
    {
        $response = $this->milvus->user()->list();
//        var_dump($response->body());
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_create_user(): void
    {
        $userName = 'test_user_' . time();
        
        $response = $this->milvus->user()->createUser($userName, '123123');

        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $describeResponse = $this->milvus->user()->describeUser($userName);
        $this->assertIsArray($describeResponse->json());
        $this->assertEquals(0, $describeResponse->json('code'));
        
        $dropResponse = $this->milvus->user()->dropUser($userName);
        $this->assertIsArray($dropResponse->json());
        $this->assertEquals(0, $dropResponse->json('code'));
    }

    public function test_describe_user(): void
    {
        $userName = 'test_describe_user_' . time();
        
        $this->milvus->user()->createUser($userName, 'P@ssw0rd123');
        
        $response = $this->milvus->user()->describeUser($userName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->user()->dropUser($userName);
    }

    public function test_drop_user(): void
    {
        $userName = 'test_drop_user_' . time();
        
        $this->milvus->user()->createUser($userName, 'P@ssw0rd123');
        
        $response = $this->milvus->user()->dropUser($userName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_grant_role(): void
    {
        $userName = 'test_grant_user_' . time();
        $roleName = 'test_grant_role_' . time();
        
        $this->milvus->user()->createUser($userName, 'P@ssw0rd123');
        $this->milvus->role()->createRole($roleName);
        
        $response = $this->milvus->user()->grantRole($userName, $roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->user()->dropUser($userName);
        $this->milvus->role()->dropRole($roleName);
    }

    public function test_revoke_role(): void
    {
        $userName = 'test_revoke_user_' . time();
        $roleName = 'test_revoke_role_' . time();
        
        $this->milvus->user()->createUser($userName, 'P@ssw0rd123');
        $this->milvus->role()->createRole($roleName);
        $this->milvus->user()->grantRole($userName, $roleName);
        
        $response = $this->milvus->user()->revokeRole($userName, $roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->user()->dropUser($userName);
        $this->milvus->role()->dropRole($roleName);
    }

    public function test_update_password(): void
    {
        $userName = 'test_user_' . time();
        $oldPassword = 'P@ssw0rd123';
        $newPassword = 'N3wP@ssw0rd!';
        
        $this->milvus->user()->createUser($userName, $oldPassword);

        $response = $this->milvus->user()->updatePassword($userName, $oldPassword, $newPassword);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));

        $this->milvus->user()->dropUser($userName);
    }
}
