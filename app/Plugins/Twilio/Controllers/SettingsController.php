<?php

namespace App\Plugins\Twilio\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plugins\Twilio\Model\Twilio;
use Schema;

class SettingsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        //$this->middleware('admin');
    }

    public function Settings() {
        //dd('sdkcjdsh');
        try {
            if (!Schema::hasTable('twilio')) {
                Schema::create('twilio', function($table) {
                    $table->increments('id');
                    $table->string('account_sid');
                    $table->string('auth_token');
                    $table->string('from_number');
                    $table->timestamps();
                });
            }

            $twilio1 = new Twilio();
            //dd($ccavanue);
            $twilio = $twilio1->where('id', '1')->first();

            if (!$twilio) {
                $twilio1->create(['id' => '1']);
            }
            $path = app_path() . '/Plugins/Twilio/views';
            \View::addNamespace('plugins', $path);
            return view('plugins::settings', compact('twilio'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettings(Request $request) {

        try {
            $this->validate($request, [
                'account_sid' => 'required',
                'auth_token' => 'required',
                'from_number' => 'required',
                
            ]);
            $twilio1 = new Twilio();
            $twilio = $twilio1->where('id', '1')->first();
            $twilio->fill($request->input())->save();
            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
    
    

}
