<?php

namespace Voyanara\MilvusSdk\Tests\LaravelTest;

use Voyanara\MilvusSdk\Enums\Privilege;
use Voyanara\MilvusSdk\Facades\Milvus;
use Voyanara\MilvusSdk\Tests\OrchestraTestCase;

class RoleTest extends OrchestraTestCase
{
    public function test_list_roles(): void
    {
        $response = Milvus::role()->list();
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_create_role(): void
    {
        $roleName = 'test_role_' . time();
        
        $response = Milvus::role()->createRole($roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        $describeResponse = Milvus::role()->describeRole($roleName);
        $this->assertIsArray($describeResponse->json());
        $this->assertEquals(0, $describeResponse->json('code'));
        
        $dropResponse = Milvus::role()->dropRole($roleName);
        $this->assertIsArray($dropResponse->json());
        $this->assertEquals(0, $dropResponse->json('code'));
    }

    public function test_describe_role(): void
    {
        $roleName = 'test_describe_role_' . time();
        
        Milvus::role()->createRole($roleName);
        
        $response = Milvus::role()->describeRole($roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
        
        Milvus::role()->dropRole($roleName);
    }

    public function test_drop_role(): void
    {
        $roleName = 'test_drop_role_' . time();
        
        Milvus::role()->createRole($roleName);
        
        $response = Milvus::role()->dropRole($roleName);
        $this->assertIsArray($response->json());
        $this->assertEquals(0, $response->json('code'));
    }

    public function test_grant_privilege(): void
    {
        $roleName = 'test_grant_role_' . time();
        
        Milvus::role()->createRole($roleName);
        
        $response = Milvus::role()->grantPrivilege($roleName, 'Global', '*', 'CollectionAdmin');
        $this->assertIsArray($response->json());
        
        Milvus::role()->dropRole($roleName);
    }

    public function test_grant_privilege_v2(): void
    {
        $roleName = 'test_grant_v2_role_' . time();
        
        Milvus::role()->createRole($roleName);
        
        $response = Milvus::role()->grantPrivilegeV2($roleName, Privilege::DatabaseAdmin, '*');
        $this->assertIsArray($response->json());
        
        Milvus::role()->dropRole($roleName);
    }

    public function test_revoke_privilege_v2(): void
    {
        $roleName = 'test_revoke_v2_role_' . time();
        
        Milvus::role()->createRole($roleName);
        Milvus::role()->grantPrivilegeV2($roleName, Privilege::DatabaseAdmin, '*');
        
        $response = Milvus::role()->revokePrivilegeV2($roleName, Privilege::DatabaseAdmin, '*');
        $this->assertIsArray($response->json());
        
        Milvus::role()->dropRole($roleName);
    }
}
