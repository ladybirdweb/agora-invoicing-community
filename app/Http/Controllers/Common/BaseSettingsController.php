<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Order\ExtendedOrderController;
use App\Model\Common\StatusSetting;
use App\Model\Mailjob\ActivityLogDay;
use App\Model\Mailjob\ExpiryMailDay;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class BaseSettingsController extends Controller
{
    /**
     * Get the logged activity.
     */
    public function getNewEntry($properties, $model)
    {
        $properties = (array_key_exists('attributes', $properties->toArray()))
        ? ($model->properties['attributes']) : null;

        $display = [];

        if ($properties != null) {
            if (array_key_exists('parent', $properties)) {
                unset($properties['parent']);
            }
            foreach ($properties as $key => $value) {
                $display[] = '<strong>'.'ucfirst'($key).'</strong>'.' : '.$value.'<br/>';
            }
            $updated = (count($properties) > 0) ? implode('', $display) : '--';

            return $updated;
        } else {
            return '--';
        }
    }

    /**
     * Get the older Entries.
     */
    public function getOldEntry($data, $model)
    {
        $oldData = '';
        $oldData = (array_key_exists('old', $data->toArray())) ? ($model->properties['old']) : null;
        if ($oldData != null) {
            if ((count($oldData) > 0)) {
                foreach ($oldData as $key => $value) {
                    $display[] = '<strong>'.'ucfirst'($key).'</strong>'.' : '.$value.'<br/>';
                }
            }
            $old = (count($oldData) > 0) ? implode('', $display) : '--';

            return $old;
        } else {
            return '--';
        }
    }

    /**
     * Get Date.
     */
    public function getDate($dbdate)
    {
        $created = new DateTime($dbdate);
        $tz = \Auth::user()->timezone()->first()->name;
        $created->setTimezone(new DateTimeZone($tz));
        $date = $created->format('M j, Y, g:i a '); //5th October, 2018, 11:17PM
        $newDate = $date;

        return $newDate;
    }

    public function destroyEmail(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $email = \DB::table('email_log')->where('id', $id)->delete();
                    if ($email) {
                        // $email->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>

                        <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                        /* @scrutinizer ignore-type */     \Lang::get('message.failed').'

                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                    </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                        <i class='fa fa-ban'></i>
                        <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '
                        ./* @scrutinizer ignore-type */\Lang::get('message.success').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */ \Lang::get('message.deleted-successfully').'
                    </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>
                        <b>"./* @scrutinizer ignore-type */ \Lang::get('message.alert').
                        '!</b> './* @scrutinizer ignore-type */\Lang::get('message.failed').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */ \Lang::get('message.select-a-row').'
                    </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>
                        <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                        /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            '.$e->getMessage().'
                    </div>';
        }
    }

    public function advanceSearch($from = '', $till = '', $delFrom = '', $delTill = '')
    {
        $join = new Activity();
        if ($from) {
            $from = $this->getDateFormat($from);
            $tills = $this->getDateFormat();
            $tillDate = (new ExtendedOrderController())->getTillDate($from, $till, $tills);
            $join = $join->whereBetween('created_at', [$from, $tillDate]);
        }
        if ($till) {
            $till = $this->getDateFormat($till);
            $froms = Activity::first()->created_at;
            $fromDate = (new ExtendedOrderController())->getFromDate($from, $froms);
            $join = $join->whereBetween('created_at', [$fromDate, $till]);
        }
        if ($delFrom) {
            $from = $this->getDateFormat($delFrom);
            $tills = $this->getDateFormat();
            $tillDate = (new ExtendedOrderController())->getTillDate($from, $delTill, $tills);
            $join->whereBetween('created_at', [$from, $tillDate])->delete();
        }
        if ($delTill) {
            $till = $this->getDateFormat($delTill);
            $froms = Activity::first()->created_at;
            $fromDate = (new ExtendedOrderController())->getFromDate($delFrom, $froms);
            $join->whereBetween('created_at', [$fromDate, $till])->delete();
        }
        $join = $join->orderBy('created_at', 'desc')
        ->select('id', 'log_name', 'description',
                'subject_id', 'subject_type', 'causer_id', 'properties', 'created_at');

        return $join;
    }

    public function getDateFormat($dbdate = '')
    {
        $created = new DateTime($dbdate);
        $tz = \Auth::user()->timezone()->first()->name;
        $created->setTimezone(new DateTimeZone($tz));
        $date = $created->format('Y-m-d H:m:i');

        return $date;
    }

    public function getScheduler(StatusSetting $status)
    {
        $cronUrl = base_path('artisan');
        $status = $status->whereId('1')->first();
        $command = ":- <pre>***** php $cronUrl schedule:run >> /dev/null 2>&1</pre>";
        $shared = ":- <pre>/usr/bin/php-cli -q  $cronUrl schedule:run >> /dev/null 2>&1</pre>";
        $warn = '';
        $condition = new \App\Model\MailJob\Condition();

        // $job = $condition->checkActiveJob();
        $commands = [
            'everyMinute'        => 'Every Minute',
            'everyFiveMinutes'   => 'Every Five Minute',
            'everyTenMinutes'    => 'Every Ten Minute',
            'everyThirtyMinutes' => 'Every Thirty Minute',
            'hourly'             => 'Every Hour',
            'daily'              => 'Every Day',
            'dailyAt'            => 'Daily at',
            'weekly'             => 'Every Week',

            'monthly' => 'Monthly',
            'yearly'  => 'Yearly',
        ];

        $expiryDays = [
            '120'=> '120 Days',
            '90' => '90 Days',
            '60' => '60 Days',
            '30' => '30 Days',
            '15' => '15 Days',
            '5'  => '5 Days',
            '3'  => '3 Days',
            '1'  => '1 Day',
            '0'  => 'On the Expiry Day',
        ];

        $selectedDays = [];
        $daysLists = ExpiryMailDay::get();
        if (count($daysLists) > 0) {
            foreach ($daysLists as $daysList) {
                $selectedDays[] = $daysList;
            }
        }
        $delLogDays = [''=>'Disabled', '720'=>'720 Days', '365'=>'365 days', '180'=>'180 Days', '150'=>'150 Days', '60'=>'60 Days', '30'=>'30 Days', '15'=>'15 Days', '5'=>'5 Days', '2'=>'2 Days'];
        $beforeLogDay[] = ActivityLogDay::first()->days;
        // dd(count($beforeLogDay));

        return view('themes.default1.common.cron.cron', compact('templates', 'warn', 'commands', 'condition', 'shared', 'status', 'expiryDays', 'selectedDays', 'delLogDays', 'beforeLogDay'));
    }

    public function postSchedular(StatusSetting $status, Request $request)
    {
        $allStatus = $status->whereId('1')->first();
        if ($request->expiry_cron) {
            $allStatus->expiry_mail = $request->expiry_cron;
        } else {
            $allStatus->expiry_mail = 0;
        }
        if ($request->activity) {
            $allStatus->activity_log_delete = $request->activity;
        } else {
            $allStatus->activity_log_delete = 0;
        }
        $allStatus->save();
        $this->saveConditions();
        /* redirect to Index page with Success Message */
        return redirect('job-scheduler')->with('success', \Lang::get('message.updated-successfully'));
    }

    public function saveConditions()
    {
        if (\Input::get('expiry-commands') && \Input::get('activity-commands')) {
            $expiry_commands = \Input::get('expiry-commands');
            $expiry_dailyAt = \Input::get('expiry-dailyAt');
            $activity_commands = \Input::get('activity-commands');
            $activity_dailyAt = \Input::get('activity-dailyAt');
            $activity_command = $this->getCommand($activity_commands, $activity_dailyAt);
            $expiry_command = $this->getCommand($expiry_commands, $expiry_dailyAt);
            $jobs = ['expiryMail' => $expiry_command, 'deleteLogs' =>  $activity_command];
            $this->storeCommand($jobs);
        }
    }

    public function getCommand($command, $daily_at)
    {
        if ($command == 'dailyAt') {
            $command = "dailyAt,$daily_at";
        }

        return $command;
    }

    public function storeCommand($array = [])
    {
        $command = new \App\Model\MailJob\Condition();
        $commands = $command->get();
        if ($commands->count() > 0) {
            foreach ($commands as $condition) {
                $condition->delete();
            }
        }
        if (count($array) > 0) {
            foreach ($array as $key => $save) {
                $command->create([
                    'job'   => $key,
                    'value' => $save,
                ]);
            }
        }
    }

    //Save the Cron Days for expiry Mails and Activity Log
    public function saveCronDays(Request $request)
    {
        $daysList = new \App\Model\Mailjob\ExpiryMailDay();
        $lists = $daysList->get();
        if ($lists->count() > 0) {
            foreach ($lists  as $list) {
                $list->delete();
            }
        }
        if ($request['expiryday'] != null) {
            foreach ($request['expiryday'] as $key => $value) {
                $daysList->create([
          'days'=> $value,
           ]);
            }
        }
        \Config::set('activitylog.delete_records_older_than_days', $request->logdelday);
        ActivityLogDay::findorFail(1)->update(['days'=>$request->logdelday]);

        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }
}
