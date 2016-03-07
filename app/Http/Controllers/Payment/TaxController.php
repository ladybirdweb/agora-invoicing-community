<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\TaxRequest;
use App\Model\Common\Country;
use App\Model\Common\State;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxRules;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public $tax;
    public $rule;
    public $country;
    public $state;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $tax = new Tax();
        $this->tax = $tax;

        $rule = new TaxRules();
        $this->rule = $rule;

        $country = new Country();
        $this->country = $country;

        $state = new State();
        $this->state = $state;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $rule = $this->rule->findOrNew('1');

            return view('themes.default1.payment.tax.index', compact('rule'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetTax()
    {
        return \Datatable::collection($this->tax->select('id', 'name', 'level', 'country', 'state', 'rate')->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->showColumns('name', 'level')
                        ->addColumn('country', function ($model) {
                            if ($this->country->where('id', $model->country)->first()) {
                                return $this->country->where('id', $model->country)->first()->name;
                            }

                        })
                        ->addColumn('state', function ($model) {
                            if ($this->state->where('id', $model->state)->first()) {
                                return $this->state->where('id', $model->state)->first()->name;
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

            return view('themes.default1.payment.tax.edit', compact('tax'));
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

    public function Rule(Request $request)
    {
        try {
            $rule = $this->rule->where('id', '1')->first();
            if ($rule) {
                $rule->fill($request->input())->save();
            } else {
                $this->rule->create(['id' => 1]);
            }

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetState(Request $request)
    {
        try {
            $id = $request->input('country_id');
            //dd($id);
            $states = \App\Model\Common\State::where('country_id', $id)->get();
            //dd($states);
            foreach ($states as $state) {
                echo '<option value='.$state->id.'>'.$state->name.'</option>';
            }
        } catch (\Exception $ex) {
            echo "<option value=''>Problem while loading</option>";
        }
    }
}
