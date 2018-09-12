<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Model\Product\ProductType;
use Bugsnag;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public $productType;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $productType = new ProductType();
        $this->productType = $productType;
    }

    public function index()
    {
        return view('themes.default1.product.type.index');
    }

    /*
    * Get All the categories
    */
    public function getTypes()
    {
        try {
            $allTypes = $this->productType->select('id', 'name')->get();

            return \DataTables::of($allTypes)
         ->addColumn('checkbox', function ($model) {
             return "<input type='checkbox' class='type_checkbox' 
            value=".$model->id.' name=select[] id=check>';
         })
         ->addColumn('type_name', function ($model) {
             return ucfirst($model->name);
         })
         ->addColumn('action', function ($model) {
             return "<p><button data-toggle='modal' 
             data-id=".$model->id." data-name= '$model->name' 
             class='btn btn-sm btn-primary btn-xs editType'><i class='fa fa-edit'
             style='color:white;'> </i>&nbsp;&nbsp;Edit</button>&nbsp;</p>";
         })
         ->rawColumns(['checkbox', 'type_name', 'action'])
         ->make(true);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $productType = $this->productType->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $type_name = $request->input('name');
            $type = $this->productType->where('id', $id)->update(['name' =>$type_name]);

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    if ($id != 1) {
                        $type = $this->productType->where('id', $id)->first();
                        if ($type) {
                            $type->delete();
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
}
