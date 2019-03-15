<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Model\Common\Country;
use App\Model\Common\Setting;
use App\Model\Payment\Currency;
use Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Lang;

class CurrencyController extends Controller
{
    public $currency;

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin');
        $currency = new Currency();
        $this->currency = $currency;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Response
     */
    public function index()
    {
        return view('themes.default1.payment.currency.index');
    }

    public function getCurrency()
    {
        $defaultCurrency = Setting::pluck('default_currency')->first();
        $model = Currency::where('name', '!=', null)->where('code', '!=', $defaultCurrency)->
        select('id', 'name', 'code', 'symbol', 'status')
        ->orderBy('id', 'desc')->get();

        return \DataTables::of($model)

                        ->addColumn('name', function ($model) {
                            return $model->name;
                        })

                          ->addColumn('code', function ($model) {
                              return $model->code;
                          })

                          ->addColumn('symbol', function ($model) {
                              return $model->symbol;
                          })

                          ->addColumn('dashboard', function ($model) {
                              if ($model->status == 1) {
                                  $showButton = $this->getButtonColor($model->id);

                                  return $showButton;
                              } else {
                                  return  '<a class="btn btn-sm btn-primary btn-xs disabled"><i class="fa fa-eye"
                                style="color:white;"> </i>&nbsp;&nbsp;Show on Dashboard</a>';
                              }
                          })

                        ->addColumn('status', function ($model) {
                            if ($model->status == 1) {
                                return'<label class="switch toggle_event_editing">
                            <input type="hidden" name="module_id" class="module_id" value="'.$model->id.'" >
                         <input type="checkbox" name="modules_settings" 
                         checked value="'.$model->status.'"  class="modules_settings_value">
                          <span class="slider round"></span>
                        </label>';
                            } else {
                                return'<label class="switch toggle_event_editing">
                             <input type="hidden" name="module_id" class="module_id" value="'.$model->id.'" >
                         <input type="checkbox" name="modules_settings" 
                         value="'.$model->status.'" class="modules_settings_value">
                          <span class="slider round"></span>
                        </label>';
                            }
                        })

                        ->rawColumns(['name', 'code', 'symbol', 'dashboard', 'status'])
                        ->make(true);
    }

    /**
     * Get the Color of the button when the currency is allowed to show on dashboard.
     *
     * @param string $id Currrency id
     *
     * @return string
     */
    public function getButtonColor($id)
    {
        $defaultCurrency = Setting::pluck('default_currency')->first();
        $currencyCode = Currency::where('id', $id)->pluck('code')->first(); //If default currency is equal to the currency code then make that button as Disabled as it would always be shown on dashboard and cannot be modified
        if ($defaultCurrency == $currencyCode) {
            return  '<a class="btn btn-sm btn-warning btn-xs disabled" style="background-color:#f39c12;">&nbsp;&nbsp;System Default Currency</a>';
        }
        $currency = Currency::where('id', $id)->pluck('dashboard_currency')->first();
        if ($currency == 1) {
            return '<a href='.url('dashboard-currency/'.$id)." 
                class='btn btn-sm btn-success btn-xs'><i class='fa fa-check'
                style='color:white;'> </i>&nbsp;&nbsp;Show on Dashboard</a>";
        } else {
            return '<a href='.url('dashboard-currency/'.$id)." 
                class='btn btn-sm btn-danger btn-xs'><i class='fa fa-close'
                style='color:white;'> </i>&nbsp;&nbsp;Show on Dashboard</a>";
        }
    }

    /**
     * Activate the Currency to be Shown on Dashboard.
     *
     *
     * @return \Response
     */
    public function setDashboardCurrency($id)
    {
        Currency::where('id', $id)->update(['dashboard_currency' => 1]);
        $dashboardStatus = Currency::where('id', '!=', $id)->select('dashboard_currency', 'id')->get();
        foreach ($dashboardStatus as $status) {
            $status = Currency::where('id', $status->id)->update(['dashboard_currency'=>0]);
        }

        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Response
     */
    public function store(Request $request)
    {

        // dd($request->all());
        // $this->validate($request, [
        //     'code'            => 'required',
        //     'name'            => 'required',
        // ]);

        try {
            $nicename = Country::where('country_id', $request->name)->value('nicename');
            $codeChar2 = Country::where('country_id', $request->name)->value('country_code_char2');
            $currency = new Currency();

            $currency->code = $request->code;
            $currency->symbol = $request->symbol;
            $currency->name = $request->currency_name;
            $currency->base_conversion = '1.0';
            $currency->country_code_char2 = $codeChar2;
            $currency->nicename = $nicename;
            $currency->save();

            // $this->currency->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function update(Request $request)
    {
        try {
            $nicename = Country::where('country_id', $request->editnicename)->value('nicename');
            $codeChar2 = Country::where('country_id', $request->editnicename)->value('country_code_char2');
            $currency = Currency::where('id', $request->currencyId)->first();
            $currency->code = $request->editcode;
            $currency->symbol = $request->editsymbol;
            $currency->name = $request->editcurrency_name;
            $currency->base_conversion = '1.0';
            $currency->country_code_char2 = $codeChar2;
            $currency->nicename = $nicename;
            $currency->save();

            return response()->json(['success' => Lang::get('message.updated-successfully')]);
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function destroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    if ($id != 1) {
                        $currency = $this->currency->where('id', $id)->first();
                        if ($currency) {
                            $currency->delete();
                        } else {
                            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                            //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                        }
                        echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>

                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.success').'

                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.can-not-delete-default').'
                </div>';
                    }
                }
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */
                    \Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }

    public function countryDetails(Request $request)
    {
        $countryDetails = Country::where('country_id', $request->id)->select('currency_code', 'currency_symbol', 'currency_name')->first();
        $data = (['code'=> $countryDetails->currency_code,
          'symbol'      => $countryDetails->currency_symbol, 'currency'=>$countryDetails->currency_name, ]);

        return $data;
    }

    public function updatecurrency(Request $request)
    {
        $code = Currency::where('id', $request->input('current_id'))->value('code');
        Artisan::call('currency:manage', ['action' => 'add', 'currency' => $code]);
        $updatedStatus = ($request->current_status == '1') ? 0 : 1;
        Currency::where('id', $request->current_id)->update(['status'=>$updatedStatus]);

        return Lang::get('message.updated-successfully');
    }
}
