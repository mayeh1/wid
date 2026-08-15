<?php

namespace App\Filament\Concerns;

use Illuminate\Support\Facades\Auth;

/**
 * Gates a Filament resource's CRUD actions with the Spatie permissions
 * seeded in RolePermissionSeeder. Super Admin always passes via the
 * Gate::before hook in AppServiceProvider, so this only ever restricts
 * everyone else.
 *
 * Each resource sets `protected static string $permissionGroup = '...';`
 * matching one of the groups in RolePermissionSeeder::PERMISSION_GROUPS.
 * Groups with granular actions (view/create/update/delete) are checked
 * per-action; groups that only have a single "manage" permission fall
 * back to it for every action.
 */
trait AuthorizesViaPermissions
{
    public static function canViewAny(): bool
    {
        return static::userCan(['view', 'manage']);
    }

    public static function canCreate(): bool
    {
        return static::userCan(['create', 'manage']);
    }

    public static function canEdit($record): bool
    {
        return static::userCan(['update', 'manage']);
    }

    public static function canDelete($record): bool
    {
        return static::userCan(['delete', 'manage']);
    }

    protected static function userCan(array $actions): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        foreach ($actions as $action) {
            if ($user->can(static::$permissionGroup.'.'.$action)) {
                return true;
            }
        }

        return false;
    }
}
