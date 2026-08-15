<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Permission slugs grouped by functional area, mirroring the module
     * breakdown in the site spec (Programs, Donations, Volunteers, etc.).
     */
    private const PERMISSION_GROUPS = [
        'content' => ['view', 'create', 'update', 'delete', 'publish'],
        'donations' => ['view', 'approve', 'reject', 'verify_payment', 'export'],
        'payment_methods' => ['manage'],
        'campaigns' => ['view', 'manage'],
        'volunteers' => ['view', 'approve', 'manage'],
        'members' => ['view', 'manage'],
        'projects' => ['view', 'manage'],
        'donors' => ['view', 'manage', 'export'],
        'moderation' => ['manage'],
        'users' => ['manage'],
        'roles' => ['manage'],
        'settings' => ['manage'],
        'seo' => ['manage'],
    ];

    /**
     * Role => permission-slug prefixes it's granted. A bare group name
     * grants every permission in that group; "group.action" grants one.
     */
    private const ROLE_GRANTS = [
        'Super Admin' => ['*'],
        'Admin' => [
            'content', 'donations', 'payment_methods', 'campaigns', 'volunteers', 'members',
            'projects', 'donors', 'moderation', 'users', 'settings', 'seo',
        ],
        'Editor' => ['content', 'moderation'],
        'Content Manager' => ['content', 'campaigns.view', 'campaigns.manage'],
        'Finance Manager' => [
            'donations', 'payment_methods', 'campaigns.view', 'donors.view', 'donors.export',
        ],
        'Volunteer Manager' => ['volunteers', 'members.view'],
        'Project Manager' => ['projects', 'content.view', 'content.update'],
        'Donor Manager' => ['donors', 'donations.view', 'donations.export', 'members'],
        'Moderator' => ['moderation', 'content.view'],
    ];

    public function run(): void
    {
        $permissions = [];

        foreach (self::PERMISSION_GROUPS as $group => $actions) {
            foreach ($actions as $action) {
                $permissions[] = "{$group}.{$action}";
            }
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        foreach (self::ROLE_GRANTS as $roleName => $grants) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if ($grants === ['*']) {
                $role->syncPermissions($permissions);

                continue;
            }

            $resolved = collect($grants)->flatMap(function (string $grant) use ($permissions) {
                if (str_contains($grant, '.')) {
                    return [$grant];
                }

                return collect($permissions)->filter(fn (string $permission) => str_starts_with($permission, "{$grant}."));
            })->unique()->values()->all();

            $role->syncPermissions($resolved);
        }
    }
}
