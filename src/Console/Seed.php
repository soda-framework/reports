<?php

namespace Soda\Reports\Console;

use Illuminate\Console\Command;

class Seed extends Command
{
    protected $signature = 'soda:reports:seed';
    protected $description = 'Seed the Soda Reports Database';

    /**
     * Runs seeds for Soda Reports.
     */
    public function handle()
    {
        $this->call('db:seed', [
            '--class' => 'Soda\\Reports\\Support\\Seeder',
        ]);
    }
}
