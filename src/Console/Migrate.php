<?php

namespace Soda\Reports\Console;

use Illuminate\Console\Command;

class Migrate extends Command
{

    protected $signature = 'soda:reports:migrate';
    protected $description = 'Migrate the Soda Reports Database';

    /**
     * Runs all database migrations for Soda Reports
     */
    public function handle()
    {
        $this->call('migrate', [
            '--path' => '/vendor/soda-framework/reports/migrations',
        ]);
    }
}
