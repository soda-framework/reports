<?php

namespace Soda\Reports\Support;

use Soda\Cms\Database\Models\Role;
use Soda\Cms\Database\Models\Permission;
use Illuminate\Database\Seeder as BaseSeeder;

class Seeder extends BaseSeeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $roleReporter = Role::firstOrCreate([
            'name'         => 'reporter',
            'display_name' => 'Reporter',
            'description'  => 'Reporters have access to generate, read and export reports.',
        ]);

        $permissionAccessReports = Permission::firstOrCreate([
            'name'         => 'view-reports',
            'display_name' => 'View Reports',
            'description'  => 'View, generate and export reports.',
            'category'     => 'Reports',
        ]);

        $roleReporter->attachPermissions([
            $permissionAccessReports,
        ]);

        if ($adminRole = Role::whereName('developer')->first()) {
            $adminRole->attachPermission($permissionAccessReports);
        }

        if ($adminRole = Role::whereName('admin')->first()) {
            $adminRole->attachPermission($permissionAccessReports);
        }
    }
}
