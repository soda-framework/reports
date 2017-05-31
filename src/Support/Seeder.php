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
        $role_reporter = Role::create([
            'name'         => 'reporter',
            'display_name' => 'Reporter',
            'description'  => 'Reporters have access to generate, read and export reports.',
        ]);

        $permission_access_reports = Permission::create([
            'name'         => 'view-reports',
            'display_name' => 'View Reports',
            'description'  => 'View, generate and export reports.',
        ]);

        foreach (['developer', 'super-admin', 'admin', 'reporter'] as $roleName) {
            if ($role = Role::where('name', $roleName)->first()) {
                $role->attachPermissions([
                    $permission_access_reports,
                ]);
            }
        }
    }
}
