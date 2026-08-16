<?php

namespace App\Http\Controllers;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployOnceController extends Controller
{
    public function __invoke(Request $request, string $secret)
    {
        if (! hash_equals((string) env('DEPLOY_SECRET'), $secret)) {
            abort(404);
        }

        $log = [];

        Artisan::call('key:generate', ['--force' => true]);
        $log[] = Artisan::output();

        Artisan::call('migrate', ['--force' => true]);
        $log[] = Artisan::output();

        Artisan::call('storage:link');
        $log[] = Artisan::output();

        Artisan::call('db:seed', ['--class' => RolePermissionSeeder::class, '--force' => true]);
        $log[] = Artisan::output();

        $admin = User::firstOrCreate(
            ['email' => env('DEPLOY_ADMIN_EMAIL')],
            ['name' => env('DEPLOY_ADMIN_NAME', 'Admin'), 'password' => bcrypt(env('DEPLOY_ADMIN_PASSWORD'))]
        );
        $admin->assignRole('Super Admin');
        $log[] = "Admin user ready: {$admin->email}";

        Artisan::call('config:cache');
        $log[] = Artisan::output();

        Artisan::call('route:cache');
        $log[] = Artisan::output();

        Artisan::call('view:cache');
        $log[] = Artisan::output();

        Artisan::call('event:cache');
        $log[] = Artisan::output();

        return response('<pre>'.e(implode("\n", $log)).'</pre>');
    }
}
