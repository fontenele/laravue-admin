<?php

namespace Fontenele\Laravue\Commands;

use Illuminate\Console\Command;

class LaravueSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravue:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Laravue data';

    public function handle()
    {
        $this->call('db:seed', ['--class' => 'LaravueRolesSeeder']);
        $this->call('db:seed', ['--class' => 'LaravueMenusSeeder']);
        $this->call('db:seed', ['--class' => 'LaravueUsersSeeder']);
    }
}
