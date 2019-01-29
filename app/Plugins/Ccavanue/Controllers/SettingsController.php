<?php

namespace App\Plugins\Ccavanue\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Ccavanue\Model\Ccavanue;
use Illuminate\Http\Request;
use Schema;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin');
    }

    public function Settings()
    {
        try {
            if (!Schema::hasTable('ccavanue')) {
                Schema::create('ccavanue', function ($table) {
                    $table->increments('id');
                    $table->string('merchant_id');
                    $table->string('access_code');
                    $table->string('working_key');
                    $table->string('redirect_url');
                    $table->string('cancel_url');
                    $table->string('ccavanue_url');
                    $table->string('currencies');
                    $table->timestamps();
                });
            }

            $ccavanue1 = new Ccavanue();
            //dd($ccavanue);
            $ccavanue = $ccavanue1->where('id', '1')->first();

            if (!$ccavanue) {
                $ccavanue1->create(['id' => '1']);
            }
            $path = app_path().'/Plugins/Ccavanue/views';
            \View::addNamespace('plugins', $path);

            return view('plugins::settings', compact('ccavanue'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettings(Request $request)
    {
        try {
            $this->validate($request, [
                'merchant_id'  => 'required',
                'access_code'  => 'required',
                'working_key'  => 'required',
                'redirect_url' => 'required|url',
                'cancel_url'   => 'required|url',
                'ccavanue_url' => 'required|url',
                'currencies'   => 'required',
            ]);
            $ccavanue1 = new Ccavanue();
            $ccavanue = $ccavanue1->where('id', '1')->first();
            $ccavanue->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
