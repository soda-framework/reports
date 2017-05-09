<?php

namespace Soda\Reports\Console;

use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'soda:reports:install';
    protected $description = 'Install the Soda Reports module';

    /**
     * Runs all database migrations for Soda Reports.
     */
    public function handle()
    {
        $this->call('migrate', [
            '--path' => '/vendor/soda-framework/reports/migrations',
        ]);

        $this->call('db:seed', [
            '--class' => 'Soda\\Reports\\Support\\InstallPermissions',
        ]);
    }
}
