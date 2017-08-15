<?php

namespace App\Http\Controllers\Licence;

use App\Http\Controllers\Controller;
use App\Model\Licence\LicencedOrganization;
use App\Organization;
use Illuminate\Http\Request;

class LicenceOrderController extends Controller
{
    public $LicencedOrg;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $licenced = new LicencedOrganization();
        $this->LicencedOrg = $licenced;

        $organization = new Organization();
        $this->org = $organization;
    }

    public function index()
    {
        try {
            return view('themes.default1.licence.order.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetOrders()
    {
        return \Datatable::collection($this->LicencedOrg->get())
                        ->addColumn('organization', function ($model) {
                            $org = $this->org->where('id', $model->organization_id)->first();

                            return '<a href='.url('organization/'.$org->id).'>'.ucfirst($org->name).'</a>';
                        })
                        ->showColumns('licence_name', 'licence_description', 'number_of_sla', 'price', 'payment_status')
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('licence-orders/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                        })

                        ->searchColumns('licence_name')
                        ->orderColumns('licence_name')
                        ->make();
    }

    //    public function create() {
//        try {
//            $productController = new \App\Http\Controllers\Product\ProductController();
//            $url = $productController->GetMyUrl();
//            $i = $this->licence->orderBy('created_at', 'desc')->first()->id + 1;
//            $cartUrl = $url . "/cart?id=" . $i;
//            return view('themes.default1.licence.order.create', compact('cartUrl'));
//        } catch (\Exception $ex) {
//            return redirect()->back()->with('fails', $ex->getMessage());
//        }
//    }
//
//    public function store(Request $request) {
//        try {
//            $this->licence->fill($request->input())->save();
//            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
//        } catch (\Exception $ex) {
//            return redirect()->back()->with('fails', $ex->getMessage());
//        }
//    }
//
//    public function edit($id) {
//        try {
//            $licence = $this->licence->where('id', $id)->first();
//            return view('themes.default1.licence.order.edit', compact('licence'));
//        } catch (\Exception $ex) {
//            return redirect()->back()->with('fails', $ex->getMessage());
//        }
//    }
//
//    public function update($id, Request $request) {
//        try {
//            $licence = $this->licence->where('id', $id)->first();
//            $licence->fill($request->input())->save();
//            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
//        } catch (\Exception $ex) {
//            return redirect()->back()->with('fails', $ex->getMessage());
//        }
//    }
}
