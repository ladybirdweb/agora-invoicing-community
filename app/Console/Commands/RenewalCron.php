<?php

namespace App\Console\Commands;
use App\Http\Controllers\Common\CronController;
use Illuminate\Console\Command;

class RenewalCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renewal:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new CronController();
        $controller->autoRenewal();
        $this->info('renewal:cron Command Run successfully!');
    }
}
