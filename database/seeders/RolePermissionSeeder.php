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
        'events' => ['view', 'create', 'update', 'delete', 'publish'],
        'donations' => ['view', 'approve', 'reject', 'verify_payment', 'export'],
        'payment_methods' => ['view', 'manage'],
        'campaigns' => ['view', 'manage'],
        'volunteers' => ['view', 'approve', 'manage'],
        'members' => ['view', 'manage'],
        'projects' => ['view', 'manage'],
        'donors' => ['view', 'manage', 'export'],
        'moderation' => ['manage'],
        'users' => ['view', 'manage'],
        'roles' => ['manage'],
        'settings' => ['manage'],
        'seo' => ['manage'],
    ];

    /**
     * Role => permission-slug prefixes it's granted. A bare group name
     * grants every permission in that group; "group.action" grants one.
     *
     * Roles below are deliberately scoped to real functional areas of the
     * organization (accounting, events, governance oversight, etc.) rather
     * than broad catch-alls, so day-to-day access matches actual
     * responsibility - separation of duties for financial transparency.
     */
    private const ROLE_GRANTS = [
        'Super Admin' => ['*'],
        'Admin' => [
            'content', 'events', 'donations', 'payment_methods', 'campaigns', 'volunteers', 'members',
            'projects', 'donors', 'moderation', 'users', 'settings', 'seo',
        ],
        'Editor' => ['content', 'moderation'],
        'Content Manager' => ['content', 'campaigns.view', 'campaigns.manage'],
        'Finance Manager' => [
            'donations', 'payment_methods', 'campaigns.view', 'donors.view', 'donors.export',
        ],
        // Read-only financial oversight - can review and export donation/payment
        // records for bookkeeping without being able to alter payment method
        // credentials or process donations, matching a real accountant's scope.
        'Accountant' => [
            'donations.view', 'donations.export', 'payment_methods.view',
            'campaigns.view', 'donors.view', 'donors.export',
        ],
        'Event Manager' => ['events', 'content.view'],
        'Volunteer Manager' => ['volunteers', 'members.view'],
        'Project Manager' => ['projects', 'content.view', 'content.update'],
        'Donor Manager' => ['donors', 'donations.view', 'donations.export', 'members'],
        'Moderator' => ['moderation', 'content.view'],
        // Read-only governance/oversight seat: can see financial reports, the
        // activity log, campaign and project status, and volunteer/member
        // counts, without any ability to create, edit, or delete records.
        'Board Member' => [
            'donations.view', 'campaigns.view', 'projects.view', 'volunteers.view',
            'members.view', 'donors.view', 'users.view',
        ],
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
