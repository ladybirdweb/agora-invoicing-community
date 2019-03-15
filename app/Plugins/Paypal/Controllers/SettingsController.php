<?php

namespace App\Plugins\Paypal\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Paypal\Model\Paypal;
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
            if (!Schema::hasTable('paypal')) {
                Schema::create('paypal', function ($table) {
                    $table->increments('id');
                    $table->string('business');
                    $table->string('cmd');
                    $table->string('paypal_url');
                    $table->string('image_url');
                    $table->string('success_url');
                    $table->string('cancel_url');
                    $table->string('notify_url');
                    $table->string('currencies');
                    $table->timestamps();
                });
            }

            $paypal1 = new Paypal();
            //dd($ccavanue);
            $paypal = $paypal1->where('id', '1')->first();

            if (!$paypal) {
                $paypal1->create(['id' => '1']);
            }
            $path = app_path().'/Plugins/Paypal/views';
            \View::addNamespace('plugins', $path);

            return view('plugins::settings', compact('paypal'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettings(Request $request)
    {
        $this->validate($request, [
            'business'    => 'required',
            'cmd'         => 'required',
            'paypal_url'  => 'required|url',
            'success_url' => 'url',
            'cancel_url'  => 'url',
            'notify_url'  => 'url',
            'currencies'  => 'required',
        ]);

        try {
            $ccavanue1 = new Paypal();
            $ccavanue = $ccavanue1->where('id', '1')->first();
            $ccavanue->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
