<?php

namespace Voyanara\MilvusSdk\Tests\PackageTest;

use Voyanara\MilvusSdk\Enums\Privilege;
use Voyanara\MilvusSdk\Milvus;
use Voyanara\MilvusSdk\Tests\TestCase;

class RoleTest extends TestCase
{
    private Milvus $milvus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->milvus = new Milvus('root:Milvus', 'http://localhost', '19530');
    }

    public function test_list_roles(): void
    {
        $response = $this->milvus->role()->list();
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_create_role(): void
    {
        $roleName = 'test_role_' . time();
        
        $response = $this->milvus->role()->createRole($roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $describeResponse = $this->milvus->role()->describeRole($roleName);
        $this->assertIsArray($describeResponse->json());
        $this->assertEquals(0, $describeResponse->json('code'));
        
        $dropResponse = $this->milvus->role()->dropRole($roleName);
        $this->assertIsArray($dropResponse->json());
        $this->assertEquals(0, $dropResponse->json('code'));
    }

    public function test_describe_role(): void
    {
        $roleName = 'test_describe_role_' . time();
        
        $this->milvus->role()->createRole($roleName);
        
        $response = $this->milvus->role()->describeRole($roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $this->milvus->role()->dropRole($roleName);
    }

    public function test_drop_role(): void
    {
        $roleName = 'test_drop_role_' . time();
        
        $this->milvus->role()->createRole($roleName);
        
        $response = $this->milvus->role()->dropRole($roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_grant_privilege(): void
    {
        $roleName = 'test_grant_role_' . time();
        
        $this->milvus->role()->createRole($roleName);
        
        $response = $this->milvus->role()->grantPrivilege($roleName, 'Global', '*', 'CollectionAdmin');
        $this->assertIsArray($response->json());
        
        $this->milvus->role()->dropRole($roleName);
    }

    public function test_grant_privilege_v2(): void
    {
        $roleName = 'test_grant_v2_role_' . time();
        
        $this->milvus->role()->createRole($roleName);
        
        $response = $this->milvus->role()->grantPrivilegeV2($roleName, Privilege::DatabaseAdmin, '*');
        $this->assertIsArray($response->json());
        
        $this->milvus->role()->dropRole($roleName);
    }

    public function test_revoke_privilege_v2(): void
    {
        $roleName = 'test_revoke_v2_role_' . time();
        
        $this->milvus->role()->createRole($roleName);
        $this->milvus->role()->grantPrivilegeV2($roleName, Privilege::DatabaseAdmin, '*');
        
        $response = $this->milvus->role()->revokePrivilegeV2($roleName, Privilege::DatabaseAdmin, '*');
        $this->assertIsArray($response->json());
        
        $this->milvus->role()->dropRole($roleName);
    }
}
