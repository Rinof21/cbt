<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            "view_dashboard",
            "manage_sessions",
            "start_session",
            "end_session",
            "manage_computers",
            "view_computers",
            "update_computer_status",
            "manage_issues",
            "create_issue",
            "update_issue",
            "view_reports",
            "export_reports",
            "manage_users",
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(["name" => $permission]);
        }

        $superAdmin = Role::firstOrCreate(["name" => "super_admin"]);
        $superAdmin->syncPermissions($permissions);

        $operatorRole = Role::firstOrCreate(["name" => "operator"]);
        $operatorRole->syncPermissions([
            "view_dashboard",
            "manage_sessions",
            "start_session",
            "end_session",
            "view_computers",
            "update_computer_status",
            "create_issue",
            "update_issue",
            "view_reports",
        ]);

        $admin = User::firstOrCreate(
            ["email" => "rino.f@untan.ac.id"],
            ["name" => "Administrator Lab CBT", "password" => Hash::make("password")]
        );
        $admin->assignRole("super_admin");

        $op = User::firstOrCreate(
            ["email" => "operator@id"],
            ["name" => "Teknisi IT Lab", "password" => Hash::make("password")]
        );
        $op->assignRole("operator");

        $this->command->info("Roles, permissions, and users seeded.");
        $this->command->info("Admin: admin@cbtlab.id / password");
        $this->command->info("Operator: operator@cbtlab.id / password");
    }
}