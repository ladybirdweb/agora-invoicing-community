<?php

namespace App\Http\Controllers\Common;

use App\ApiKey;
use App\Model\Common\Mailchimp\MailchimpSetting;
use App\Model\Common\Setting;
use App\Model\Common\StatusSetting;
use App\Model\Common\Template;
use App\Model\Payment\Currency;
use App\Model\Plugin;
use App\User;
use Bugsnag;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SettingsController extends BaseSettingsController
{
    public $apikey;
    public $statusSetting;

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'checkPaymentGateway']);
        $this->middleware('admin', ['except' => 'checkPaymentGateway']);

        $apikey = new ApiKey();
        $this->apikey = $apikey;

        $status = new StatusSetting();
        $this->statusSetting = $status;
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

    /**
     * Get the Status and Api Keys for Settings Module.
     *
     * @param ApiKey $apikeys
     */
    public function getKeys(ApiKey $apikeys)
    {
        try {
            $licenseSecret = $apikeys->pluck('license_api_secret')->first();
            $licenseUrl = $apikeys->pluck('license_api_url')->first();
            $status = StatusSetting::pluck('license_status')->first();
            $captchaStatus = StatusSetting::pluck('recaptcha_status')->first();
            $updateStatus = StatusSetting::pluck('update_settings')->first();
            $mobileStatus = StatusSetting::pluck('msg91_status')->first();
            $siteKey = $apikeys->pluck('nocaptcha_sitekey')->first();
            $secretKey = $apikeys->pluck('captcha_secretCheck')->first();
            $updateSecret = $apikeys->pluck('update_api_secret')->first();
            $mobileauthkey = $apikeys->pluck('msg91_auth_key')->first();
            $msg91Sender = $apikeys->pluck('msg91_sender')->first();
            $updateUrl = $apikeys->pluck('update_api_url')->first();
            $emailStatus = StatusSetting::pluck('emailverification_status')->first();
            $twitterKeys = $apikeys->select('twitter_consumer_key','twitter_consumer_secret',
                'twitter_access_token', 'access_tooken_secret')->first();
            $twitterStatus = $this->statusSetting->pluck('twitter_status')->first();
            $zohoStatus = $this->statusSetting->pluck('zoho_status')->first();
            $zohoKey = $apikeys->pluck('zoho_api_key')->first();
            $rzpStatus = $this->statusSetting->pluck('rzp_status')->first();
            $rzpKeys = $apikeys->select('rzp_key', 'rzp_secret', 'apilayer_key')->first();
            $mailchimpSetting = StatusSetting::pluck('mailchimp_status')->first();
            $mailchimpKey = MailchimpSetting::pluck('api_key')->first();
            $termsStatus = StatusSetting::pluck('terms')->first();
            $termsUrl = $apikeys->pluck('terms_url')->first();
            $pipedriveKey = $apikeys->pluck('pipedrive_api_key')->first();
            $pipedriveStatus = StatusSetting::pluck('pipedrive_status')->first();
            $domainCheckStatus = StatusSetting::pluck('domain_check')->first();
            $model = $apikeys->find(1);

            return view('themes.default1.common.apikey', compact('model', 'status', 'licenseSecret', 'licenseUrl', 'siteKey', 'secretKey', 'captchaStatus', 'updateStatus', 'updateSecret', 'updateUrl', 'mobileStatus', 'mobileauthkey', 'msg91Sender', 'emailStatus', 'twitterStatus', 'twitterKeys', 'zohoStatus', 'zohoKey', 'rzpStatus', 'rzpKeys', 'mailchimpSetting', 'mailchimpKey', 'termsStatus', 'termsUrl', 'pipedriveKey', 'pipedriveStatus', 'domainCheckStatus'));
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

    /**
     * PAyment Gateway that is shown on the basis of currency.
     *
     * @param string $currency The currency of the Product Selected
     *
     * @return string Name of the Payment Gateway
     */
    public static function checkPaymentGateway($currency)
    {
        try {
            $plugins = new Plugin();
            $models = [];
            $gateways = '';
            $name = '';
            $active_plugins = $plugins->where('status', 1)->get(); //get the plugins that are active
            if ($active_plugins->count() > 0) {
                foreach ($active_plugins as $plugin) {
                    $models[] = \DB::table(strtolower($plugin->name))->first(); //get the table of the active plugin
                    $pluginName[] = $plugin->name; //get the name of active plugin
                }

                if (count($models) > 0) {//If more than 1 plugin is active it will check the currencies allowed for that plugin.If the currencies allowed matches the passed arguement(currency),that plugin name is returned
                    for ($i = 0; $i < count($pluginName); $i++) {
                        $currencies = explode(',', $models[$i]->currencies);
                        if (in_array($currency, $currencies)) {
                            $name = $pluginName[$i];
                        }
                    }
                }
            }

            return $name;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function settingsSystem(Setting $settings)
    {
        try {
            $set = $settings->find(1);
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($set->state);
            $selectedCountry = \DB::table('countries')->where('country_code_char2', $set->country)
            ->pluck('nicename', 'country_code_char2')->toArray();
            $selectedCurrency = \DB::table('currencies')->where('code', $set->default_currency)
            ->pluck('name', 'symbol')->toArray();
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($set->country);

            return view(
                'themes.default1.common.setting.system',
                compact('set', 'selectedCountry', 'state', 'states', 'selectedCurrency')
            );
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettingsSystem(Setting $settings, Request $request)
    {
        $this->validate($request, [
                'company'         => 'required',
                'company_email'   => 'required',
                'website'         => 'required',
                'phone'           => 'required',
                'address'         => 'required',
                'country'         => 'required',
                'default_currency'=> 'required',
                'admin-logo'      => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
                'fav-icon'        => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
                'logo'            => 'sometimes | mimes:jpeg,jpg,png,gif | max:1000',
            ]);

        try {
            $setting = $settings->find(1);
            if ($request->hasFile('logo')) {
                $name = $request->file('logo')->getClientOriginalName();
                $destinationPath = public_path('common/images');
                $request->file('logo')->move($destinationPath, $name);
                $setting->logo = $name;
            }
            if ($request->hasFile('admin-logo')) {
                $logoName = $request->file('admin-logo')->getClientOriginalName();
                $destinationPath = public_path('admin/images');
                $request->file('admin-logo')->move($destinationPath, $logoName);
                $setting->admin_logo = $logoName;
            }
            if ($request->hasFile('fav-icon')) {
                $iconName = $request->file('fav-icon')->getClientOriginalName();
                $destinationPath = public_path('common/images');
                $request->file('fav-icon')->move($destinationPath, $iconName);
                $setting->fav_icon = $iconName;
            }
            $setting->default_symbol = Currency::where('code', $request->input('default_currency'))
            ->pluck('symbol')->first();
            $setting->fill($request->except('password', 'logo', 'admin-logo', 'fav-icon'))->save();

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
                'email'     => 'required',
                'password'  => 'required',
                'driver'    => 'required',
                'port'      => 'required',
                'encryption'=> 'required',
                'host'      => 'required',
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

    public function settingsActivity(Request $request, Activity $activities)
    {
        try {
            $activity = $activities->all();
            $from = $request->input('from');
            $till = $request->input('till');
            $delFrom = $request->input('delFrom');
            $delTill = $request->input('delTill');

            return view('themes.default1.common.Activity-Log', compact('activity', 'from', 'till', 'delFrom', 'delTill'));
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

    public function getActivity(Request $request)
    {
        try {
            $from = $request->input('log_from');
            $till = $request->input('log_till');
            $delFrom = $request->input('delFrom');
            $delTill = $request->input('delTill');
            $query = $this->advanceSearch($from, $till, $delFrom, $delTill);

            return \DataTables::of($query->take(50))
             ->setTotalRecords($query->count())
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

                                    ->filterColumn('log_name', function ($query, $keyword) {
                                        $sql = 'log_name like ?';
                                        $query->whereRaw($sql, ["%{$keyword}%"]);
                                    })

                                ->filterColumn('description', function ($query, $keyword) {
                                    $sql = 'description like ?';
                                    $query->whereRaw($sql, ["%{$keyword}%"]);
                                })

                            ->filterColumn('causer_id', function ($query, $keyword) {
                                $sql = 'first_name like ?';
                                $query->whereRaw($sql, ["%{$keyword}%"]);
                            })

                            ->rawColumns(['checkbox', 'name', 'description',
                                'username', 'role', 'new', 'old', 'created_at', ])
                            ->make(true);
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function getMails()
    {
        try {
            $email_log = \DB::table('email_log')->orderBy('date', 'desc')->get();

            return\ DataTables::of($email_log)
             ->addColumn('checkbox', function ($model) {
                 return "<input type='checkbox' class='email' value=".$model->id.' name=select[] id=check>';
             })
                           ->addColumn('date', function ($model) {
                               $date = $model->date;
                               if ($date) {
                                   $date1 = new \DateTime($date);
                                   $tz = \Auth::user()->timezone()->first()->name;
                                   $date1->setTimezone(new \DateTimeZone($tz));
                                   $finalDate = $date1->format('M j, Y, g:i a ');
                               }

                               return $finalDate;
                           })
                             ->addColumn('from', function ($model) {
                                 $from = Markdown::convertToHtml($model->from);

                                 return $from;
                             })
                              ->addColumn('to', function ($model) {
                                  $to = Markdown::convertToHtml($model->to);

                                  return $to;
                              })

                               ->addColumn('subject', function ($model) {
                                   return ucfirst($model->subject);
                               })
                                // ->addColumn('headers', function ($model) {
                                //     $headers = Markdown::convertToHtml(ucfirst($model->headers));

                                //     return $headers;
                                // })
                              ->addColumn('status', function ($model) {
                                  return ucfirst($model->status);
                              })

                            ->rawColumns(['checkbox', 'date', 'from', 'to',
                                'bcc', 'subject',  'status', ])
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
