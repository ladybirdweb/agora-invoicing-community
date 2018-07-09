<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Model\Common\Setting;
use App\Model\Common\Template;
use App\ApiKey;
use App\Model\Plugin;
use App\User;
use Illuminate\Support\Facades\Input;
use Bugsnag;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;


class SettingsController extends Controller
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
        } catch (Exception $ex) {
            return redirect('/')->with('fails', $ex->getMessage());
        }
    }

    public function postKeys(ApiKey $apikeys, Request $request)
    {
       try{
        $keys = $apikeys->find(1);
        $keys->fill($request->input())->save();
        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));

       } catch(Exception $ex){
         return redirect()->back()->with('fails',$ex->getMessage());
           
       }
    }

    public function getPlugin()
    {
        $plugins = $this->fetchConfig();

        //dd($result);
        return \DataTables::of(new Collection($plugins))
                        // ->searchColumns('name')
                        ->addColumn('name', function ($model) {
                            if (array_has($model, 'path')) {
                                if ($model['status'] == 0) {
                                    $activate = '<a href='.url('plugin/status/'.$model['path']).'>Activate</a>';
                                    $settings = ' ';
                                } else {
                                    $settings = '<a href='./* @scrutinizer ignore-type */url($model['settings']).'>Settings</a> | ';
                                    $activate = '<a href='./* @scrutinizer ignore-type */url('plugin/status/'.$model['path']).'>Deactivate</a>';
                                }

                                $delete = '<a href=  id=delete'.$model['path'].' data-toggle=modal data-target=#del'.$model['path']."><span style='color:red'>Delete</span></a>"
                                        ."<div class='modal fade' id=del".$model['path'].">
                                            <div class='modal-dialog'>
                                                <div class=modal-content>  
                                                    <div class=modal-header>
                                                        <h4 class=modal-title>Delete</h4>
                                                    </div>
                                                    <div class=modal-body>
                                                       <p>Are you Sure ?</p>
                                                        <div class=modal-footer>
                                                            <button type=button class='btn btn-default pull-left' data-dismiss=modal id=dismis>"./* @scrutinizer ignore-type */\Lang::get('lang.close').'</button>
                                                            <a href='.url('plugin/delete/'.$model['path'])."><button class='btn btn-danger'>Delete</button></a>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
                                $action = '<br><br>'.$delete.' | '.$settings.$activate;
                            } else {
                                $action = '';
                            }

                            return ucfirst($model['name']).$action;
                        })
                        ->addColumn('description', function ($model) {
                            return $model['description'];
                        })
                        ->addColumn('author', function ($model) {
                            return ucfirst($model['author']);
                        })
                        ->addColumn('website', function ($model) {
                            return '<a href='.$model['website'].' target=_blank>'.$model['website'].'</a>';
                        })
                        ->addColumn('version', function ($model) {
                            return $model['version'];
                        })
                      ->rawColumns(['name', 'description', 'author', 'website', 'version'])
                            ->make(true);
    }

    /**
     * Reading the Filedirectory.
     *
     * @return type
     */
    public function readPlugins()
    {
        $dir = app_path().DIRECTORY_SEPARATOR.'Plugins';
        $plugins = array_diff(scandir($dir), ['.', '..']);

        return $plugins;
    }

    /**
     * After plugin post.
     *
     * @param Request $request
     *
     * @return type
     */
    public function postPlugins(Request $request)
    {
        $v = $this->validate($request, ['plugin' => 'required|mimes:application/zip,zip,Zip']);
        $plug = new Plugin();
        $file = $request->file('plugin');
        //dd($file);
        $destination = app_path().DIRECTORY_SEPARATOR.'Plugins';
        $zipfile = $file->getRealPath();
        /*
         * get the file name and remove .zip
         */
        $filename2 = $file->getClientOriginalName();
        $filename2 = str_replace('.zip', '', $filename2);
        $filename1 = ucfirst($file->getClientOriginalName());
        $filename = str_replace('.zip', '', $filename1);
        mkdir($destination.DIRECTORY_SEPARATOR.$filename);
        /*
         * extract the zip file using zipper
         */
        \Zipper::make($zipfile)->folder($filename2)->extractTo($destination.DIRECTORY_SEPARATOR.$filename);

        $file = app_path().DIRECTORY_SEPARATOR.'Plugins'.DIRECTORY_SEPARATOR.$filename; // Plugin file path

        if (file_exists($file)) {
            $seviceporvider = $file.DIRECTORY_SEPARATOR.'ServiceProvider.php';
            $config = $file.DIRECTORY_SEPARATOR.'config.php';
            if (file_exists($seviceporvider) && file_exists($config)) {
                /*
                 * move to faveo config
                 */
                $faveoconfig = config_path().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$filename.'.php';
                if ($faveoconfig) {
                    //copy($config, $faveoconfig);
                    /*
                     * write provider list in app.php line 128
                     */
                    $app = base_path().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'app.php';
                    $str = "\n\n\t\t\t'App\\Plugins\\$filename"."\\ServiceProvider',";
                    $line_i_am_looking_for = 102;
                    $lines = file($app, FILE_IGNORE_NEW_LINES);
                    $lines[$line_i_am_looking_for] = $str;
                    file_put_contents($app, implode("\n", $lines));
                    $plug->create(['name' => $filename, 'path' => $filename, 'status' => 1]);

                    return redirect()->back()->with('success', 'Installed SuccessFully');
                } else {
                    /*
                     * delete if the plugin hasn't config.php and ServiceProvider.php
                     */
                    $this->deleteDirectory($file);

                    return redirect()->back()->with('fails', 'Their is no '.$file);
                }
            } else {
                /*
                 * delete if the plugin hasn't config.php and ServiceProvider.php
                 */
                $this->deleteDirectory($file);

                return redirect()->back()->with('fails', 'Their is no <b>config.php or ServiceProvider.php</b>  '.$file);
            }
        } else {
            /*
             * delete if the plugin Name is not equal to the folder name
             */
            $this->deleteDirectory($file);

            return redirect()->back()->with('fails', '<b>Plugin File Path is not exist</b>  '.$file);
        }
    }

    /**
     * Delete the directory.
     *
     * @param type $dir
     *
     * @return bool
     */
    public function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            chmod($dir.DIRECTORY_SEPARATOR.$item, 0777);
            if (!$this->deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) {
                return false;
            }
        }
        chmod($dir, 0777);

        return rmdir($dir);
    }

    public function readConfigs()
    {
        $dir = app_path().DIRECTORY_SEPARATOR.'Plugins'.DIRECTORY_SEPARATOR;
        $directories = scandir($dir);
        $files = [];
        foreach ($directories as $key => $file) {
            if ($file === '.' or $file === '..') {
                continue;
            }

            if (is_dir($dir.DIRECTORY_SEPARATOR.$file)) {
                $files[$key] = $file;
            }
        }
        //dd($files);
        $config = [];
        $plugins = [];
        if (count($files) > 0) {
            foreach ($files as $key => $file) {
                $plugin = $dir.$file;
                $plugins[$key] = array_diff(scandir($plugin), ['.', '..', 'ServiceProvider.php']);
                $plugins[$key]['file'] = $plugin;
            }
            foreach ($plugins as $plugin) {
                $dir = $plugin['file'];
                //opendir($dir);
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file == 'config.php') {
                            $config[] = $dir.DIRECTORY_SEPARATOR.$file;
                        }
                    }
                    closedir($dh);
                }
            }

            return $config;
        } else {
            return 'null';
        }
    }

    public function fetchConfig()
    {
        $configs = $this->readConfigs();
        //dd($configs);
        $plugs = new Plugin();
        $fields = [];
        $attributes = [];
        if ($configs != 'null') {
            foreach ($configs as $key => $config) {
                $fields[$key] = include $config;
            }
        }
        //dd($fields);
        if (count($fields) > 0) {
            foreach ($fields as $key => $field) {
                $plug = $plugs->where('name', $field['name'])->select('path', 'status')->orderBy('name')->get()->toArray();
                if ($plug) {
                    foreach ($plug as $i => $value) {
                        $attributes[$key]['path'] = $plug[$i]['path'];
                        $attributes[$key]['status'] = $plug[$i]['status'];
                    }
                } else {
                    $attributes[$key]['path'] = $field['name'];
                    $attributes[$key]['status'] = 0;
                }
                $attributes[$key]['name'] = $field['name'];
                $attributes[$key]['settings'] = $field['settings'];
                $attributes[$key]['description'] = $field['description'];
                $attributes[$key]['website'] = $field['website'];
                $attributes[$key]['version'] = $field['version'];
                $attributes[$key]['author'] = $field['author'];
            }
        }
        //dd($attributes);
        return $attributes;
    }

    public function deletePlugin($slug)
    {
        $dir = app_path().DIRECTORY_SEPARATOR.'Plugins'.DIRECTORY_SEPARATOR.$slug;
        $this->deleteDirectory($dir);
        /*
         * remove service provider from app.php
         */
        $str = "'App\\Plugins\\$slug"."\\ServiceProvider',";
        $path_to_file = base_path().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'app.php';
        $file_contents = file_get_contents($path_to_file);
        $file_contents = str_replace($str, '//', $file_contents);
        file_put_contents($path_to_file, $file_contents);
        $plugin = new Plugin();
        $plugin = $plugin->where('path', $slug)->first();
        if ($plugin) {
            $plugin->delete();
        }

        return redirect()->back()->with('success', 'Deleted Successfully');
    }

    public function statusPlugin($slug)
    {
        $plugs = new Plugin();
        $plug = $plugs->where('name', $slug)->first();
        if (!$plug) {
            $app = base_path().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'app.php';
            $str = "\n'App\\Plugins\\$slug"."\\ServiceProvider',";
            $line_i_am_looking_for = 102;
            $lines = file($app, FILE_IGNORE_NEW_LINES);
            $lines[$line_i_am_looking_for] = $str;
            file_put_contents($app, implode("\n", $lines));
            $plugs->create(['name' => $slug, 'path' => $slug, 'status' => 1]);

            return redirect()->back()->with('success', 'Status has changed');
        }
        $status = $plug->status;
        if ($status == 0) {
            $plug->status = 1;

            $app = base_path().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'app.php';
            $str = "\n'App\\Plugins\\$slug"."\\ServiceProvider',";
            $line_i_am_looking_for = 102;
            $lines = file($app, FILE_IGNORE_NEW_LINES);
            $lines[$line_i_am_looking_for] = $str;
            file_put_contents($app, implode("\n", $lines));
        }
        if ($status == 1) {
            $plug->status = 0;
            /*
             * remove service provider from app.php
             */
            $str = "\n'App\\Plugins\\$slug"."\\ServiceProvider',";
            $path_to_file = base_path().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'app.php';

            $file_contents = file_get_contents($path_to_file);
            $file_contents = str_replace($str, '//', $file_contents);
            file_put_contents($path_to_file, $file_contents);
        }
        $plug->save();

        return redirect()->back()->with('success', 'Status has changed');
    }

    public static function checkPaymentGateway($currency)
    {
        try {
            $plugins = new Plugin();
            $models = [];
            $gateways = 'Razorpay';
            // $active_plugins = $plugins->where('status', 1)->get();
            // if ($active_plugins->count() > 0) {
            //     foreach ($active_plugins as $plugin) {
            //         array_push($models, \DB::table(strtolower($plugin->name)));
            //     }
            //     if (count($models) > 0) {
            //         foreach ($models as $model) {
            //             if ($model->first()) {
            //                 $currencies = explode(',', $model->first()->currencies);
            //                 if (in_array($currency, $currencies)) {
            //                     array_push($gateways, $model);
            //                 }
            //             }
            //         }
            //     }
            // }

            return $gateways;
        } catch (\Exception $ex) {
            dd($ex);
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

    public function getActivity()
    {
        try {
            $activity_log = Activity::select('id', 'log_name', 'description', 'subject_id', 'subject_type', 'causer_id', 'properties', 'created_at')->get();

            return\ DataTables::of($activity_log)
            // return \Datatable::collection($this->product->select('id', 'name', 'type', 'group')->where('id', '!=', 1)->get())
                     ->addColumn('checkbox', function ($model) {
                         return "<input type='checkbox' class='activity' value=".$model->id.' name=select[] id=check>';
                     })
                           ->addColumn('name', function ($model) {
                               return ucfirst($model->log_name);
                           })
                             ->addColumn('description', function ($model) {
                                 return ucfirst($model->description);
                             })
                            // ->addColumn('subject_id', function ($model) {
                            //     return ucfirst($model->subject_id);
                            // })
                             // ->addColumn('subject_type', function ($model) {
                             //     return ucfirst($model->subject_type);
                             // })
                             ->addColumn('username', function ($model) {
                                 $causer_id = $model->causer_id;
                                 $names = User::where('id', $causer_id)->pluck('last_name', 'first_name');
                                 foreach ($names as $key => $value) {
                                     $fullName = $key.' '.$value;

                                     return $fullName;
                                 }
                                 // return $fullName;
                             })
                              ->addColumn('role', function ($model) {
                                  $causer_id = $model->causer_id;
                                  $role = User::where('id', $causer_id)->pluck('role');

                                  return json_decode($role);
                              })
                              // ->addColumn('causer_type', function ($model) {
                              //     return ucfirst($model->causer_type);
                              // })
                                ->addColumn('new', function ($model) {
                                    $properties = ($model->properties);
                                    $properties = (array_key_exists('attributes', $properties->toArray())) ? ($model->properties['attributes']) : null;
                                    $display = '';
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
                                })
                                ->addColumn('old', function ($model) {
                                    $data = ($model->properties);
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
                                })
                                ->addColumn('created_at', function ($model) {
                                    $created = new DateTime($model->created_at);
                                    $tz = \Auth::user()->timezone()->first()->name;
                                    $created->setTimezone(new DateTimeZone($tz));
                                    $date = $created->format('M j, Y, g:i a ');
                                    $newDate = $date;

                                    return $newDate;
                                })

                            ->rawColumns(['checkbox', 'name', 'description',   'username', 'role', 'new', 'old', 'created_at'])
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

                        <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> './* @scrutinizer ignore-type */     \Lang::get('message.failed').'

                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                    </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                        <i class='fa fa-ban'></i>
                        <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> './* @scrutinizer ignore-type */\Lang::get('message.success').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */ \Lang::get('message.deleted-successfully').'
                    </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>
                        <b>"./* @scrutinizer ignore-type */ \Lang::get('message.alert').'!</b> './* @scrutinizer ignore-type */\Lang::get('message.failed').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */ \Lang::get('message.select-a-row').'
                    </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>
                        <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            '.$e->getMessage().'
                    </div>';
        }
    }

    public function settingsBugsnag(Setting $settings)
    {
        try {
            $set = $settings->find(1);

            return view('themes.default1.common.setting.bugsnag', compact('set'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
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
