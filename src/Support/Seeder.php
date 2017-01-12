<?php

namespace Soda\Reports\Support;

use Illuminate\Database\Seeder as BaseSeeder;

class Seeder extends BaseSeeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $roleModel = app('soda.role.model');
        $permissionModel = app('soda.permission.model');

        $roleReporter = $roleModel->firstOrCreate([
            'name'         => 'reporter',
            'display_name' => 'Reporter',
            'description'  => 'Reporters have access to generate, read and export reports.',
        ]);

        $permissionAccessReports = $permissionModel->firstOrCreate([
            'name'         => 'view-reports',
            'display_name' => 'View Reports',
            'description'  => 'View, generate and export reports.',
            'category'     => 'Reports',
        ]);

        $roleReporter->attachPermissions([
            $permissionAccessReports,
        ]);

        if ($adminRole = $roleModel->whereName('admin')->first()) {
            $adminRole->attachPermission($permissionAccessReports);
        }
    }
}
