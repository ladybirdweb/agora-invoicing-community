<?php

namespace App\Console\Commands;

use App\Http\Controllers\Common\SettingsController;
use Illuminate\Console\Command;

class MoveImagesToStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage/images:move';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move storage/images from public folder to storage folder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new SettingsController();
        $controller->MoveImagesToStorage();
        $this->info('storage/images:move Command Run successfully!');
    }
}
