<?php

namespace App\Console;

use App\Model\Common\StatusSetting;
use App\Model\Mailjob\ActivityLogDay;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Torann\Currency\Console\Manage as CurrencyManage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
         'App\Console\Commands\Inspire',
         \App\Console\Commands\Install::class,
          CurrencyManage::class,
        'App\Console\Commands\ExpiryCron',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->execute($schedule, 'expiryMail');
        $this->execute($schedule, 'deleteLogs');
    }

    public function execute($schedule, $task)
    {
        $env = base_path('.env');
        if (\File::exists($env) && (env('DB_INSTALL') == 1)) {
            $expiryMailStatus = StatusSetting::pluck('expiry_mail')->first();
            $logDeleteStatus = StatusSetting::pluck('activity_log_delete')->first();
            $delLogDays = ActivityLogDay::pluck('days')->first();
            if ($delLogDays == null) {
                $delLogDays = 99999999;
            }
            \Config::set('activitylog.delete_records_older_than_days', $delLogDays);
            $condition = new \App\Model\Mailjob\Condition();
            $command = $condition->getConditionValue($task);
            switch ($task) {
            case 'expiryMail':
               if ($expiryMailStatus == 1) {
                   return $this->getCondition($schedule->command('expiry:notification'), $command);
               }

            case 'deleteLogs':
             if ($logDeleteStatus == 1) {
                 return $this->getCondition($schedule->command('activitylog:clean'), $command);
             }
            }
        }
    }

    public function getCondition($schedule, $command)
    {
        $condition = $command['condition'];
        $at = $command['at'];
        switch ($condition) {
            case 'everyMinute':
                return $schedule->everyMinute();
            case 'everyFiveMinutes':
                return $schedule->everyFiveMinutes();
            case 'everyTenMinutes':
                return $schedule->everyTenMinutes();
            case 'everyThirtyMinutes':
                return $schedule->everyThirtyMinutes();
            case 'hourly':
                return $schedule->hourly();
            case 'daily':
                return $schedule->daily();
            case 'dailyAt':
                return $this->getConditionWithOption($schedule, $condition, $at);
            case 'weekly':
                return $schedule->weekly();
            case 'monthly':
                return $schedule->monthly();
            case 'yearly':
                return $schedule->yearly();
            default:
                return $schedule->everyMinute();
        }
    }

    public function getConditionWithOption($schedule, $command, $at)
    {
        switch ($command) {
            case 'dailyAt':
                return $schedule->dailyAt($at);
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
