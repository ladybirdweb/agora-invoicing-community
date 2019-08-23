<?php

namespace App\Http\Controllers\Common;

use App\ApiKey;
use App\Http\Controllers\Order\ExtendedOrderController;
use App\Model\Common\StatusSetting;
use App\Model\Mailjob\ActivityLogDay;
use App\Model\Mailjob\ExpiryMailDay;
use App\Traits\ApiKeySettings;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class BaseSettingsController extends PaymentSettingsController
{
    use ApiKeySettings;

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
        ->select(
            'id',
            'log_name',
            'description',
            'subject_id',
            'subject_type',
            'causer_id',
            'properties',
            'created_at'
        );

        return $join;
    }

    public function getScheduler(StatusSetting $status)
    {
        $cronPath = base_path('artisan');
        $status = $status->whereId('1')->first();
        $execEnabled = $this->execEnabled();
        $paths = $this->getPHPBinPath();
        // $command = ":- <pre>***** php $cronUrl schedule:run >> /dev/null 2>&1</pre>";
        // $shared = ":- <pre>/usr/bin/php-cli -q  $cronUrl schedule:run >> /dev/null 2>&1</pre>";
        $warn = '';
        $condition = new \App\Model\Mailjob\Condition();

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
        $delLogDays = ['720' => '720 Days', '365'=>'365 days', '180'=>'180 Days',
        '150'                => '150 Days', '60'=>'60 Days', '30'=>'30 Days', '15'=>'15 Days', '5'=>'5 Days', '2'=>'2 Days', '0'=>'Delete All Logs', ];
        $beforeLogDay[] = ActivityLogDay::first()->days;

        return view('themes.default1.common.cron.cron', compact(
            'cronPath',
            'warn',
            'commands',
            'condition',
            'status',
            'expiryDays',
            'selectedDays',
            'delLogDays',
            'beforeLogDay',
            'execEnabled',
            'paths'
        ));
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

    /**
     * Check if exec() function is available.
     *
     * @return bool
     */
    private function execEnabled()
    {
        try {
            // make a small test
            return function_exists('exec') && !in_array('exec', array_map('trim', explode(', ', ini_get('disable_functions'))));
        } catch (\Exception $ex) {
            return false;
        }
    }

    private function getPHPBinPath()
    {
        $paths = [
            '/usr/bin/php',
            '/usr/local/bin/php',
            '/bin/php',
            '/usr/bin/php7',
            '/usr/bin/php7.1',
            '/usr/bin/php71',
            '/opt/plesk/php/7.1/bin/php',
        ];
        // try to detect system's PHP CLI
        if ($this->execEnabled()) {
            try {
                $paths = array_unique(array_merge($paths, explode(' ', exec('whereis php'))));
            } catch (\Exception $e) {
                // @todo: system logging here
                echo $e->getMessage();
            }
        }

        // validate detected / default PHP CLI
        // Because array_filter() preserves keys, you should consider the resulting array to be an associative array even if the original array had integer keys for there may be holes in your sequence of keys. This means that, for example, json_encode() will convert your result array into an object instead of an array. Call array_values() on the result array to guarantee json_encode() gives you an array.
        $paths = array_values(array_filter($paths, function ($path) {
            return is_executable($path) && preg_match("/php[0-9\.a-z]{0,3}$/i", $path);
        }));

        return $paths;
    }

    protected function checkPHPExecutablePath(Request $request)
    {
        $path = $request->get('path');
        $version = '5.6';
        if (!file_exists($path) || !is_executable($path)) {
            return errorResponse(\Lang::get('message.invalid-php-path'));
        }

        if ($this->execEnabled()) {
            $exec_script = $path.' '.public_path('cron-test.php');
            $version = exec($exec_script, $output);

            return (version_compare($version, '7.1.3', '>=') == 1) ? successResponse(\Lang::get('message.valid-php-path')) : errorResponse(\Lang::get('message.invalid-php-version-or-path'));
        } else {
            return successResponse(\Lang::get('message.please_enable_php_exec_for_cronjob_check'));
        }
    }

    //Save the Cron Days for expiry Mails and Activity Log
    public function saveCronDays(Request $request)
    {
        $daysList = new \App\Model\Mailjob\ExpiryMailDay();
        $lists = $daysList->get();
        if ($lists->count() > 0) {
            foreach ($lists as $list) {
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
        ActivityLogDay::findorFail(1)->update(['days'=>$request->logdelday]);

        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }

    //Save Google recaptch site key and secret in Database
    public function captchaDetails(Request $request)
    {
        $status = $request->input('status');
        if ($status == 1) {
            $nocaptcha_sitekey = $request->input('nocaptcha_sitekey');
            $captcha_secretCheck = $request->input('nocaptcha_secret');
            $path_to_file = base_path('.env');
            $file_contents = file_get_contents($path_to_file);
            $file_contents_sitekey = str_replace(env('NOCAPTCHA_SITEKEY'), $nocaptcha_sitekey, $file_contents);
            file_put_contents($path_to_file, $file_contents_sitekey);
            $file_contents_nocaptcha_secret = str_replace(env('NOCAPTCHA_SECRET'), $captcha_secretCheck, $file_contents);
            file_put_contents($path_to_file, $file_contents_nocaptcha_secret);
        } else {
            $nocaptcha_sitekey = '00';
            $captcha_secretCheck = '00';
            $path_to_file = base_path('.env');
            $file_contents = file_get_contents($path_to_file);
            $file_contents_sitekey = str_replace(env('NOCAPTCHA_SITEKEY'), $nocaptcha_sitekey, $file_contents);
            file_put_contents($path_to_file, $file_contents_sitekey);
            $file_contents_secretchek = str_replace(env('NOCAPTCHA_SECRET'), $captcha_secretCheck, $file_contents);
            file_put_contents($path_to_file, $file_contents_secretchek);
        }

        StatusSetting::where('id', 1)->update(['recaptcha_status'=>$status]);
        ApiKey::where('id', 1)->update(['nocaptcha_sitekey'=> $nocaptcha_sitekey,
         'captcha_secretCheck'                             => $captcha_secretCheck, ]);

        return ['message' => 'success', 'update'=>'Recaptcha Settings Updated'];
    }
}
