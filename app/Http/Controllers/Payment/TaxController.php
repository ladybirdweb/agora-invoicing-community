<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\TaxRequest;
use App\Model\Common\Country;
use App\Model\Common\State;
use App\Model\Payment\Tax;
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
        $this->middleware('auth', ['except' => 'GetState']);
        $this->middleware('admin', ['except' => 'GetState']);

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
     * @return Response
     */
    public function index()
    {
        try {
            $options = $this->tax_option->find(1);
            if (!$options) {
                $options = '';
            }
            $classes = $this->tax_class->lists('name', 'id')->toArray();
            if (count($classes) == 0) {
                $classes = $this->tax_class->get();
            }

            return view('themes.default1.payment.tax.index', compact('options', 'classes'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetTax()
    {
        return \Datatable::collection($this->tax->select('id', 'name', 'level', 'country', 'state', 'rate', 'tax_classes_id')->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->addColumn('tax_classes_id', function ($model) {
                            return ucfirst($this->tax_class->where('id', $model->tax_classes_id)->first()->name);
                        })
                        ->showColumns('name', 'level')
                        ->addColumn('country', function ($model) {
                            if ($this->country->where('country_code_char2', $model->country)->first()) {
                                return $this->country->where('country_code_char2', $model->country)->first()->country_name;
                            }
                        })
                        ->addColumn('state', function ($model) {
                            if ($this->state->where('state_subdivision_code', $model->state)->first()) {
                                return $this->state->where('state_subdivision_code', $model->state)->first()->state_subdivision_name;
                            }
                        })
                        ->showColumns('rate')
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('tax/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('name')
                        ->orderColumns('name')
                        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(TaxRequest $request)
    {
        try {
            $this->tax->fill($request->input())->save();

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
     * @return Response
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
     * @return Response
     */
    public function edit($id)
    {
        try {
            $tax = $this->tax->where('id', $id)->first();
            $classes = $this->tax_class->lists('name', 'id')->toArray();
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($tax->state);
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($tax->country);

            if (count($classes) == 0) {
                $classes = $this->tax_class->get();
            }

            return view('themes.default1.payment.tax.edit', compact('tax', 'classes', 'states', 'state'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            $tax = $this->tax->where('id', $id)->first();
            $tax->fill($request->input())->save();

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
     * @return Response
     */
    public function destroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $tax = $this->tax->where('id', $id)->first();
                    if ($tax) {
                        $tax->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.deleted-successfully').'
                </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.select-a-row').'
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

    public function GetState(Request $request)
    {
        try {
            $id = $request->input('country_id');
            $states = \App\Model\Common\State::where('country_code_char2', $id)->get();
            //return $states;
            echo '<option value=>Select State</option>';
            foreach ($states as $state) {
                echo '<option value='.$state->state_subdivision_code.'>'.$state->state_subdivision_name.'</option>';
            }
        } catch (\Exception $ex) {
            echo "<option value=''>Problem while loading</option>";
        }
    }

    public function options(Request $request)
    {
        try {
            //dd($request->all());
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
                $this->tax_class->fill($request->input())->save();
            }

            return redirect()->back()->with('success', \Lang::get('message.created-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
