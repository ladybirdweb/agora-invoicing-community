<?php

namespace App\Http\Controllers\Common;

use App\ApiKey;
use App\Model\Common\Setting;
use App\Model\Common\Template;
use App\Model\Plugin;
use App\User;
use Bugsnag;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Spatie\Activitylog\Models\Activity;

class SettingsController extends BaseSettingsController
{
    public $apikey;

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'checkPaymentGateway']);
        $this->middleware('admin', ['except' => 'checkPaymentGateway']);

        $apikey = new ApiKey();
        $this->apikey = $apikey;
    }

    public function settings(Setting $settings)
    {
        if (!$settings->where('id', '1')->first()) {
            $settings->create(['company' => '']);
        }

        return view('themes.default1.common.admin-settings');
        //return view('themes.default1.common.settings', compact('setting', 'template'));
    }

    public function plugins()
    {
        return view('themes.default1.common.plugins');
    }

    public function getKeys(ApiKey $apikeys)
    {
        try {
            $model = $apikeys->find(1);

            return view('themes.default1.common.apikey', compact('model'));
        } catch (\Exception $ex) {
            return redirect('/')->with('fails', $ex->getMessage());
        }
    }

    public function postKeys(ApiKey $apikeys, Request $request)
    {
        try {
            $keys = $apikeys->find(1);
            $keys->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public static function checkPaymentGateway($currency)
    {
        try {
            $plugins = new Plugin();
            $models = [];
            $gateways = 'Razorpay';

            return $gateways;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function settingsSystem(Setting $settings)
    {
        try {
            $set = $settings->find(1);

            return view('themes.default1.common.setting.system', compact('set'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettingsSystem(Setting $settings, Request $request)
    {
        try {
            $setting = $settings->find(1);
            if ($request->hasFile('logo')) {
                $name = $request->file('logo')->getClientOriginalName();
                $destinationPath = public_path('cart/img/logo');
                $request->file('logo')->move($destinationPath, $name);
                $setting->logo = $name;
            }
            $setting->fill($request->except('password', 'logo'))->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function settingsEmail(Setting $settings)
    {
        try {
            $set = $settings->find(1);

            return view('themes.default1.common.setting.email', compact('set'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettingsEmail(Setting $settings, Request $request)
    {
        $this->validate($request, [
                'email'    => 'required',
                'password' => 'required',
            ]);

        try {
            $setting = $settings->find(1);
            $setting->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function settingsTemplate(Setting $settings)
    {
        try {
            $set = $settings->find(1);
            $template = new Template();
            //$templates = $template->lists('name', 'id')->toArray();
            return view('themes.default1.common.setting.template', compact('set', 'template'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettingsTemplate(Setting $settings, Request $request)
    {
        try {
            $setting = $settings->find(1);
            $setting->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function settingsError(Setting $settings)
    {
        try {
            $set = $settings->find(1);

            return view('themes.default1.common.setting.error-log', compact('set'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function settingsActivity(Activity $activities)
    {
        try {
            $activity = $activities->all();

            return view('themes.default1.common.Activity-Log', compact('activity'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function settingsMail()
    {
        try {
            return view('themes.default1.common.email-log');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getActivity()
    {
        try {
            $activity_log = Activity::select('id', 'log_name', 'description', 
                'subject_id', 'subject_type', 'causer_id', 'properties', 'created_at')->get();

            return\ DataTables::of($activity_log)
             ->addColumn('checkbox', function ($model) {
                 return "<input type='checkbox' class='activity' value=".$model->id.' name=select[] id=check>';
             })
                           ->addColumn('name', function ($model) {
                               return ucfirst($model->log_name);
                           })
                             ->addColumn('description', function ($model) {
                                 return ucfirst($model->description);
                             })
                          ->addColumn('username', function ($model) {
                              $causer_id = $model->causer_id;
                              $names = User::where('id', $causer_id)->pluck('last_name', 'first_name');
                              foreach ($names as $key => $value) {
                                  $fullName = $key.' '.$value;

                                  return $fullName;
                              }
                          })
                              ->addColumn('role', function ($model) {
                                  $causer_id = $model->causer_id;
                                  $role = User::where('id', $causer_id)->pluck('role');

                                  return json_decode($role);
                              })
                               ->addColumn('new', function ($model) {
                                   $properties = ($model->properties);
                                   $newEntry = $this->getNewEntry($properties, $model);

                                   return $newEntry;
                               })
                                ->addColumn('old', function ($model) {
                                    $data = ($model->properties);
                                    $oldEntry = $this->getOldEntry($data, $model);

                                    return $oldEntry;
                                })
                                ->addColumn('created_at', function ($model) {
                                    $newDate = $this->getDate($model->created_at);

                                    return $newDate;
                                })

                            ->rawColumns(['checkbox', 'name', 'description',   
                                'username', 'role', 'new', 'old', 'created_at'])
                            ->make(true);
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function getMails()
    {
        try {
            $email_log = \DB::table('email_log')->get();

            return\ DataTables::of($email_log)
             ->addColumn('checkbox', function ($model) {
                 return "<input type='checkbox' class='activity' value=".$model->id.' name=select[] id=check>';
             })
                           ->addColumn('date', function ($model) {
                               return ucfirst($model->date);
                           })
                             ->addColumn('from', function ($model) {
                                 $from = Markdown::convertToHtml(ucfirst($model->from));

                                 return $from;
                             })
                              ->addColumn('to', function ($model) {
                                  $to = Markdown::convertToHtml(ucfirst($model->to));

                                  return $to;
                              })
                             ->addColumn('cc', function ($model) {
                                 $cc = '--';

                                 return $cc;
                             })

                               ->addColumn('subject', function ($model) {
                                   return ucfirst($model->subject);
                               })

                              ->addColumn('headers', function ($model) {
                                  $headers = Markdown::convertToHtml(ucfirst($model->headers));

                                  return $headers;
                              })
                              ->addColumn('status', function ($model) {
                                  return ucfirst($model->status);
                              })

                            ->rawColumns(['checkbox', 'date', 'from', 'to', 'cc', 
                                'bcc', 'subject', 'headers', 'status'])
                            ->make(true);
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $activity = Activity::where('id', $id)->first();
                    if ($activity) {
                        $activity->delete();
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

    public function postSettingsError(Setting $settings, Request $request)
    {
        try {
            $setting = $settings->find(1);
            $setting->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
