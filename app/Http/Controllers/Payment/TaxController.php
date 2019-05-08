<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Model\Common\Country;
use App\Model\Common\State;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxByState;
use App\Model\Payment\TaxClass;
use App\Model\Payment\TaxOption;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public $tax;
    public $country;
    public $state;
    public $tax_option;
    public $tax_class;

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'getState']);
        $this->middleware('admin', ['except' => 'getState']);

        $tax = new Tax();
        $this->tax = $tax;

        $country = new Country();
        $this->country = $country;

        $state = new State();
        $this->state = $state;

        $tax_option = new TaxOption();
        $this->tax_option = $tax_option;

        $tax_class = new TaxClass();
        $this->tax_class = $tax_class;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Response
     */
    public function index()
    {
        try {
            $options = $this->tax_option->find(1);
            if (!$options) {
                $options = '';
            }
            $classes = $this->tax_class->pluck('name', 'id')->toArray();
            if (count($classes) == 0) {
                $classes = $this->tax_class->get();
            }
            $gstNo = $this->tax_option->find(1);

            return view('themes.default1.payment.tax.index', compact('options', 'classes', 'gstNo'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * @return type
     */
    public function getTax()
    {
        return \DataTables::of($this->tax->select('id', 'tax_classes_id', 'name', 'country', 'state', 'rate')->get())
                            ->addColumn('checkbox', function ($model) {
                                return "<input type='checkbox' class='tax_checkbox' 
                                value=".$model->id.' name=select[] id=check>';
                            })
                            ->addColumn('tax_classes_id', function ($model) {
                                return ucfirst($this->tax_class->where('id', $model->tax_classes_id)->first()->name);
                            })
                            ->addColumn('name', function ($model) {
                                return ucfirst($model->name);
                            })

                            // ->showColumns('name', 'level')
                            ->addColumn('country', function ($model) {
                                if ($this->country->where('country_code_char2', $model->country)->first()) {
                                    return ucfirst($this->country
                                      ->where('country_code_char2', $model->country)->first()->country_name);
                                }
                            })
                            ->addColumn('state', function ($model) {
                                if ($this->state->where('state_subdivision_code', $model->state)->first()) {
                                    return $this->state
                                    ->where('state_subdivision_code', $model->state)
                                    ->first()->state_subdivision_name;
                                }
                            })
                            ->addColumn('rate', function ($model) {
                                return $model->rate;
                            })

                            // ->showColumns('rate')
                            ->addColumn('action', function ($model) {
                                return '<a href='.url('tax/'.$model->id.'/edit').
                                " class='btn btn-sm btn-primary btn-xs'><i class='fa fa-edit' 
                                style='color:white;'> </i>&nbsp;&nbsp;Edit</a>";
                            })
                            ->rawColumns(['checkbox', 'tax_classes_id', 'name', 'country', 'state', 'rate', 'action'])
                            ->make(true);
    }

    public function getTaxTable()
    {
        return \DataTables::of(TaxByState::select('id', 'state', 'c_gst', 's_gst', 'i_gst', 'ut_gst')->get())
                         ->addColumn('id', function ($model) {
                             return $model->id;
                         })

                         ->addColumn('state', function ($model) {
                             return ucfirst($model->state);
                         })
                         ->addColumn('c_gst', function ($model) {
                             return ucfirst($model->c_gst);
                         })
                         ->addColumn('s_gst', function ($model) {
                             return ucfirst($model->s_gst);
                         })
                         ->addColumn('i_gst', function ($model) {
                             return ucfirst($model->i_gst);
                         })
                         ->addColumn('ut_gst', function ($model) {
                             return ucfirst($model->ut_gst);
                         })
                          ->rawColumns(['id', 'state',  'c_gst', 's_gst', 'i_gst', 'ut_gst'])
                          ->make(true);
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
        try {
            $tax = $this->tax->where('id', $id)->first();
            $taxClassName[] = $tax->taxClass()->find($tax->tax_classes_id)->name; //Find the Tax Class Name related to the tax
            $txClass = $this->tax_class->where('id', $tax->tax_classes_id)->first();
            // if ($txClass->name == 'Others') {
            //     $classes = 0;
            // } elseif ($txClass->name == 'CGST+SGST') {
            //     $classes = 1;
            // } elseif ($txClass->name == 'IGST') {
            //     $classes = 2;
            // } else {
            //     $classes = 3;
            // }
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($tax->state);
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($tax->country);
            // if ($classes) {
            //     $classes = $this->tax_class->get();
            // }

            return view('themes.default1.payment.tax.edit',
                compact('tax', 'txClass', 'states', 'state', 'taxClassName'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function update($id, Request $request)
    {
        try {
            $taxClassesName = $request->tax_classes_id;
            $defaultValue = ['Others', 'Intra State GST', 'Inter State GST', 'Union Territory GST'];
            $TaxClass = TaxClass::where('name', $request->tax_classes_id)->first();
            if ($TaxClass == null) {
                $TaxClass = $this->tax_class->create(['name'=>$taxClassesName]);
            }
            $taxId = $TaxClass->id;
            $tax = $this->tax->where('id', $id)->first();
            $tax->fill($request->except('tax_classes_id'))->save();

            $this->tax->where('id', $id)->update(['tax_classes_id'=> $taxId]);
            if ($taxClassesName != 'Others') {
                $country = 'IN';
                $state = 'Any State';
                $rate = '';
                $this->tax->where('id', $id)
                ->update(['tax_classes_id'=> $taxId, 'country'=>$country, 'state'=>$state, 'rate'=>$rate]);
            }

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
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
                    $tax = $this->tax->where('id', $id)->first();
                    $taxClassId = $tax->tax_classes_id;
                    $taxClass = $this->tax_class->where('id', $taxClassId)->first();
                    if ($tax) {
                        $taxClass->delete();
                        $tax->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>

                        <b>"./* @scrutinizer ignore-type */ \Lang::get('message.alert').'!
                        </b> './* @scrutinizer ignore-type */ \Lang::get('message.failed').'

                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            '.\Lang::get('message.no-record').'
                    </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                        <i class='fa fa-ban'></i>

                        <b>".\Lang::get('message.alert').'!</b> '.
                        /* @scrutinizer ignore-type */ \Lang::get('message.success').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'

                    </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>

                        <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                        /* @scrutinizer ignore-type */ \Lang::get('message.failed').'
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

    /**
     * @param Request $request
     * @param type    $state
     *
     * @return type
     */
    public function getState(Request $request, $stateid)
    {
        try {
            $id = $stateid;
            $states = \App\Model\Common\State::where('country_code_char2', $id)
            ->orderBy('state_subdivision_name', 'asc')->get();
            echo '<option value="">Choose</option>';
            foreach ($states as $state) {
                echo '<option value='.$state->state_subdivision_code.'>'.$state->state_subdivision_name.'</option>';
            }
        } catch (\Exception $ex) {
            echo "<option value=''>Problem while loading</option>";

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */

    /**
     * @param Request $request
     *
     * @return type
     */
    public function options(Request $request)
    {
        if ($request->input('name') == 'Others') {
            // if($request->)
            $this->validate($request, [
            'rate'        => 'required',
        ]);
        }

        try {
            $method = $request->method();
            if ($method == 'PATCH') {
                $rules = $this->tax_option->find(1);
                if (!$rules) {
                    $this->tax_option->create($request->input());
                } else {
                    $rules->fill($request->input())->save();
                }
            } else {
                $v = \Validator::make($request->all(), ['name' => 'required']);
                if ($v->fails()) {
                    return redirect()->back()
                                        ->withErrors($v)
                                        ->withInput();
                }
                $this->tax_class->fill($request->except('tax-name', 'level',
                  'active', 'country', 'country1', 'rate'))->save();
                $country = ($request->input('rate')) ? $request->input('country') : $request->input('country1');

                $this->tax->fill($request->except('tax-name', 'name', 'country'))->save();
                $taxClass = TaxClass::orderBy('id', 'DESC')->first();
                $tax = Tax::orderBy('id', 'DESC')->first();
                $tax->name = $request->input('tax-name');
                $tax->country = $country;
                $tax->tax_classes_id = $taxClass->id;
                $tax->save();
            }

            return redirect()->back()->with('success', \Lang::get('message.created-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
