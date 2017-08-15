<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Model\Product\Plan;
use App\Model\Product\Product;
use App\Model\Product\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $product = new Product();
        $this->product = $product;
        //        $plan = new Plan();
        //        $this->plan = $plan;
        $service = new Service();
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('themes.default1.product.addon.index');
    }

    public function GetServices()
    {
        return \Datatable::collection($this->service->get())
                        ->addColumn('#', function ($model) {
                            return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                        })
                        ->showColumns('name')
                        ->addColumn('plan', function ($model) {
                            //return $this->product->plan()->name;
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('products/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
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
        $product = $this->product;
        //$plan = $this->plan;
        return view('themes.default1.product.service.create', compact('product', 'plan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->service->fill($request->input())->save();

        return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
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
        $product = $this->product;
        $plan = $this->plan;
        $service = $this->service->where('id', $id)->first();

        return view('themes.default1.product.service.edit', compact('service', 'plan', 'addon'));
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
        $service = $this->service->where('id', $id)->first();
        $service->fill($request->input())->save();

        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ids = $request->input('select');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $service = $this->service->where('id', $id)->first();
                if ($service) {
                    $service->delete();
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
    }
}
