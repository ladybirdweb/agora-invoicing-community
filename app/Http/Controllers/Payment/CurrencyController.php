<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Model\Payment\Currency;
use Form;
use Illuminate\Http\Request;
use Lang;

class CurrencyController extends Controller
{
    public $currency;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $currency = new Currency();
        $this->currency = $currency;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('themes.default1.payment.currency.index');
    }

    public function GetCurrency()
    {
        return \Datatable::collection($this->currency->select('name', 'id')->where('id', '!=', 1)->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->showColumns('name', 'base_conversion')
                        ->addColumn('action', function ($model) {
                            //return "<a href=" . url('products/' . $model->id . '/edit') . " class='btn btn-sm btn-primary'>Edit</a>";
                            //return "<a href=#create class='btn btn-primary pull-right' data-toggle=modal data-target=#edit".$model->id.">".\Lang::get('message.create')."</a>".  include base_path(). '/resources/views/themes/default1/payment/currency/edit.blade.php';

                            return "<a href=#edit class='btn btn-primary' data-toggle='modal' data-target=#edit".$model->id.'>'.\Lang::get('message.edit')."</a>
        <div class='modal fade' id=edit".$model->id.">
<div class='modal-dialog'>
    <div class='modal-content'>
        <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
            <h4 class='modal-title'>Edit Currency</h4>
        </div>
        ".Form::model($model, ['url' => 'currency/'.$model->id, 'method' => 'patch'])."
        <div class='modal-body'>
           
            
                
            

            <div class='form-group'>
               
                ".Form::label('name', Lang::get('message.name'), ['class' => 'required']).'
                                    '.Form::text('name', null, ['class' => 'form-control'])."

            </div>
            <div class='form-group'>
                ".Form::label('code', Lang::get('message.code'), ['class' => 'required']).'
                                    '.Form::text('code', null, ['class' => 'form-control'])."

            </div>
            <div class='form-group'>
                ".Form::label('symbol', Lang::get('message.symbol')).'
                                    '.Form::text('symbol', null, ['class' => 'form-control'])."

            </div>
            <div class='form-group'>
                ".Form::label('base_conversion', Lang::get('message.base_conversion_rate'), ['class' => 'required']).'
                                    '.Form::text('base_conversion', null, ['class' => 'form-control'])."

            </div>


        </div>
        <div class='modal-footer'>
            <button type=button id=close class='btn btn-default pull-left' data-dismiss=modal>Close</button>
            <input type=submit class='btn btn-primary' value=".\Lang::get('message.save').'>
                
        </div>
       
             '.Form::close().'
        
        
        
    </div>
   
</div>
</div>';
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'code'            => 'required',
            'name'            => 'required',
            'base_conversion' => 'required',
        ]);

        try {
            $this->currency->fill($request->input())->save();

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
        //
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
        $this->validate($request, [
            'code'            => 'required',
            'name'            => 'required',
            'base_conversion' => 'required',
        ]);

        try {
            $currency = $this->currency->where('id', $id)->first();
            $currency->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $ex) {
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
                    if ($id != 1) {
                        $currency = $this->currency->where('id', $id)->first();
                        if ($currency) {
                            $currency->delete();
                        } else {
                            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.no-record').'
                </div>';
                            //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
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
                        '.\Lang::get('message.can-not-delete-default').'
                </div>';
                    }
                }
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
}
