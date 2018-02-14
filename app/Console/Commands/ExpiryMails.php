<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Common\CronController;
use Exception;
class ExpiryMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Expiry Mails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    try
        {
        $mail= new CronController ;
        loging('sending-expiry-mail', 'Mail is sent', 'info') ; 
        $this->info("Mail Sent");

        }
        catch (Exception $ex){
        $this->error($ex->getMessage());

        }
    }
}
