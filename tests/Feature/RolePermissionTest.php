<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        $this->seed();
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $user = User::where(
            'email',
            'admin@peoplehub.test'
        )->firstOrFail();

        $this->assertTrue(
            $user->can('payroll.manage')
        );

        $this->assertTrue(
            $user->can('employees.delete')
        );
    }

    public function test_hr_admin_can_manage_employees(): void
    {
        $user = User::where(
            'email',
            'hr@peoplehub.test'
        )->firstOrFail();

        $this->assertTrue(
            $user->can('employees.create')
        );

        $this->assertFalse(
            $user->can('users.manage')
        );
    }

    public function test_manager_cannot_manage_payroll(): void
    {
        $user = User::where(
            'email',
            'manager@peoplehub.test'
        )->firstOrFail();

        $this->assertTrue(
            $user->can('leaves.approve')
        );

        $this->assertFalse(
            $user->can('payroll.manage')
        );
    }

    public function test_employee_has_limited_permissions(): void
    {
        $user = User::where(
            'email',
            'employee@peoplehub.test'
        )->firstOrFail();

        $this->assertTrue(
            $user->can('leaves.create')
        );

        $this->assertFalse(
            $user->can('employees.create')
        );
    }
}